<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use Symfony\Component\DomCrawler\Crawler;
use App\Models\Link;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    public function getEventInfo()
    {
        set_time_limit(0);

        $months = [
            '01' => 'января',
            '02' => 'февраля',
            '03' => 'марта',
            '04' => 'апреля',
            '05' => 'мая',
            '06' => 'июня',
            '07' => 'июля',
            '08' => 'августа',
            '09' => 'сентября',
            '10' => 'октября',
            '11' => 'ноября',
            '12' => 'декабря',
        ];

        $categories = [
            'child' => 'Детские',
            'concerts' => 'Концерты',
            'theatres' => 'Театры',
            'klubs' => 'Клубы',
            'sport' => 'Спорт',
            'seminars' => 'Семинары',
            'circus' => 'Цирк',
        ];

        $video = '';

        $eventLinks = null;
        $eventLinks = Link::where([['status', 'wait'],['belongs_to', 'musin.zp'] ])->pluck('links')->toArray();

        foreach ($eventLinks as $eventLink){

            $html = file_get_contents($eventLink);
            $crawler = new Crawler($html);

            $title = $crawler->filterXPath('//div[@class="row event"]//div[@class="col-xs-8"]//h1')->html();
            $description = $crawler->filterXPath('//div[@class="col-xs-12 content_desc"]')->html();
            $eventSessionHtml = $crawler->filterXPath('//div[@class="btn-buy-container"]//a');

            $eventSessionInfo = [];

            foreach ($eventSessionHtml as $node){
                $eventSessionInfo[] = explode(',', $node->nodeValue);
            }

            $eventSession = [];

            if ($eventSessionInfo){
                foreach ($eventSessionInfo as $index => $session){
                    $session[1] = explode(' ', trim($session[1]));
                    $sessionMonth = $session[1][1];

                    foreach ($months as $numberMonth => $month){
                        if ($sessionMonth == $month){
                            $sessionMonth = $numberMonth;
                        }
                    }
                    $sessionYear = $sessionMonth < now()->format('m') ? now()->addYear()->format('Y') : now()->format('Y');

                    $sessionDay = $session[1][0] < 10 ? '0' . $session[1][0] : $session[1][0];
                    $sessionDate = $sessionDay . '-' . $sessionMonth . '-' . $sessionYear;
                    $eventSession[$sessionDate]['time'][] = $session[2];
                }
            } else {
                Link::where('links', $eventLink)->update(['status' => 'no tickets']);
               continue;
            }

            $eventInfo = $crawler->filterXPath('//div[@class="h4"]')->text();

            $eventInfo = explode(',', $eventInfo);

            $eventSessionJson = json_encode($eventSession, JSON_UNESCAPED_UNICODE);

            $city = trim($eventInfo[0]);

            $startDate = null;

            if (count($eventInfo)>1){
                if (count($eventInfo)>2){
                    $startDateInfo = trim($eventInfo[2]);
                    $startDate = explode(' ', $startDateInfo);
                }else{
                    $startDateInfo = trim($eventInfo[1]);
                    $startDateInfo = explode('-', $startDateInfo);
                    $startDate = explode(' ', trim($startDateInfo[0]));
                    $yearDateInfo = explode(' ', trim($startDateInfo[1]));
                    $startDate[2] = $yearDateInfo[2];
                }

                foreach ($months as $numberMonth => $month){
                    if ($startDate[1] == $month){
                        $startDate[1] = $numberMonth;
                    }
                }
                $startDate = intval($startDate[2]).'-'.$startDate[1].'-'.$startDate[0];
            }



            if ($startDate == null ){
                $startDate = array_keys($eventSession)[0];
            }

            $eventObject = $crawler->filterXPath('//div[@class="row event"]//div[@class="col-xs-8"]//div[2]//a')->html();

            $eventObjectLink = 'http://www.musin.zp.ua' . $crawler->filterXPath('//div[@class="row event"]//div[@class="col-xs-8"]//div[2]//a')->attr('href');

            $imageLink = 'http://www.musin.zp.ua' . $crawler->filterXPath('//div[@class="row event"]//div[@class="col-xs-4 text-center"]//div//img')->attr('src');

            $imageArray = explode('/', $imageLink);
            $imageName = 'mus' . array_pop( $imageArray);

            $image = file_get_contents($imageLink);

            $eventCategoryUri = explode('/' ,str_replace('http://www.musin.zp.ua/', '', $eventLink));

            $eventCategory = $eventCategoryUri[1];

            $eventCategoryName = null;
            foreach ($categories as $index => $category){
                if ($eventCategory == $index){
                    $eventCategoryName = $category;
                }
            }

            if ($eventCategoryName == null){
                Link::where('links', $eventLink)->update(['status' => 'no category']);
                continue;
            }

            $categoryId = Category::where('name', $eventCategoryName)->value('id');

            $eventUniqueName = 'musin'.$eventCategoryUri[0].$eventCategoryUri[2];

            $checkEvent = Event::where('unique_name', $eventUniqueName)->first();

            if (!$checkEvent){
                $event = null;

                Storage::disk('local')->put('public/images/'.$imageName, $image);

                $event = Event::create([
                    'title' => $title,
                    'description' => $description,
                    'start_date' => $startDate,
                    'city' => $city,
                    'sessions' => $eventSessionJson,
                    'image' => $imageName,
                    'category' => $categoryId,
                    'belongs_to' => 'musin.zp',
                    'published' => 1,
                    'video' => $video,
                    'link' =>$eventLink,
                    'unique_name' => $eventUniqueName,
                ]);

                if ($event){
                    echo $event->id;
                    echo '<br>';
                    Link::where('links', $eventLink)->update(['status' => 'success']);
                    echo '<br>-----------------------------------------<br>';

                } else {
                    echo $eventLink;
                    Link::where('links', $eventLink)->update(['status' => 'error']);
                    echo '<br>-----------------------------------------<br>';

                }
            }
        }
    }

    public function showEvents()
    {
        $events = Event::where('published', 1)->paginate(15);

        return view('parser.showEvents', ['events' => $events]);
    }

    public function showEvent($id)
    {
        $event = Event::where('id', $id)->first();


        $sessions =$event->sessions;

        $sessions = json_decode($sessions, true);

//        echo '<pre>';
//        var_dump($sessions);
//        die();

        return view('parser.showEvent', [
            'event' => $event,
            'sessions' => $sessions,
            ]);
    }





}

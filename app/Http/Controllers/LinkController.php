<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Link;
use Symfony\Component\DomCrawler\Crawler;

class LinkController extends Controller
{
    public function getLink()
    {
        $link = 'http://musin.zp.ua/';
        $html = file_get_contents($link);
        $crawler = new Crawler($html);

        $linksArray = null;
        $check = null;
        $flag = true;

        $linksArray = $crawler->filterXPath('//a[@class="btn btn-success btn-buy-small"]')->extract(array('href'));

        if ($linksArray){
            foreach ($linksArray as $uri){
                $eventLink = 'http://www.musin.zp.ua' . $uri;
                $check = Link::where('links', $eventLink)->value('links');
                if (!$check){
                    $flag =false;
                    echo $eventLink;
                    echo '<br>';
                    Link::create([
                        'links'=>$eventLink,
                        'status'=>'wait',
                        'belongs_to'=>'musin.zp'
                    ]);
                }
            }
        }

        if ($flag){
            echo 'No new link!';
        }
    }
}

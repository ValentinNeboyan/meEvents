<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Symfony\Component\DomCrawler\Crawler;


class Event extends Model
{
    protected $fillable = [
        'title',
        'description',
        'start_date',
        'city',
        'image',
        'category',
        'belongs_to',
        'published',
        'sessions',
        'video'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [

    ];



}

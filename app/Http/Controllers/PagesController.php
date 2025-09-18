<?php

namespace App\Http\Controllers;

use App\Models\SportModality;
use App\Models\Event;
use App\Models\Sport;

class PagesController extends Controller
{
    public function home()
    {
        $route = 'home';
        $sport_modalities = SportModality::all();
        $sports = Sport::all();
        $events = Event::all();
        // $Competitions = Competition::all();
        // dd($sport_modalities);
        return view('pages.home', compact('sport_modalities','sports', 'events'));
    }
}

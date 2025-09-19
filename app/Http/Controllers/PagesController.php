<?php

namespace App\Http\Controllers;

use App\Models\Sport;
use App\Models\SportModality;
use App\Models\Event;

class PagesController extends Controller
{
    public function home()
    {
        $route = 'home';

        // Agora sim: Esportes de verdade
        $sports = Sport::with('sportModality')->get();

        // Se ainda quiser pegar modalidades soltas:
        $sport_modalities = SportModality::all();

        $events = Event::all();

        return view('pages.home', compact('sports', 'sport_modalities', 'events'));
    }
}

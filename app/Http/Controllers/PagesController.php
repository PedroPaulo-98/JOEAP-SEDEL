<?php

namespace App\Http\Controllers;

use App\Models\Sport;
use App\Models\SportModality;
use App\Models\Event;
use App\Models\PageText;


class PagesController extends Controller
{
    public function home()
    {
        $route = 'home';

        // Agora sim: Esportes de verdade ¬.¬ Olha os comentários 
        $sports = Sport::with('sportModality')->get();

        // Se ainda quiser pegar modalidades soltas:
        $sport_modalities = SportModality::all();

        $events = Event::all();


        $texts = PageText::firstOrCreate(
            ['id' => 1],
            ['data' => ['banner_title' => 'MAPA DOS', 'subtitle_banner' => 'ESPORTES']]
        );

        return view('pages.home', compact('sports', 'sport_modalities', 'events', 'texts'));
    }
}

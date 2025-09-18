@extends('layout.base_page')

@section('title', 'Página Inicial')

@section('content')
<header class="hero">
    <div class="hero-content">
        <h1>MAPA DOS ESPORTES</h1>
    </div>
</header>

<main>
    <section id="companies" class="companies">
        <div class="distance">
            <div class="section-title">
                <h2>Esportes</h2>
                <p>Fique por dentro</p>
            </div>

            <div class="systems-container">
                @foreach($sports as $sport)
                <div class="card">
                    <h5 class="card-title">{{ $sport->name }}</h5>
                </div>
                @endforeach
            </div>

            <div class="see-more">
                <a href="#"><b>VER MAIS MODALIDADES</b></a>
            </div>
        </div>
        <div class="distance">
            <div class="section-title">
                <h2>Eventos</h2>
                <p>Fique por dentro</p>
            </div>

            <div class="systems-container">
                @foreach($events as $event)
                <div class="card">
                    <h5 class="card-title">{{ $event->name }}</h5>
                </div>
                @endforeach
            </div>

            <div class="see-more">
                <a href="#"><b>VER MAIS EVENTOS</b></a>
            </div>
        </div>
    </section>
</main>
@endsection
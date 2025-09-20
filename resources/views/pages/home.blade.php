@extends('layout.base_page')

@section('title', 'Página Inicial')

@section('content')
<header class="hero" style="background: url('{{ $texts->getText('banner') }}') top center; background-size: cover;">
    <div class="hero-content">
        <h1>
            {{ $texts->getText('banner_title') }}
            <br>{{ $texts->getText('subtitle_banner') }}
        </h1>
    </div>
</header>

<main>
    <section id="companies" class="companies">
        <div class="distance">
            <div class="section-title">
                <h2>Esportes</h2>
            </div>

            <div class="systems-container">
                @foreach($sports as $sport)
                <div class="card" data-modal="modal-{{ $sport->id }}">
                    <h5 class="card-title">{{ $sport->name }}</h5>
                </div>

                <!-- Modal de cada esporte -->
                <div class="modal" id="modal-{{ $sport->id }}">
                    <div class="modal-content">
                        <span class="close">&times;</span>
                        <h3>{{ $sport->name }}</h3>
                        <ul>
                            @forelse($sport->sportModality as $modality)
                            <li>
                                <strong>Modalidade:</strong> {{ $modality->name }} <br>
                                <strong>Gênero:</strong> {{ $modality->gender->name ?? 'Sem gênero' }} <br>
                                <strong>Idade:</strong> {{ $modality->min_age }} - {{ $modality->max_age }} anos <br>
                                <strong>Peso:</strong> {{ $modality->min_weight ?? '--' }} - {{ $modality->max_weight ?? '--' }} kg
                            </li>
                            @empty
                            <li>Sem modalidades cadastradas</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="distance">
                <div class="section-title">
                    <h2>Eventos</h2>
                </div>

                <!-- Eventos Abaixo com modal -->
                <div class="systems-container">
                    @foreach($events as $event)
                    <div class="card" data-modal="eventModal-{{ $event->id }}">
                        <h5 class="card-title">{{ $event->name }}</h5>
                    </div>

                    <!-- Modal único para cada evento -->
                    <div class="modal" id="eventModal-{{ $event->id }}">
                        <div class="modal-content">
                            <span class="close">&times;</span>

                            <h3 class="event-title">{{ $event->name }}</h3>

                            <div class="event-info">
                                <p><strong>Descrição:</strong> {{ $event->description }}</p>
                                <p><strong>Início:</strong> {{ \Carbon\Carbon::parse($event->start_time)->format('d/m/y') }}</p>
                                <p><strong>Término:</strong> {{ \Carbon\Carbon::parse($event->end_time)->format('d/m/y') }}</p>
                            </div>
                        </div>
                    </div>

                    @endforeach
                </div>
            </div>
            <div>
                <iframe style="border:0; width: 100%; height: 270px;" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3610.5772623312632!2d-51.0533297!3d0.0305886!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8d61e18ae3ea9939%3A0x6e43945e9e63e996!2sSEDEL%20-%20Secretaria%20Estadual%20de%20Desporto%20e%20Lazer!5e1!3m2!1spt-BR!2sbr!4v1746572843692!5m2!1spt-BR!2sbr" frameborder="0" allowfullscreen></iframe>
            </div>
    </section>
</main>
@endsection
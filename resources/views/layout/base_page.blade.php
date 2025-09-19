<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'JOEAP')</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/index.css') }}">
  <style>
    body {
      /* altura mínima = altura da tela */
      display: flex;
      flex-direction: column;
    }

    main {
      min-height: 100vh;
      flex: 1;
      /* ocupa todo espaço livre */
    }

    footer {
      color: #fff;
    }

    footer a {
      color: #0d6efd;
      text-decoration: none;
    }

    footer a:hover {
      text-decoration: none;
    }
  </style>
</head>

<body>

  <!-- HEADER -->
  <header>
    <nav class="navbar navbar-expand-lg bg-dark navbar-dark py-3">
      <div class="container">
        <a class="navbar-brand fw-bold text-white" href="#">JOEAP</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav ms-auto">
            <li class="nav-item"><a class="nav-link text-white" href="#">Home</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="#">Sobre</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="#">Serviços</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="#">Contato</a></li>
          </ul>
        </div>
      </div>
    </nav>
  </header>

  <!-- CONTEÚDO -->
  @yield('content')

  <!-- FOOTER -->
  <footer class="footer">
    <div class="footer-container">

      <!-- Localização -->
      <div class="footer-section">
        <h3>Localização</h3>
        <p>R. Hildemar Maia, n. 1497<br>Macapá, Amapá.<br>Santa Rita. CEP: 68.901-271</p>
      </div>

      <!-- Contato -->
      <div class="footer-section">
        <h3>Contato</h3>
        <p>Email: sedel@sedel.ap.gov.br</p>
        <p>Central: (96) 4009-9650</p>
      </div>

      <!-- Redes sociais -->
      <div class="footer-section">
        <h3>Siga a SEDEL</h3>
        <div class="social-icons">
          <a href="#"><i class="ri-facebook-circle-fill"></i></a>
          <a href="#"><i class="ri-instagram-fill"></i></a>
          <a href="#"><i class="ri-linkedin-box-fill"></i></a>
          <a href="#"><i class="ri-twitter-x-fill"></i></a>
        </div>
      </div>

    </div>

    <!-- Direitos reservados -->
    <div class="footer-bottom">
      <p>&copy; 2025 SEDEL. Todos os direitos reservados.</p>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="{{ asset('js/sports_events.js') }}" type="module"></script>
</body>

</html>
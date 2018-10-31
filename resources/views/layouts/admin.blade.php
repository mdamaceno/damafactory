<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ mix('css/app.css') }}"></script>

    <title>Damafactory</title>
  </head>
  <body>
    <div id="app">
        @section('sidebar')
            sidebar
        @endsection

        <div class="container">
            @yield('content')
        </div>
	</div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="{{ mix('js/app.js') }}"></script>
  </body>
</html>

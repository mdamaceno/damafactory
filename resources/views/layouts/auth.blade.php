<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Damafactory</title>

    <link rel="stylesheet" href="{{ mix('assets/admin/css/app.css') }}">
  </head>
  <body class="bg-purple-800">
    <div id="app">
        <div class="page-content">
            <div class="content-wrapper">
                <div class="content d-flex justify-content-center align-items-center">
                    @yield('content')
                </div>
            </div>
        </div>
	</div>
    <script src="{{ mix('assets/admin/js/manifest.js') }}"></script>
    <script src="{{ mix('assets/admin/js/vendor.js') }}"></script>
    <script src="{{ mix('assets/admin/js/core.js') }}"></script>
    @include('sweet::alert')
    @yield('scripts')
  </body>
</html>

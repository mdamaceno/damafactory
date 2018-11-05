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
  <body class="bg-slate-300">
    <div id="app">
        @include('admin.partials.nav')

        <div class="page-content pt-0">
            @include('admin.partials.sidebar')
            <div class="content-wrapper">
                <div class="content">
                    @yield('content')
                </div>
            </div>
        </div>
	</div>
    <script src="{{ mix('assets/admin/js/manifest.js') }}"></script>
    <script src="{{ mix('assets/admin/js/vendor.js') }}"></script>
    <script src="{{ mix('assets/admin/js/core.js') }}"></script>
    @yield('scripts')
  </body>
</html>

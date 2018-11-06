  <!-- Main navbar -->
  <div class="navbar navbar-expand-md navbar-dark bg-slate-800" style="margin-bottom: 1.4em;">
    <div class="navbar-brand wmin-200">
      <a href="index.html" class="d-inline-block">
        <img src="/assets/admin/images/logo_light.png" alt="">
      </a>
    </div>

    <div class="d-md-none">
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-mobile">
        <i class="icon-tree5"></i>
      </button>
      <button class="navbar-toggler sidebar-mobile-main-toggle" type="button">
        <i class="icon-paragraph-justify3"></i>
      </button>
    </div>

    <div class="collapse navbar-collapse" id="navbar-mobile">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a href="#" class="navbar-nav-link sidebar-control sidebar-main-toggle d-none d-md-block">
            <i class="icon-paragraph-justify3"></i>
          </a>
        </li>
      </ul>

      <span class="navbar-text ml-md-auto mr-md-3">
      </span>

      <ul class="navbar-nav">
        <li class="nav-item dropdown dropdown-user">
          <a href="#" class="navbar-nav-link dropdown-toggle" data-toggle="dropdown">
            <span>{{ auth()->user()->email }}</span>
          </a>

          <div class="dropdown-menu dropdown-menu-right">
            <a href="#" class="dropdown-item"><i class="icon-cog5"></i> {{ __('Account settings') }}</a>
            <a href="{{ url('/logout') }}" class="dropdown-item"><i class="icon-switch2"></i> {{ __('Logout') }}</a>
          </div>
        </li>
      </ul>
    </div>
  </div>
  <!-- /main navbar -->

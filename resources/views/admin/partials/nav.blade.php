  <!-- Main navbar -->
  <div class="navbar navbar-expand-md " style="margin-bottom: 1.4em;">
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
        <li class="nav-item">
            <a href="javascript:void(0)" class="btn text-grey-300">{{ auth()->user()->email }}</i></a>
        </li>
        <li class="nav-item">
            <a href="{{ url('/admin/users/edit?modify=' . auth()->user()->id) }}" class="btn text-grey-300"><i class="icon-cog"></i></a>
        </li>
        <li class="nav-item">
            <a href="{{ url('/logout') }}" class="btn text-danger"><i class="icon-switch2"></i></a>
        </li>
      </ul>
    </div>
  </div>
  <!-- /main navbar -->

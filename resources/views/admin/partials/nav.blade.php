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
        <li class="nav-item dropdown">
            <a href="#" class="navbar-nav-link dropdown-toggle caret-0" data-toggle="dropdown">
                <img src="{{ config('app.supported_locales')[locale()->current()]['flag'] }}" alt="">
                <span class="d-md-none ml-2">Language</span>
            </a>
            <div class="dropdown-menu dropdown-content wmin-md-350">
                <div class="dropdown-content-body dropdown-scrollable">
                    <ul class="media-list">
                        @foreach (config('app.supported_locales') as $key => $lang)
                        <li class="media">
                            <div class="media-body">
                                <a href="{{ url("$key/admin") }}">
                                    <img src="{{ config("app.supported_locales.$key.flag") }}" alt=""> {{ __(config("app.supported_locales.$key.name")) }}
                                </a>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </li>
        <li class="nav-item dropdown">
            <a href="javascript:void(0)" class="navbar-nav-link btn text-grey-300">{{ auth()->user()->email }}</i></a>
        </li>
        <li class="nav-item dropdown">
            <a href="{{ url(locale()->current() . '/admin/users/edit?modify=' . auth()->user()->id) }}" class="navbar-nav-link btn text-grey-300"><i class="icon-cog"></i></a>
        </li>
        <li class="nav-item dropdown">
            <a href="{{ url('/logout') }}" class="navbar-nav-link btn text-danger"><i class="icon-switch2"></i></a>
        </li>
      </ul>
    </div>
  </div>
  <!-- /main navbar -->

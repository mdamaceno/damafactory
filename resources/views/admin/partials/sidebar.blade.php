<!-- Main sidebar -->
<div class="sidebar sidebar-light sidebar-main sidebar-expand-md align-self-start">

  <!-- Sidebar mobile toggler -->
  <div class="sidebar-mobile-toggler text-center">
    <a href="#" class="sidebar-mobile-main-toggle">
      <i class="icon-arrow-left8"></i>
    </a>
    <span class="font-weight-semibold">Main sidebar</span>
    <a href="#" class="sidebar-mobile-expand">
      <i class="icon-screen-full"></i>
      <i class="icon-screen-normal"></i>
    </a>
  </div>
  <!-- /sidebar mobile toggler -->

  <!-- Sidebar content -->
  <div class="sidebar-content">

    <!-- Navigation -->
    <div class="card card-sidebar-mobile">
      <div class="card-header header-elements-inline">
        <h6 class="card-title">{{ __('Menu') }}</h6>
        <div class="header-elements">
          <div class="list-icons">
            <a class="list-icons-item" data-action="collapse"></a>
          </div>
        </div>
      </div>

      <div class="card-body p-0">
        <ul class="nav nav-sidebar" data-nav-type="accordion">

          <li class="nav-item">
            <a href="{{ url('/admin') }}" class="nav-link active">
              <i class="icon-home4"></i>
              <span>
                {{ __('Dashboard') }}
              </span>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ url('/admin/databases') }}" class="nav-link active">
              <i class="icon-stack"></i>
              <span>
                {{ __('Databases') }}
              </span>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ url('/admin/users') }}" class="nav-link active">
              <i class="icon-users"></i>
              <span>
                {{ __('Users') }}
              </span>
            </a>
          </li>
          <!-- /main -->

        </ul>
      </div>
    </div>
    <!-- /navigation -->

  </div>
  <!-- /sidebar content -->

</div>
<!-- /main sidebar -->

<!-- Main sidebar -->
<div class="sidebar sidebar-main sidebar-expand-md align-self-start" style="box-shadow: none">

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
      <div class="card-body p-0">
        <ul class="nav nav-sidebar" data-nav-type="accordion">

          <li class="nav-item">
            <a href="{{ url('/admin') }}" class="nav-link text-grey-300 active">
              <i class="icon-home4"></i>
              <span>
                {{ __('Dashboard') }}
              </span>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ url('/admin/databases') }}" class="nav-link text-grey-300 active">
              <i class="icon-stack"></i>
              <span>
                {{ __('Databases') }}
              </span>
            </a>
          </li>
          @if (auth()->user()->role === 'master')
          <li class="nav-item">
            <a href="{{ url('/admin/permissions') }}" class="nav-link text-grey-300 active">
              <i class="icon-people"></i>
              <span>
                {{ __('Permissions') }}
              </span>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ url('/admin/users') }}" class="nav-link text-grey-300 active">
              <i class="icon-users"></i>
              <span>
                {{ __('Users') }}
              </span>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ url('/admin/auth-tokens') }}" class="nav-link text-grey-300 active">
              <i class="icon-key"></i>
              <span>
                {{ __('Auth Tokens') }}
              </span>
            </a>
          </li>
          @endif
          <li class="nav-item">
            <a href="{{ url('/admin/help') }}" class="nav-link text-grey-300 active">
              <i class="icon-help"></i>
              <span>
                {{ __('Help') }}
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

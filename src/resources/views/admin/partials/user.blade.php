<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" style="display: flex" href="#" id="navbarDropdown" role="button" data-toggle="dropdown"
       aria-haspopup="true" aria-expanded="false">
        {!!  fontAwesomeIcon('user') !!}
    </a>
    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
        <a class="dropdown-item" style="display: flex" href="{{ route('auth.logout') }}">
            {!!  fontAwesomeIcon('sign-out') !!} {{ trans('HCCore::user.admin.menu.logout') }}
        </a>
    </div>
</li>


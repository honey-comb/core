<li class="dropdown">
    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
        {!!  fontAwesomeIcon('user') !!}
    </a>
    <ul class="dropdown-menu dropdown-user">
        <li>
            <a href="{{ route('auth.logout') }}">
                <i class="fa fa-sign-out fa-fw"></i> {{ trans('HCCore::user.admin.menu.logout') }}
            </a>
        </li>
    </ul>
</li>

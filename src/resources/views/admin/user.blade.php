<li class="dropdown">
    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
        <i class="fa fa-user fa-fw"></i> <i class="fa fa-caret-down"></i>
    </a>
    <ul class="dropdown-menu dropdown-user">
        <li>
            <a href="{{ route('auth.logout') }}">
                <i class="fa fa-sign-out fa-fw"></i> {{ trans('HCCore::user.admin.menu.logout') }}
            </a>
        </li>
    </ul>
</li>

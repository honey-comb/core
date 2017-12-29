<header class="main-header">

    <a href="{{ route('admin.index') }}" class="logo">{{ config('app.name') }}</a>

    <nav class="navbar navbar-static-top" role="navigation">
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>

        <ul class="nav navbar-nav pull-left">
            <li>
                <a href="{{ url('/')}}" target="_blank">
                    {{ trans('HCCore::core.index') }}
                </a>
            </li>
        </ul>

        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">

                @include('HCCore::admin.user')

                <li>
                    <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                </li>
            </ul>
        </div>
    </nav>
</header>

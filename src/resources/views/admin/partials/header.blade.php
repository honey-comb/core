<header class="main-header">

    <a href="{{ route('admin.index') }}" class="logo">{{ config('app.name') }}</a>

    <nav class="navbar navbar-static-top">
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            {!! fontAwesomeIcon('bars') !!}
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

                @include('HCCore::admin.partials.language')

                @include('HCCore::admin.partials.user')

                <li>
                    <a href="#" data-toggle="control-sidebar">
                        {!! fontAwesomeIcon('cogs') !!}
                    </a>
                </li>
            </ul>
        </div>
    </nav>
</header>

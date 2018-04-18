<header class="main-header">

    <a href="{{ route('admin.index') }}" class="logo">{{ config('app.name') }}</a>

    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a href="#" class="nav-link" data-toggle="push-menu" role="button">
                        {!! fontAwesomeIcon('bars') !!}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/')}}" target="_blank">
                        {{ trans('HCCore::core.index') }}
                    </a>
                </li>
            </ul>
            <ul class="navbar-nav">
                @include('HCCore::admin.partials.user')
                @include('HCCore::admin.partials.language')
                <li class="nav-item dropdown">
                    <a href="#" class="nav-link" data-toggle="control-sidebar">
                        {!! fontAwesomeIcon('cogs') !!}
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    {{--<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="collapse navbar-collapse" id="navbarTogglerDemo02">
            <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
                <li class="nav-item">
                    <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                        {!! fontAwesomeIcon('bars') !!}
                        <span class="sr-only">Toggle navigation</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Link</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link disabled" href="#">Disabled</a>
                </li>
            </ul>
            <form class="form-inline my-2 my-lg-0">
                <input class="form-control mr-sm-2" type="search" placeholder="Search">
                <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
            </form>
        </div>
    </nav>--}}
    {{--<nav class="navbar navbar-static-top">


       <ul class="nav navbar-nav pull-left">
           <li>
               <a href="{{ url('/')}}" target="_blank">
                   {{ trans('HCCore::core.index') }}
               </a>
           </li>
       </ul>


   </nav>--}}
</header>

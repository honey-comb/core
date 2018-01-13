<aside class="main-sidebar">

    <section class="sidebar" style="height: auto;">

        <div class="sidebar-form">
            <div class="input-group">
                <input type="text"
                       name="q"
                       class="form-control search-menu-box"
                       placeholder="{{ trans('HCCore::core.filter') }}"/>
                <span class="input-group-btn">
                    <button name="search"
                            id="search-btn"
                            class="btn btn-flat">
                        <i class="fa fa-search"></i>
                    </button>
                </span>
            </div>
        </div>

        <ul class="sidebar-menu tree"
            data-widget="tree">

            @if( isset($adminMenu) )
                @include('HCCore::admin.partials.submenu', ['menuItems' => $adminMenu])
            @endif

        </ul>
    </section>
</aside>

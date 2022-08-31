<style>
    .c-sidebar .c-sidebar-nav-link:hover,
    .c-sidebar .c-sidebar-nav-dropdown-toggle:hover {

        background: #5DBA3C;
    }

    .c-sidebar {
        color: #fff;
        background: #003E69;
    }
</style>
<div class="c-sidebar c-sidebar-dark c-sidebar-fixed c-sidebar-lg-show" id="sidebar">
    <div class="c-sidebar-brand d-lg-down-none">
        <svg class="c-sidebar-brand-full" width="118" height="46" alt="CoreUI Logo">
            <use xlink:href="{{ asset('img/brand/coreui.svg#full') }}"></use>
        </svg>
        <svg class="c-sidebar-brand-minimized" width="46" height="46" alt="CoreUI Logo">
            <use xlink:href="{{ asset('img/brand/coreui.svg#signet') }}"></use>
        </svg>
    </div>
    <!--c-sidebar-brand-->

    <ul class="c-sidebar-nav">
        <li class="c-sidebar-nav-item">
            <x-utils.link class="c-sidebar-nav-link" :href="route('admin.dashboard')" :active="activeClass(Route::is('admin.dashboard'), 'c-active')" icon="c-sidebar-nav-icon cil-speedometer" :text="__('Dashboard')" />
        </li>

        @if (
        $logged_in_user->hasAllAccess() ||
        (
        $logged_in_user->can('admin.access.user.list') ||
        $logged_in_user->can('admin.access.user.deactivate') ||
        $logged_in_user->can('admin.access.user.reactivate') ||
        $logged_in_user->can('admin.access.user.clear-session') ||
        $logged_in_user->can('admin.access.user.impersonate') ||
        $logged_in_user->can('admin.access.user.change-password')
        )
        )
        <li class="c-sidebar-nav-title">@lang('System')</li>

        <li class="c-sidebar-nav-dropdown {{ activeClass(Route::is('admin.auth.user.*') || Route::is('admin.auth.role.*'), 'c-open c-show') }}">
            <x-utils.link href="#" icon="c-sidebar-nav-icon cil-user" class="c-sidebar-nav-dropdown-toggle" :text="__('Access')" />

            <ul class="c-sidebar-nav-dropdown-items">
                @if (
                $logged_in_user->hasAllAccess() ||
                (
                $logged_in_user->can('admin.access.user.list') ||
                $logged_in_user->can('admin.access.user.deactivate') ||
                $logged_in_user->can('admin.access.user.reactivate') ||
                $logged_in_user->can('admin.access.user.clear-session') ||
                $logged_in_user->can('admin.access.user.impersonate') ||
                $logged_in_user->can('admin.access.user.change-password')
                )
                )
                <li class="c-sidebar-nav-item">
                    <x-utils.link :href="route('admin.auth.user.index')" class="c-sidebar-nav-link" :text="__('User Management')" :active="activeClass(Route::is('admin.auth.user.*'), 'c-active')" />
                </li>
                @endif

                @if ($logged_in_user->hasAllAccess())
                <li class="c-sidebar-nav-item">
                    <x-utils.link :href="route('admin.auth.role.index')" class="c-sidebar-nav-link" :text="__('Role Management')" :active="activeClass(Route::is('admin.auth.role.*'), 'c-active')" />
                </li>
                @endif
            </ul>
        </li>
        @endif


        @if ($logged_in_user->hasAllAccess())
        <li class="c-sidebar-nav-dropdown">
            <a href="#" class="c-sidebar-nav-dropdown-toggle"><i class="c-sidebar-nav-icon cil-user"></i> Members</a>
            <ul class="c-sidebar-nav-dropdown-items">
                <li class="c-sidebar-nav-item">
                    <x-utils.link :href="route('admin.members.index')" class="c-sidebar-nav-link" :text="__('Members List')" :active="activeClass(Route::is('admin.members.index.*'), 'c-active')" />
                </li>
            </ul>
        </li>

        @endif

        @if ($logged_in_user->hasAllAccess())
        <li class="c-sidebar-nav-dropdown">
            <a href="#" class="c-sidebar-nav-dropdown-toggle"><i class="c-sidebar-nav-icon cil-user"></i> Trainers</a>
            <ul class="c-sidebar-nav-dropdown-items">
                <li class="c-sidebar-nav-item">
                    <x-utils.link :href="route('admin.trainers.create')" class="c-sidebar-nav-link" :text="__('Add Trainers ')" :active="activeClass(Route::is('admin.trainers.create.*'), 'c-active')" />
                </li>

                <li class="c-sidebar-nav-item">
                    <x-utils.link :href="route('admin.trainers.index')" class="c-sidebar-nav-link" :text="__('Trainers List')" :active="activeClass(Route::is('admin.trainers.index.*'), 'c-active')" />
                </li>
            </ul>
        </li>

        @endif

        @if ($logged_in_user->hasAllAccess())
        <li class="c-sidebar-nav-dropdown">
            <a href="#" class="c-sidebar-nav-dropdown-toggle"><i class="c-sidebar-nav-icon cil-user"></i> Fighter</a>
            <ul class="c-sidebar-nav-dropdown-items">
                <!-- <li class="c-sidebar-nav-item">
                    <x-utils.link :href="route('admin.dashboard')" class="c-sidebar-nav-link" :text="__('Fighter List')" :active="activeClass(Route::is('admin.trainers.index.*'), 'c-active')" />
                </li> -->
                <li class="c-sidebar-nav-item">
                    <x-utils.link :href="route('admin.fighter.index')" class="c-sidebar-nav-link" :text="__('Fighter List')" :active="activeClass(Route::is('admin.trainers.index.*'), 'c-active')" />
                </li>
            </ul>
        </li>

        @endif



        @if ($logged_in_user->hasAllAccess())
        <li class="c-sidebar-nav-item">
            <x-utils.link class="c-sidebar-nav-link" :href="route('admin.dashboard')" :active="activeClass(Route::is('admin.dashboard'))" icon="c-sidebar-nav-icon cil-user" :text="__('Workouts')" />
        </li>
        @endif

        @if ($logged_in_user->hasAllAccess())
        <li class="c-sidebar-nav-item">
            <x-utils.link class="c-sidebar-nav-link" :href="route('admin.dashboard')" :active="activeClass(Route::is('admin.dashboard'))" icon="c-sidebar-nav-icon cil-user" :text="__('Subscription')" />
        </li>
        @endif

        @if ($logged_in_user->hasAllAccess())
        <li class="c-sidebar-nav-item">
            <x-utils.link class="c-sidebar-nav-link" :href="route('admin.dashboard')" :active="activeClass(Route::is('admin.dashboard'))" icon="c-sidebar-nav-icon cil-user" :text="__('Voting')" />
        </li>
        @endif


        @if ($logged_in_user->hasAllAccess())
        <li class="c-sidebar-nav-dropdown">
            <x-utils.link href="#" icon="c-sidebar-nav-icon cil-list" class="c-sidebar-nav-dropdown-toggle" :text="__('Logs')" />

            <ul class="c-sidebar-nav-dropdown-items">
                <li class="c-sidebar-nav-item">
                    <x-utils.link :href="route('log-viewer::dashboard')" class="c-sidebar-nav-link" :text="__('Dashboard')" />
                </li>
                <li class="c-sidebar-nav-item">
                    <x-utils.link :href="route('log-viewer::logs.list')" class="c-sidebar-nav-link" :text="__('Logs')" />
                </li>
            </ul>
        </li>
        @endif
    </ul>

    <button class="c-sidebar-minimizer c-class-toggler" type="button" data-target="_parent" data-class="c-sidebar-minimized"></button>
</div>
<!--sidebar-->
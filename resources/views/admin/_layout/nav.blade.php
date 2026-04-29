<div class="nav-content d-flex">
    <!-- Logo Start -->
    <div class="logo position-relative">
        <a href="/admin">
            <div class="img"></div>
        </a>
    </div>
    <!-- Logo End -->
    <!-- Language Switch Start -->
    <div class="language-switch-container">
        <button class="btn btn-empty language-button dropdown-toggle"
                data-bs-toggle="dropdown"
                aria-haspopup="true"
                aria-expanded="false">
            {{ strtoupper(app()->getLocale()) }}
        </button>

        <div class="dropdown-menu">
            <a href="#" data-lang="az" class="dropdown-item {{ App::isLocale('az') ? 'active' : '' }}">AZ</a>
            <a href="#" data-lang="en" class="dropdown-item {{ App::isLocale('en') ? 'active' : '' }}">EN</a>
            <a href="#" data-lang="ru" class="dropdown-item {{ App::isLocale('ru') ? 'active' : '' }}">RU</a>
            <a href="#" data-lang="tr" class="dropdown-item {{ App::isLocale('tr') ? 'active' : '' }}">TR</a>
        </div>
    </div>
    <!-- Language Switch End -->
    <!-- User Menu Start -->
    <div class="user-container d-flex">
        <a href="#" class="d-flex user position-relative" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <img class="profile" alt="profile" src="{{asset('backend/img/profile/male.png')}}" />
            <div class="name">{{ user()->fullname }}</div>
        </a>
        <div class="dropdown-menu dropdown-menu-end user-menu wide">
            <div class="row mb-1 ms-0 me-0">
                <div class="col-6 pe-1 ps-1">
                    <ul class="list-unstyled">
                        <li>
                            <a href="{{ route('admin.profile.index') }}">
                                <i data-acorn-icon="gear" class="me-2" data-acorn-size="17"></i>
                                <span class="align-middle">{{__('my_profile')}}</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{route('admin.logout')}}">
                                <i data-acorn-icon="logout" class="me-2" data-acorn-size="17"></i>
                                <span class="align-middle">{{__('logout')}}</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- User Menu End -->

    <!-- Icons Menu Start -->
    <ul class="list-unstyled list-inline text-center menu-icons">
        <li class="list-inline-item">
            <a href="#" id="pinButton" class="pin-button">
                <i data-acorn-icon="lock-on" class="unpin" data-acorn-size="18"></i>
                <i data-acorn-icon="lock-off" class="pin" data-acorn-size="18"></i>
            </a>
        </li>
        <li class="list-inline-item">
            <a href="#" id="colorButton">
                <i data-acorn-icon="light-on" class="light" data-acorn-size="18"></i>
                <i data-acorn-icon="light-off" class="dark" data-acorn-size="18"></i>
            </a>
        </li>

    </ul>
    <!-- Icons Menu End -->
    <!-- Menu Start -->
    <div class="menu-container flex-grow-1">
        <ul id="menu" class="menu">
            @can('admin.menu')
            <li>
                <a href="#adminMenu" data-href="/adminMenu">
                    <i data-acorn-icon="power" class="icon" data-acorn-size="18"></i>
                    <span class="label">{{__('menu_admin')}}</span>
                </a>
                <ul id="adminMenu">
                    @can('admin.list')
                    <li>
                        <a href="{{route('admin.list')}}">
                            <span class="label">{{__('menu_users')}}</span>
                        </a>
                    </li>
                    @endcan

                    @can('role.list')
                    <li>
                        <a href="{{route('admin.role.list')}}">
                            <span class="label">{{__('menu_roles')}}</span>
                        </a>
                    </li>
                    @endcan
                    @can('permission.list')
                    <li>
                        <a href="{{route('admin.permission.list')}}">
                            <span class="label">{{__('menu_permission_list')}}</span>
                        </a>
                    </li>
                    @endcan
                    @can('services.list')
                        <li>
                            <a href="{{route('admin.services.list')}}">
                                <span class="label">{{__('menu_services')}}</span>
                            </a>
                        </li>
                    @endcan
                    @can('statistics')
                        <li>
                            <a href="{{route('admin.statistics.info')}}">
                                <span class="label">{{__('menu_statistics')}}</span>
                            </a>
                        </li>
                    @endcan
                    @can('messages')
                        <li>
                            <a href="{{route('admin.messages.list')}}">
                                <span class="label">{{__('menu_messages')}}</span>
                            </a>
                        </li>
                    @endcan
                    @can('settings')
                        <li>
                            <a href="{{route('admin.settings')}}">
                                <span class="label">{{__('menu_settings')}}</span>
                            </a>
                        </li>
                    @endcan
                    @can('stock.list')
                        <li>
                            <a href="{{route('admin.stock.list')}}">
                                <span class="label">{{__('menu_stock')}}</span>
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
            @endcan

            @can('patient.menu')
            <li>
                <a href="#ordersmenu" data-href="/ordersmenu" class="{{ isActiveRoute('admin/patient/*') }}">
                    <i data-acorn-icon="user" class="icon" data-acorn-size="18"></i>
                    <span class="label">{{__('menu_patients')}}</span>
                </a>
                <ul id="ordersmenu">
                    @can('patient.list')
                    <li>
                        <a href="{{route('admin.patient.list')}}">
                            <span class="label">{{__('menu_patient_list')}}</span>
                        </a>
                    </li>
                    @endcan
                    @can('patient.debtors')
                    <li>
                        <a href="{{route('admin.patient.debtors')}}">
                            <span class="label">{{__('menu_patient_debtors')}}</span>
                        </a>
                    </li>
                    @endcan
                    @can('patient.active_services')
                        <li>
                            <a href="{{route('admin.patient.activeServices')}}">
                                <span class="label">{{__('menu_patient_activeservices')}}</span>
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
            @endcan
            @can('partners.list')
                <li>
                    <a href="{{route('admin.partners.list')}}" class="{{ isActiveRoute('admin/partners/*') }}">
                        <i data-acorn-icon="user" class="icon" data-acorn-size="18"></i>
                        <span class="label">{{__('menu_partners')}}</span>
                    </a>
                </li>
            @endcan
            @can('crm')
                <li>
                    <a href="{{route('admin.crm.index')}}" class="{{ isActiveRoute('admin/crm/*') }}">
                        <i data-acorn-icon="category" class="icon" data-acorn-size="18"></i>
                        <span class="label">{{__('menu_crm')}}</span>
                    </a>
                </li>
            @endcan

            @can('cashier')
                <li>
                    <a href="{{route('admin.cashier.index')}}" class="{{ isActiveRoute('admin/cashier') }}">
                        <i data-acorn-icon="money" class="icon" data-acorn-size="18"></i>
                        <span class="label">{{__('menu_cashier')}}</span>
                    </a>
                </li>
            @endcan

            @can('reservations.list')
                <li>
                    <a href="{{route('admin.reservations.list')}}" class="{{ isActiveRoute('admin/reservations/*') }}">
                        <i data-acorn-icon="clock" class="icon" data-acorn-size="18"></i>
                        <span class="label">{{__('menu_reservations')}}</span>
                    </a>
                </li>
            @endcan

            @can('notes.list')
                <li>
                    <a href="{{route('admin.notes.list')}}" class="{{ isActiveRoute('admin/notes/*') }}">
                        <i data-acorn-icon="note" class="icon" data-acorn-size="18"></i>
                        <span class="label">{{__('menu_notes')}}</span>
                    </a>
                </li>
            @endcan

        </ul>
    </div>
    <!-- Menu End -->

    <!-- Mobile Buttons Start -->
    <div class="mobile-buttons-container">
        <!-- Scrollspy Mobile Button Start -->
        <a href="#" id="scrollSpyButton" class="spy-button" data-bs-toggle="dropdown">
            <i data-acorn-icon="menu-dropdown"></i>
        </a>
        <!-- Scrollspy Mobile Button End -->

        <!-- Scrollspy Mobile Dropdown Start -->
        <div class="dropdown-menu dropdown-menu-end" id="scrollSpyDropdown"></div>
        <!-- Scrollspy Mobile Dropdown End -->

        <!-- Menu Button Start -->
        <a href="#" id="mobileMenuButton" class="menu-button">
            <i data-acorn-icon="menu"></i>
        </a>
        <!-- Menu Button End -->
    </div>
    <!-- Mobile Buttons End -->
</div>
<div class="nav-shadow"></div>

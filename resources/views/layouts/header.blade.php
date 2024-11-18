<nav class="layout-navbar navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
    id="layout-navbar">
    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
            <i class="mdi mdi-menu mdi-24px"></i>
        </a>
    </div>

    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
        @if (Route::current()->getName() == 'dashboard')
            <!-- Welcome Text -->
            <div class="navbar-nav align-items-center">
                <div class="nav-item navbar-search-wrapper mb-0 mt-4">
                    <h3>Welcome {{ auth()->user()->first_name }} !</h3>
                </div>
            </div>
            <!-- /Welcome Text -->
        @endif
        <ul class="navbar-nav flex-row align-items-center ms-auto">
            <!-- User -->
            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <div class="avatar">
                        <img src="{{ asset('uploads/user-profile/'. Auth::user()->picture) }}" alt
                            class="w-px-40 h-auto rounded-circle" />
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="{{ route('dashboard') }}">
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar ">
                                        <img src="{{ asset('uploads/user-profile/'. Auth::user()->picture) }}"
                                            class="w-px-40 h-auto rounded-circle" />
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <span class="fw-medium d-block">{{ Auth::user()->first_name }}</span>
                                    <small class="text-muted">{{ Auth::user()->getrole->name ?? "" }}</small>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <div class="dropdown-divider"></div>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{route('profile.edit',auth()->user()->id)}}">
                            <i class="mdi mdi-account-outline me-2"></i>
                            <span class="align-middle">My Profile</span>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('profile.updatePassword') }}">
                            <i class="mdi mdi-key-outline me-2"></i>
                            <span class="align-middle">Change Password</span>
                        </a>
                    </li>
                    {{-- <li>
                        <a class="dropdown-item" href="pages-account-settings-billing.html">
                            <span class="d-flex align-items-center align-middle">
                                <i class="flex-shrink-0 mdi mdi-credit-card-outline me-2"></i>
                                <span class="flex-grow-1 align-middle">Billing</span>
                                <span
                                    class="flex-shrink-0 badge badge-center rounded-pill bg-danger w-px-20 h-px-20">4</span>
                            </span>
                        </a>
                    </li>
                    <li>
                        <div class="dropdown-divider"></div>
                    </li>
                    <li>
                        <a class="dropdown-item" href="pages-faq.html">
                            <i class="mdi mdi-help-circle-outline me-2"></i>
                            <span class="align-middle">FAQ</span>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="pages-pricing.html">
                            <i class="mdi mdi-currency-usd me-2"></i>
                            <span class="align-middle">Pricing</span>
                        </a>
                    </li> --}}
                    <li>
                        <div class="dropdown-divider"></div>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('logout') }}"
                            onclick="event.preventDefault();  document.getElementById('logout-form').submit();">
                            <i class="mdi mdi-logout me-2"></i>
                            <span class="align-middle">Log Out</span>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </a>

                        {{-- <a class="dropdown-item" href="auth-login-cover.html" target="_blank">
                            <i class="mdi mdi-logout me-2"></i>
                            <span class="align-middle">Log Out</span>
                        </a> --}}
                    </li>
                </ul>
            </li>
            <!--/ User -->
        </ul>
    </div>

    <!-- Search Small Screens -->
    <div class="navbar-search-wrapper search-input-wrapper d-none">
        <input type="text" class="form-control search-input container-xxl border-0" placeholder="Search..."
            aria-label="Search..." />
        <i class="mdi mdi-close search-toggler cursor-pointer"></i>
    </div>
</nav>

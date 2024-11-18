@php

    use App\Models\Role;
    use App\Models\Permission;
    use App\Models\User;

    $loggedInUser = Auth::user();

    $permissions = [];
    $roleId = User::where('id', Auth::user()->id)->value('role_id');
    $data = Permission::where('role_id', $roleId)->pluck('module', 'id')->toArray();
    $permissions = array_unique($data);

    $isSuperAdmin = 0;
    if ($loggedInUser->role_id == 1) {
        $isSuperAdmin = 1;
    }

    // dd($permissions);

@endphp

<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <div class="mdi mdi-close close-menu "></div>

        <a href="{{ route('dashboard') }}" class="app-brand-link">
            {{-- <span class="app-brand-logo demo">
        </span> --}}
            {{-- <img src="{{ asset('assets/img/branding/main-logo.png') }}" height="130" alt=""> --}}
            <h1><b>SBGVSSPPY</b></h1>
            {{-- <span class="app-brand-text demo menu-text fw-bold ms-2">Materialize</span> --}}
        </a>
        {{-- <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto close-menu-div">
            <div class="mdi mdi-close close-menu"></div>
        </a> --}}
        {{-- <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
        <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path
            d="M11.4854 4.88844C11.0081 4.41121 10.2344 4.41121 9.75715 4.88844L4.51028 10.1353C4.03297 10.6126 4.03297 11.3865 4.51028 11.8638L9.75715 17.1107C10.2344 17.5879 11.0081 17.5879 11.4854 17.1107C11.9626 16.6334 11.9626 15.8597 11.4854 15.3824L7.96672 11.8638C7.48942 11.3865 7.48942 10.6126 7.96672 10.1353L11.4854 6.61667C11.9626 6.13943 11.9626 5.36568 11.4854 4.88844Z"
            fill="currentColor"
            fill-opacity="0.6" />
          <path
            d="M15.8683 4.88844L10.6214 10.1353C10.1441 10.6126 10.1441 11.3865 10.6214 11.8638L15.8683 17.1107C16.3455 17.5879 17.1192 17.5879 17.5965 17.1107C18.0737 16.6334 18.0737 15.8597 17.5965 15.3824L14.0778 11.8638C13.6005 11.3865 13.6005 10.6126 14.0778 10.1353L17.5965 6.61667C18.0737 6.13943 18.0737 5.36568 17.5965 4.88844C17.1192 4.41121 16.3455 4.41121 15.8683 4.88844Z"
            fill="currentColor"
            fill-opacity="0.38" />
        </svg>
      </a> --}}
    </div>

    {{-- <div class="menu-inner-shadow"></div> --}}

    <ul class="menu-inner py-1">
        <li class="menu-item {{ Request::segment(1) === 'dashboard' || request()->is('/') ? 'active' : '' }}">
            <a href="{{ route('dashboard') }}" class="menu-link">
                <i class="menu-icon tf-icons mdi mdi-home-outline"></i>
                <div data-i18n="Dashboard">Dashboard</div>
            </a>
        </li>

        @if ($isSuperAdmin == 1 || in_array('House', $permissions))
            <li
                class="menu-item {{ in_array(Route::current()->getName(), ['house.index', 'house.create', 'house.edit', 'house.show']) ? 'active' : '' }}">
                <a href="{{ route('house.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-home-variant-outline"></i>
                    <div data-i18n="House">House</div>
                </a>
            </li>
        @endif

        @if ($isSuperAdmin == 1 || in_array('Member', $permissions))
            <li
                class="menu-item {{ in_array(Route::current()->getName(), ['member.index', 'member.create', 'member.edit', 'member.show']) ? 'active' : '' }}">
                <a href="{{ route('member.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-clipboard-account"></i>
                    <div data-i18n="Member">Member</div>
                </a>
            </li>
        @endif

        @if ($isSuperAdmin == 1 || in_array('Income', $permissions))
            <li
                class="menu-item {{ in_array(Route::current()->getName(), ['income.index', 'income.create', 'income.edit', 'income.show', 'income.receipt', 'income.donation']) ? 'active' : '' }}">
                <a href="{{ route('income.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-wallet"></i>
                    <div data-i18n="Income">Income</div>
                </a>
            </li>
        @endif

        @if ($isSuperAdmin == 1 || in_array('Expense', $permissions))
            <li
                class="menu-item {{ in_array(Route::current()->getName(), ['expense.index', 'expense.create', 'expense.edit', 'expense.show']) ? 'active' : '' }}">
                <a href="{{ route('expense.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-cash"></i>
                    <div data-i18n="Expense">Expense</div>
                </a>
            </li>
        @endif

        @if ($isSuperAdmin == 1 || in_array('Petty Cash', $permissions))
            <li
                class="menu-item {{ in_array(Route::current()->getName(), ['petty-cash.index', 'petty-cash.create', 'petty-cash.edit', 'petty-cash.show', 'petty-cash-log.create']) ? 'active' : '' }}">
                <a href="{{ route('petty-cash.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-account-outline"></i>
                    <div data-i18n="Petty Cash">Petty Cash</div>
                </a>
            </li>
        @endif

        {{-- @if ($isSuperAdmin == 1 || in_array('Expense Member', $permissions))
            <li
                class="menu-item {{ in_array(Route::current()->getName(), ['expense-member.index', 'expense-member.create', 'expense-member.edit', 'expense-member.show']) ? 'active' : '' }}">
                <a href="{{ route('expense-member.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-cash"></i>
                    <div data-i18n="Expense Member">Expense Member</div>
                </a>
            </li>
        @endif --}}

        @if ($isSuperAdmin == 1 || in_array('User', $permissions))
            <li
                class="menu-item {{ in_array(Route::current()->getName(), ['user.index', 'user.create', 'user.edit', 'user.show']) ? 'active' : '' }}">
                <a href="{{ route('user.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-account-outline"></i>
                    <div data-i18n="User">User</div>
                </a>
            </li>
        @endif

        @if ($isSuperAdmin == 1 || in_array('Bank', $permissions) || in_array('Bank Transaction', $permissions) /* || in_array('Activity Log', $permissions) */)

            <li
                class="menu-item {{ in_array(Route::current()->getName(), ['bank.index', 'bank.create', 'bank.edit', 'bank.show', 'bank-transaction.index', 'bank-transaction.create', 'bank-transaction.edit', 'bank-transaction.show', 'bank.balance-sheet']) ? 'open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons mdi mdi-cog-outline"></i>
                    <div data-i18n="Accounting">Accounting</div>
                </a>
                <ul class="menu-sub">

                    @if ($isSuperAdmin == 1 || in_array('Bank', $permissions))
                        <li
                            class="menu-item {{ in_array(Route::current()->getName(), ['bank.index', 'bank.create', 'bank.edit', 'bank.show']) ? 'active' : '' }}">
                            <a href="{{ route('bank.index') }}" class="menu-link">
                                <div data-i18n="Bank">Bank</div>
                            </a>
                        </li>
                    @endif

                    @if ($isSuperAdmin == 1 || in_array('Bank Transaction', $permissions))
                        <li
                            class="menu-item {{ in_array(Route::current()->getName(), ['bank-transaction.index', 'bank-transaction.create', 'bank-transaction.edit', 'bank-transaction.show']) ? 'active' : '' }}">
                            <a href="{{ route('bank-transaction.index') }}" class="menu-link">
                                <div data-i18n="Bank Transaction">Bank Transaction</div>
                            </a>
                        </li>
                    @endif

                    {{-- @if ($isSuperAdmin == 1 || in_array('Bank', $permissions))
                        <li
                            class="menu-item {{ in_array(Route::current()->getName(), ['bank.balance-sheet']) ? 'active' : '' }}">
                            <a href="{{ route('bank.balance-sheet') }}" class="menu-link">
                                <div data-i18n="Balance Sheet">Balance Sheet</div>
                            </a>
                        </li>
                    @endif --}}

                    {{-- @if ($isSuperAdmin == 1 || in_array('Activity Log', $permissions))
                        <li
                            class="menu-item {{ in_array(Route::current()->getName(), ['activity-log.index']) ? 'active' : '' }}">
                            <a href="{{ route('activity-log.index') }}" class="menu-link">
                                <div data-i18n="Activity Log">Activity Log</div>
                            </a>
                        </li>
                    @endif --}}
                </ul>
            </li>
        @endif

        <li
            class="menu-item {{ in_array(Route::current()->getName(), ['report.change-house-owner', 'report.donation-report', 'report.expense-report', 'report.income-report', 'report.balance-sheet-report', 'report.petty-cash-report']) ? 'open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons mdi mdi-file-document-outline"></i>
                <div data-i18n="Reports">Reports</div>
            </a>
            <ul class="menu-sub">
                <li
                    class="menu-item {{ in_array(Route::current()->getName(), ['report.change-house-owner']) ? 'active' : '' }}">
                    <a href="{{ route('report.change-house-owner') }}" class="menu-link">
                        <div data-i18n="Change House Owner">Change House Owner</div>
                    </a>
                </li>
                {{-- <li
                    class="menu-item {{ in_array(Route::current()->getName(), ['report.donation-report']) ? 'active' : '' }}">
                    <a href="{{ route('report.donation-report') }}" class="menu-link">
                        <div data-i18n="Donation Report">Donation Report</div>
                    </a>
                </li> --}}
                <li
                    class="menu-item {{ in_array(Route::current()->getName(), ['report.expense-report']) ? 'active' : '' }}">
                    <a href="{{ route('report.expense-report') }}" class="menu-link">
                        <div data-i18n="Expense Report">Expense Report</div>
                    </a>
                </li>
                <li
                    class="menu-item {{ in_array(Route::current()->getName(), ['report.income-report']) ? 'active' : '' }}">
                    <a href="{{ route('report.income-report') }}" class="menu-link">
                        <div data-i18n="Income Report">Income Report</div>
                    </a>
                </li>
                <li
                    class="menu-item {{ in_array(Route::current()->getName(), ['report.balance-sheet-report']) ? 'active' : '' }}">
                    <a href="{{ route('report.balance-sheet-report') }}" class="menu-link">
                        <div data-i18n="Balance Sheet Report">Balance Sheet Report</div>
                    </a>
                </li>
                <li
                    class="menu-item {{ in_array(Route::current()->getName(), ['report.petty-cash-report']) ? 'active' : '' }}">
                    <a href="{{ route('report.petty-cash-report') }}" class="menu-link">
                        <div data-i18n="Petty Cash Report">Petty Cash Report</div>
                    </a>
                </li>
            </ul>
        </li>

        @if (
            $isSuperAdmin == 1 ||
                in_array('Account', $permissions) ||
                in_array('Income Category', $permissions) ||
                in_array('Expense Category', $permissions) ||
                in_array('Bank', $permissions))
            <li
                class="menu-item {{ in_array(Route::current()->getName(), ['expense-member.index', 'expense-member.create', 'expense-member.edit', 'expense-member.show', 'accounts.index', 'accounts.create', 'accounts.edit', 'income-category.index', 'income-category.create', 'income-category.edit', 'expense-category.index', 'expense-category.create', 'expense-category.edit'/* , 'bank.index', 'bank.create', 'bank.edit', 'bank.show' */]) ? 'open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons mdi mdi-account-cog-outline"></i>
                    <div data-i18n="Masters">Masters</div>
                </a>
                <ul class="menu-sub">
                    @if ($isSuperAdmin == 1 || in_array('Expense Member', $permissions))
                        <li
                            class="menu-item {{ in_array(Route::current()->getName(), ['expense-member.index', 'expense-member.create', 'expense-member.edit', 'expense-member.show']) ? 'active' : '' }}">
                            <a href="{{ route('expense-member.index') }}" class="menu-link">
                                <div data-i18n="Expense Member">Expense Member</div>
                            </a>
                        </li>
                    @endif

                    @if ($isSuperAdmin == 1 || in_array('Account', $permissions))
                        <li
                            class="menu-item {{ in_array(Route::current()->getName(), ['accounts.index', 'accounts.create', 'accounts.edit']) ? 'active' : '' }}">
                            <a href="{{ route('accounts.index') }}" class="menu-link">
                                <div data-i18n="Account">Account</div>
                            </a>
                        </li>
                    @endif

                    @if ($isSuperAdmin == 1 || in_array('Income Category', $permissions))
                        <li
                            class="menu-item {{ in_array(Route::current()->getName(), ['income-category.index', 'income-category.create', 'income-category.edit']) ? 'active' : '' }}">
                            <a href="{{ route('income-category.index') }}" class="menu-link">
                                <div data-i18n="Income Category">Income Category</div>
                            </a>
                        </li>
                    @endif

                    @if ($isSuperAdmin == 1 || in_array('Expense Category', $permissions))
                        <li
                            class="menu-item {{ in_array(Route::current()->getName(), ['expense-category.index', 'expense-category.create', 'expense-category.edit']) ? 'active' : '' }}">
                            <a href="{{ route('expense-category.index') }}" class="menu-link">
                                <div data-i18n="Expense Category">Expense Category</div>
                            </a>
                        </li>
                    @endif

                    {{-- @if ($isSuperAdmin == 1 || in_array('Bank', $permissions))
                        <li
                            class="menu-item {{ in_array(Route::current()->getName(), ['bank.index', 'bank.create', 'bank.edit', 'bank.show']) ? 'active' : '' }}">
                            <a href="{{ route('bank.index') }}" class="menu-link">
                                <div data-i18n="Bank">Bank</div>
                            </a>
                        </li>
                    @endif --}}
                </ul>
            </li>
        @endif

        @if ($isSuperAdmin == 1 || in_array('Role', $permissions) || in_array('Activity Log', $permissions))

            <li
                class="menu-item {{ in_array(Route::current()->getName(), ['roles.index', 'roles.create', 'roles.edit', 'roles.show', 'activity-log.index']) ? 'open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons mdi mdi-cog-outline"></i>
                    <div data-i18n="Configuration">Configuration</div>
                </a>
                <ul class="menu-sub">
                    @if ($isSuperAdmin == 1 || in_array('Role', $permissions))
                        <li
                            class="menu-item {{ in_array(Route::current()->getName(), ['roles.index', 'roles.create', 'roles.show', 'roles.edit']) ? 'active' : '' }}">
                            <a href="{{ route('roles.index') }}" class="menu-link">
                                <div data-i18n="Role">Role</div>
                            </a>
                        </li>
                    @endif

                    @if ($isSuperAdmin == 1 || in_array('Activity Log', $permissions))
                        <li
                            class="menu-item {{ in_array(Route::current()->getName(), ['activity-log.index']) ? 'active' : '' }}">
                            <a href="{{ route('activity-log.index') }}" class="menu-link">
                                <div data-i18n="Activity Log">Activity Log</div>
                            </a>
                        </li>
                    @endif
                </ul>
            </li>
        @endif

        <li class="menu-item">
            <a href="{{ route('logout') }}" class="menu-link"
                onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                <i class="menu-icon tf-icons mdi mdi-logout"></i>
                <div data-i18n="Logout">Logout</div>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </a>
        </li>
    </ul>
</aside>

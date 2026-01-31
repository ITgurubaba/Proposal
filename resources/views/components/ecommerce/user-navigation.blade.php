<div>
    <ul class="nav myaccount-tab-trigger" wire:ignore>
        <li class="nav-item">
            <a class="nav-link {{ $currentTab == "dashboard"?'active':'' }}"
               href="{{ route('frontend::account:dashboard') }}"
            >Dashboard</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $currentTab == "orders"?'active':'' }}"
               href="{{ route('frontend::account:orders') }}"
            >Orders</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $currentTab == "address"?'active':'' }}"
               href="{{ route('frontend::account:address') }}"
            >Addresses</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $currentTab == "profile"?'active':'' }}"
               href="{{ route('frontend::account:profile') }}"
            >Account Details</a>
        </li>
        <li class="nav-item">
            <a class="nav-link"
               href="{{ Theme::LINK_NONE }}"
               onclick="processLogout()"
            >Logout</a>
        </li>
    </ul>
</div>

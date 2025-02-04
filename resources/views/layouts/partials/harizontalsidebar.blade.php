@php
$user        = Auth::user();
$displayName = $user->display_name;
$client      = \App\Admin\Clients\Models\Client::with('modules')->find($user->client_id);
$companyLogo = $client->company_logo;
$modules     = $client ? $client->modules : collect();
$modules     = json_decode(json_encode($modules), true);
// dd($modules);

// $modulesList = [];
// foreach ($modules as $module) {
//     array_push($modulesList, $module['slug']);
// }

usort($modules, function ($a, $b) {
    return $a['order'] <=> $b['order']; // Ascending order
});
// dd($modules);
@endphp

<aside class="left-sidebar with-horizontal">
    <!-- Sidebar scroll-->
    <div>
        <!-- Sidebar navigation-->
        <nav id="sidebarnavh" class="sidebar-nav scroll-sidebar container-fluid">
            <ul id="sidebarnav">
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">Home</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" aria-expanded="false" href="{{ url('bsadmin/dashboard') }}">
                        <span>
                            {{-- <i class="ti ti-aperture"></i> --}}
                            <i class="fa-solid fa-house"></i>
                        </span>
                        <span class="hide-menu">Dashboard</span>
                    </a>
                </li>

                @foreach ($modules as $module)
                    @if (auth()->user()->can($module['slug'] . '.view') || auth()->user()->hasRole('superadmin'))
                        <li class="sidebar-item">
                            <a class="sidebar-link" aria-expanded="false" href="{{ url('bsadmin/' . $module['url']) }}">
                                <span>
                                    {{-- <i class="ti ti-aperture"></i> --}}
                                    <i class="{{ $module['icon'] }}"></i>
                                </span>
                                <span class="hide-menu">{{ $module['name'] }}</span>
                            </a>
                        </li>
                    @endif
                @endforeach
            </ul>
        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
</aside>

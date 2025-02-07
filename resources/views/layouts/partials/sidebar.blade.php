 @php
     $user        = Auth::user();
     $displayName = $user->display_name;
     $client      = \App\Admin\Clients\Models\Client::with('modules')->find($user->client_id);
     $companyLogo = $client->company_logo;
     $name        = $client->company_name;
     $modules     = $client ? $client->modules : collect();

     $modules = json_decode(json_encode($modules), true);
     usort($modules, function ($a, $b) {
         return $a['order'] <=> $b['order']; 
     });

 @endphp
 <!-- Sidebar Start -->
 <aside class="left-sidebar with-vertical">
     <div><!-- ---------------------------------- -->
         <!-- Start Vertical Layout Sidebar -->
         <!-- ---------------------------------- -->
         <div class="brand-logo d-flex align-items-center justify-content-between">
             <a href="{{ url('bsadmin/dashboard') }}" class="text-nowrap logo-img">
                 <img src="{{ $companyLogo }}" class="dark-logo logo" alt="Logo-Dark" />
                 <img src="{{ $companyLogo }}" class="light-logo logo" alt="Logo-light" />
                 
             </a>
             <a href="javascript:void(0)" class="sidebartoggler ms-auto text-decoration-none fs-5 d-block d-xl-none">
                 <i class="ti ti-x"></i>
             </a>
         </div>

         <nav class="sidebar-nav scroll-sidebar" data-simplebar>
             <ul id="sidebarnav">

                 {{-- <li class="nav-small-cap">
                     <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                     <span class="hide-menu">Home</span>
                 </li> --}}
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
                             <a class="sidebar-link" aria-expanded="false"
                                 href="{{ url('bsadmin/' . $module['url']) }}">
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

         <div class="fixed-profile p-3 mx-4 mb-2 bg-secondary-subtle rounded mt-3">
             <div class="hstack gap-3">
                 <div class="john-img">
                     <img src="../assets/images/profile/user-1.jpg" class="rounded-circle" width="40" height="40"
                         alt="modernize-img" />
                 </div>
                 <div class="john-title">
                     <h6 class="mb-0 fs-4 fw-semibold">{{ $displayName }}</h6>
                     {{-- <span class="fs-2">Developer</span> --}}
                 </div>
                 {{-- <button class="border-0 bg-transparent text-primary ms-auto" tabindex="0" type="button"
                     aria-label="logout" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="logout">
                     
                     <i class="ti ti-power fs-6"></i>
                 </button> --}}
             </div>
         </div>
     </div>
 </aside>
 <!--  Sidebar End -->

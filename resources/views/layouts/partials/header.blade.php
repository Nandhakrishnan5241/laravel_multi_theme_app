@php
    $user = Auth::user();
    $displayName = $user->display_name;
    $email = $user->email;
    $client = \App\Admin\Clients\Models\Client::with('modules')->find($user->client_id);
    $companyLogo = $client->company_logo;
@endphp
<style>
    label.error {
        color: #dc3545;
        font-size: 14px;
    }

    .validation,
    .validation:focus {
        color: red !important;
        border-color: red !important;
    }

    .validation::placeholder {
        color: red !important;
        opacity: 1 !important;
    }
</style>
<!--  Header Start -->
<header class="topbar">
    <div class="with-vertical"><!-- ---------------------------------- -->
        <!-- Start Vertical Layout Header -->
        <!-- ---------------------------------- -->
        <nav class="navbar navbar-expand-lg p-0">
            <ul class="navbar-nav">
                <li class="nav-item nav-icon-hover-bg rounded-circle ms-n2">
                    <a class="nav-link sidebartoggler" id="headerCollapse" href="javascript:void(0)">
                        <i class="ti ti-menu-2"></i>
                    </a>
                </li>
            </ul>



            <div class="d-block d-lg-none py-4">
                <a href="../main/index.html" class="text-nowrap logo-img">
                  <img src="{{ $companyLogo }}" class="dark-logo logo" alt="Logo-Dark" />
                  <img src="{{ $companyLogo }}" class="light-logo logo" alt="Logo-light" />
                </a>
            </div>
            <a class="navbar-toggler nav-icon-hover-bg rounded-circle p-0 mx-0 border-0" href="javascript:void(0)"
                data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false"
                aria-label="Toggle navigation">
                <i class="ti ti-dots fs-7"></i>
            </a>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <div class="d-flex align-items-center justify-content-between">
                    <a href="javascript:void(0)"
                        class="nav-link nav-icon-hover-bg rounded-circle mx-0 ms-n1 d-flex d-lg-none align-items-center justify-content-center"
                        type="button" data-bs-toggle="offcanvas" data-bs-target="#mobilenavbar"
                        aria-controls="offcanvasWithBothOptions">
                        <i class="ti ti-align-justified fs-7"></i>
                    </a>
                    <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-center">

                        <li class="nav-item nav-icon-hover-bg rounded-circle">
                            <a class="nav-link moon dark-layout" href="javascript:void(0)">
                                <i class="ti ti-moon moon"></i>
                            </a>
                            <a class="nav-link sun light-layout" href="javascript:void(0)">
                                <i class="ti ti-sun sun"></i>
                            </a>
                        </li>
                        <!-- ------------------------------- -->
                        <!-- start profile Dropdown -->
                        <!-- ------------------------------- -->
                        <li class="nav-item dropdown">
                            <a class="nav-link pe-0" href="javascript:void(0)" id="drop1" aria-expanded="false">
                                <div class="d-flex align-items-center">
                                    <div class="user-profile-img">
                                        <img src="../assets/images/profile/user-1.jpg" class="rounded-circle"
                                            width="35" height="35" alt="modernize-img" />
                                    </div>
                                </div>
                            </a>
                            <div class="dropdown-menu content-dd dropdown-menu-end dropdown-menu-animate-up"
                                aria-labelledby="drop1">
                                <div class="profile-dropdown position-relative" data-simplebar>
                                    <div class="py-3 px-7 pb-0">
                                        <h5 class="mb-0 fs-5 fw-semibold">User Profile</h5>
                                    </div>
                                    <div class="d-flex align-items-center py-9 mx-7 border-bottom">
                                        <img src="../assets/images/profile/user-1.jpg" class="rounded-circle"
                                            width="80" height="80" alt="modernize-img" />
                                        <div class="ms-3">
                                            <h5 class="mb-1 fs-3">{{ $displayName }}</h5>
                                            {{-- <span class="mb-1 d-block">Designer</span> --}}
                                            <p class="mb-0 d-flex align-items-center gap-2">
                                                <i class="ti ti-mail fs-4"></i> {{ $email }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="message-body">
                                        <div class="py-8 px-7 mt-8 d-flex align-items-center">
                                            <span
                                                class="d-flex align-items-center justify-content-center text-bg-light rounded-1 p-6">
                                                <img src="../assets/images/svgs/icon-account.svg" alt="modernize-img"
                                                    width="24" height="24" />
                                            </span>
                                            <div class="w-100 ps-3 ">
                                                <h6 class="mb-1 fs-3 fw-semibold lh-base cursor-pointer"
                                                    data-bs-toggle="modal" data-bs-target="#changePasswordModal">Change
                                                    Password</h6>
                                            </div>
                                        </div>
                                        <a href="{{ route('bsadmin.logout') }}"
                                            class="py-8 px-7 d-flex align-items-center"
                                            onclick="event.preventDefault(); document.getElementById('logout-form-h').submit();">
                                            <span
                                                class="d-flex align-items-center justify-content-center text-bg-light rounded-1 p-6">
                                                <img src="../assets/images/svgs/icon-inbox.svg" alt="Logout"
                                                    width="24" height="24" />
                                            </span>
                                            <div class="w-100 ps-3">
                                                <h6 class="mb-1 fs-3 fw-semibold lh-base">Logout</h6>
                                                <span class="fs-2 d-block text-body-secondary">Sign out of your
                                                    account</span>
                                            </div>
                                        </a>

                                        <form id="logout-form-h" method="POST" action="{{ route('bsadmin.logout') }}"
                                            class="d-none">
                                            @csrf
                                        </form>
                                    </div>


                                </div>
                            </div>
                        </li>

                        <!-- ------------------------------- -->
                        <!-- end profile Dropdown -->
                        <!-- ------------------------------- -->
                    </ul>
                </div>
            </div>
        </nav>


    </div>
    <div class="app-header with-horizontal">
        <nav class="navbar navbar-expand-xl container-fluid p-0">
            <ul class="navbar-nav align-items-center">
                <li class="nav-item nav-icon-hover-bg rounded-circle d-flex d-xl-none ms-n2">
                    <a class="nav-link sidebartoggler" id="sidebarCollapse" href="javascript:void(0)">
                        <i class="ti ti-menu-2"></i>
                    </a>
                </li>
                <li class="nav-item d-none d-xl-block">
                    <a href="../main/index.html" class="text-nowrap nav-link">
                      <img src="{{ $companyLogo }}" class="dark-logo logo" alt="Logo-Dark" />
                      <img src="{{ $companyLogo }}" class="light-logo logo" alt="Logo-light" />
                    </a>
                </li>
            </ul>

            <div class="d-block d-xl-none">
                <a href="../main/index.html" class="text-nowrap nav-link">
                    <img src="../assets/images/logos/dark-logo.svg" width="180" alt="modernize-img" />
                </a>
            </div>
            <a class="navbar-toggler nav-icon-hover-bg rounded-circle p-0 mx-0 border-0" href="javascript:void(0)"
                data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav"
                aria-expanded="false" aria-label="Toggle navigation">
                <span class="p-2">
                    <i class="ti ti-dots fs-7"></i>
                </span>
            </a>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <div class="d-flex align-items-center justify-content-between px-0 px-xl-8">
                    <a href="javascript:void(0)"
                        class="nav-link round-40 p-1 ps-0 d-flex d-xl-none align-items-center justify-content-center"
                        type="button" data-bs-toggle="offcanvas" data-bs-target="#mobilenavbar"
                        aria-controls="offcanvasWithBothOptions">
                        <i class="ti ti-align-justified fs-7"></i>
                    </a>
                    <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-center">
                        <!-- ------------------------------- -->
                        <!-- start language Dropdown -->
                        <!-- ------------------------------- -->
                        <li class="nav-item nav-icon-hover-bg rounded-circle">
                            <a class="nav-link moon dark-layout" href="javascript:void(0)">
                                <i class="ti ti-moon moon"></i>
                            </a>
                            <a class="nav-link sun light-layout" href="javascript:void(0)">
                                <i class="ti ti-sun sun"></i>
                            </a>
                        </li>

                        <!-- ------------------------------- -->
                        <!-- start profile Dropdown -->
                        <!-- ------------------------------- -->
                        <li class="nav-item dropdown">
                            <a class="nav-link pe-0" href="javascript:void(0)" id="drop1" aria-expanded="false">
                                <div class="d-flex align-items-center">
                                    <div class="user-profile-img">
                                        <img src="../assets/images/profile/user-1.jpg" class="rounded-circle"
                                            width="35" height="35" alt="modernize-img" />
                                    </div>
                                </div>
                            </a>
                            <div class="dropdown-menu content-dd dropdown-menu-end dropdown-menu-animate-up"
                                aria-labelledby="drop1">
                                <div class="profile-dropdown position-relative" data-simplebar>
                                    <div class="py-3 px-7 pb-0">
                                        <h5 class="mb-0 fs-5 fw-semibold">User Profile</h5>
                                    </div>
                                    <div class="d-flex align-items-center py-9 mx-7 border-bottom">
                                        <img src="../assets/images/profile/user-1.jpg" class="rounded-circle"
                                            width="80" height="80" alt="modernize-img" />
                                        <div class="ms-3">
                                            <h5 class="mb-1 fs-3">{{ $displayName }}</h5>
                                            {{-- <span class="mb-1 d-block">Designer</span> --}}
                                            <p class="mb-0 d-flex align-items-center gap-2">
                                                <i class="ti ti-mail fs-4"></i> {{ $email }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="message-body">
                                        <div class="py-8 px-7 mt-8 d-flex align-items-center">
                                            <span
                                                class="d-flex align-items-center justify-content-center text-bg-light rounded-1 p-6">
                                                <img src="../assets/images/svgs/icon-account.svg" alt="modernize-img"
                                                    width="24" height="24" />
                                            </span>
                                            <div class="w-100 ps-3 ">
                                                <h6 class="mb-1 fs-3 fw-semibold lh-base cursor-pointer"
                                                    data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                                                    Change Password</h6>
                                            </div>
                                        </div>
                                        <a href="{{ route('bsadmin.logout') }}"
                                            class="py-8 px-7 d-flex align-items-center"
                                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            <span
                                                class="d-flex align-items-center justify-content-center text-bg-light rounded-1 p-6">
                                                <img src="../assets/images/svgs/icon-inbox.svg" alt="Logout"
                                                    width="24" height="24" />
                                            </span>
                                            <div class="w-100 ps-3">
                                                <h6 class="mb-1 fs-3 fw-semibold lh-base">Logout</h6>
                                                <span class="fs-2 d-block text-body-secondary">Sign out of your
                                                    account</span>
                                            </div>
                                        </a>

                                        <form id="logout-form" method="POST" action="{{ route('bsadmin.logout') }}"
                                            class="d-none">
                                            @csrf
                                        </form>
                                    </div>

                                </div>
                            </div>
                        </li>
                        <!-- ------------------------------- -->
                        <!-- end profile Dropdown -->
                        <!-- ------------------------------- -->
                    </ul>
                </div>
            </div>
        </nav>
    </div>
</header>
<!--  Header End -->

<!-- Change Password Modal -->
<div class="modal fade " id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="changePasswordModalLabel">Change Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="changePasswordForm" action="{{ route('changepassword') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="password">Old Password</label>
                        <input class="form-control" name="password" type="password" placeholder="enter the password"
                            id="password" />

                    </div>
                    <div class="mb-3">
                        <label for="newpassword">New Password</label>
                        <input class="form-control" name="newpassword" type="password" id="newpassword"
                            placeholder="enter the new password" />

                    </div>
                    <div class="mb-3">
                        <label for="confirmpassword">Confirm Password</label>
                        <input class="form-control" name="confirmpassword" type="password" id="confirmpassword"
                            placeholder="enter the confirm password" />

                    </div>

                    {{-- <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> --}}
                    <button type="submit" class="btn btn-primary">Save changes</button>
                    {{-- </div> --}}

                </form>
            </div>

        </div>
    </div>
</div>

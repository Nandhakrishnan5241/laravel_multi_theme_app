@extends('layouts.partials.admin')

@section('title', 'Dashboard')
@section('content')
<div class="container-fluid">
    <!--  Owl carousel -->
    <div class="owl-carousel counter-carousel owl-theme">
      @foreach ($modules as $module)
        @if (auth()->user()->can($module['slug'] . '.view') || auth()->user()->hasRole('superadmin'))
        <div class="item">
          <div class="card border-0 zoom-in bg-success-subtle shadow-none">
            <div class="card-body">
              <div class="text-center">
                <img src="../assets/images/svgs/icon-speech-bubble.svg" width="50" height="50" class="mb-3" alt="modernize-img" />
                <a href="{{ url('bsadmin/' . $module['url']) }}">
                  <b><p class="fw-semibold fs-3 text-dark mb-1"></b>
                    {{$module['name']}}
                  </p>
                </a>
                {{-- <h5 class="fw-semibold text-primary mb-0">96</h5> --}}
              </div>
            </div>
          </div>
        </div>
        @endif
      @endforeach
    </div>
    <!--  Row 1 -->
   
  </div>
  {{-- <script src="../assets/libs/apexcharts/dist/apexcharts.min.js"></script> --}}
  {{-- <script src="../assets/js/dashboards/dashboard.js"></script> --}}
  <script>
    
    // Carousel
    document.addEventListener("DOMContentLoaded", function () {
      $(".counter-carousel").owlCarousel({
        loop: true,
        rtl: true,
        margin: 30,
        mouseDrag: true,
    
        nav: false,
    
        responsive: {
          0: {
            items: 2,
            loop: true,
          },
          576: {
            items: 2,
            loop: true,
          },
          768: {
            items: 3,
            loop: true,
          },
          1200: {
            items: 5,
            loop: true,
          },
          1400: {
            items: 6,
            loop: true,
          },
        },
      });
    });
  </script>
@endsection
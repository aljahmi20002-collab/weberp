@include('backend.partials.header')
<div class="main-loader">
    <!-- Overlayer -->
    <div class="overlayer"></div>
    <div class="loader"></div>
    <!-- Overlayer --> 
</div>
 <main class="user-panel">
     @include('backend.partials.sideber')
     @include('backend.partials.theam-customize')
     <article class="user-panel-body">
         <div class="header-top-wrapper">
             @include('backend.partials.navber')
             <div class="cog-btn">
                 <i class="fas fa-cog"></i>
             </div> 
             @yield('breadcrumb')
         </div>
         @yield('maincontent')
      @include('backend.partials.dynamic_modal')
      @include('sweetalert::alert')
      @include('backend.partials.footer_text')
      @include('backend.partials.footer')

     </article>
 </main>

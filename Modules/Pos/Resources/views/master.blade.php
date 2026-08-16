@include('backend.partials.header')
<div class="main-loader">
  <!-- Overlayer -->
  <div class="overlayer"></div>
  <div class="loader"></div>
  <!-- Overlayer -->
</div>
<!-- User Panel -->

 <main class="user-panel pos-user-panel"> 
     <article class="user-panel-body"> 
      @yield('maincontent')
      @include('backend.partials.dynamic_modal')
      @include('sweetalert::alert') 
    </article>
</main>
@include('backend.partials.footer') 

@include('backend.partials.header')

<!-- Overlayer -->
<div class="overlayer"></div>
<div class="loader"></div>
<!-- Overlayer -->
<!-- User Panel -->

 <main class="user-panel pos-user-panel"> 
     <article class="user-panel-body"> 
      @yield('maincontent')
      @include('backend.partials.dynamic_modal')
      @include('sweetalert::alert') 
      @include('backend.partials.footer') 
     </article>
 </main>


    @include('sweetalert::alert')
    {{-- table data not availble image --}}
    <div id="data-not-available" data-image="{{ settings('table_empty_image') }}"></div>
    {{-- sound effect --}}
    <audio id="success-audio">
        <source src="{{ static_asset('/backend/audio/success.ogg') }}" type="audio/ogg">
        <source src="{{ static_asset('/backend/audio/success.mp3') }}" type="audio/mpeg">
    </audio>
    <audio id="error-audio">
        <source src="{{ static_asset('/backend/audio/error.ogg') }}" type="audio/ogg">
        <source src="{{ static_asset('/backend/audio/error.mp3') }}" type="audio/mpeg">
    </audio>
    <audio id="warning-audio">
        <source src="{{ static_asset('/backend/audio/warning.ogg') }}" type="audio/ogg">
        <source src="{{ static_asset('/backend/audio/warning.mp3') }}" type="audio/mpeg">
    </audio>
    {{-- end sound effect --}}
    
    <!-- User Panel -->
    <script src="{{static_asset('backend/assets')}}/js/jquery-3.6.0.min.js"></script>
    <script src="{{static_asset('backend/assets')}}/js/jquery-ui.min.js"></script>
    <script src="{{static_asset('backend/assets')}}/js/bootstrap.bundle.min.js"></script>   
    <script src="{{static_asset('backend/assets')}}/js/viewport.jquery.js"></script>
    <script src="{{static_asset('backend/assets')}}/js/wow.min.js"></script>
    <script src="{{static_asset('backend/assets')}}/js/sweetalert2.min.js"></script>
    <script src="{{static_asset('backend/assets')}}/js/sweet-alerts.init.js"></script>
    <script src="{{static_asset('backend/assets')}}/js/dropzone.min.js"></script>
    <script src="{{static_asset('backend/assets')}}/js/main.js"></script>
    <script src="{{static_asset('backend')}}/js/dynamic_modal.js"></script>  
    
    <script src="{{static_asset('backend')}}/js/custom.js"></script>
    <script src="{{static_asset('backend/assets')}}/js/apexcharts.min.js"></script>
    <script  src="{{static_asset('backend/js')}}/select2/select2.min.js"></script>
    <script type="text/javascript" src="{{static_asset('backend/js')}}/moment.min.js"></script>  
  
    <!-- Datatable export scripts -->
    <script src="{{ static_asset('backend/vendor/datatable') }}/jquery.dataTables.min.js"></script> 
    <script type="text/javascript" language="javascript" src="{{ static_asset('backend/vendor/datatable') }}/dataTables.buttons.min.js"></script>
    <script type="text/javascript" src="{{ static_asset('backend/vendor/datatable') }}/dataTables.bootstrap.min.js"></script>
    <script type="text/javascript" src="{{ static_asset('backend/vendor/datatable') }}/jszip.min.js"></script>
    <script type="text/javascript" src="{{ static_asset('backend/vendor/datatable') }}/buttons.html5.min.js"></script>
    <script type="text/javascript" src="{{ static_asset('backend/vendor/datatable') }}/buttons.print.min.js"></script>
    <script type="text/javascript" src="{{ static_asset('backend/vendor/datatable') }}/buttons.colVis.min.js"></script>
    <script type="text/javascript" src="{{ static_asset('backend/vendor/datatable') }}/pdfmake.min.js"></script>
    <script type="text/javascript" src="{{ static_asset('backend/vendor/datatable') }}/vfs_fonts.js"></script> 


    <script type="text/javascript" src="{{ static_asset('backend/vendor/toastr') }}/toastr.min.js"></script>  
 
    @stack('search-input')
    <!-- End Datatable export scripts --> 
    <script src="{{ static_asset('backend/vendor/summernote') }}/summernote-lite.min.js"></script>
    {{-- only div print --}}
    <script src="{{ static_asset('backend') }}/jquery.print.min.js"></script>
    <script src="{{ static_asset('backend/js') }}/print.js"></script> 
    {{-- end only div print --}}
 
    <!-- Chart Two End -->
    <script type="text/javascript">
            $(document).ready(function(){  

                toastr.options = {
                                    "closeButton" : true,
                                    "debug"       : false,
                                    "newestOnTop" : false,
                                    "progressBar" : true,
                                    "positionClass": "toast-bottom-right",
                                    "preventDuplicates":false,
                                    "onclick"     : null,
                                    "showDuration":"300",
                                    "hideDuration": "1000",
                                    "timeOut"     : "5000",
                                    "extendedTimeOut": "1000",
                                    "showEasing"  : "swing",
                                    "hideEasing"  :"linear",
                                    "showMethod"  : "fadeIn",
                                    "hideMethod"  : "fadeOut"
                                }
 
                $(".toggle-mode, .toggle-mode-btn").on("click", function () {
                    $(this).parent().siblings().removeClass("active");
                    $(this).parent().addClass("active");
                    if ($("html").hasClass("check-theme")) {
                        localStorage.setItem("color-theme", true);
                        localStorage.setItem('mode','light');
                        $(".toggle-mode").html('<i class="fas fa-sun"></i>');
                    } else {
                        localStorage.setItem('mode','night');
                        localStorage.removeItem("color-theme");
                        $(".toggle-mode").html('<i class="fas fa-moon"></i>');
                    }
                    setVersion();
                    console.log(localStorage.getItem('color-theme'));
                });
 
                if(localStorage.getItem("color-theme") == null && localStorage.getItem('mode') == null){
                    if("{{ settings('default_display_mode') }}" == 'night'){
                        localStorage.removeItem("color-theme");
                        $("html").removeClass("check-theme");
                		$("html").removeClass("dark-theme");
                        $(".toggle-mode").html('<i class="fas fa-sun"></i>');

                    }else if("{{ settings('default_display_mode') }}" == 'light'){

                        localStorage.setItem("color-theme", true);
                        $("html").addClass("check-theme");
                        $(".toggle-mode").html('<i class="fas fa-moon"></i>');

                    } 
                }
 
                setVersion();
                function setVersion() {
                	if (localStorage.getItem("color-theme")) {
                		$("html").removeClass("check-theme");
                		$("html").removeClass("dark-theme");
                		$(".toggle-mode").html('<i class="fas fa-moon"></i>');
                	} else {

                        $("html").addClass("check-theme");
                        $("html").addClass("dark-theme");
                        $(".toggle-mode").html('<i class="fas fa-sun"></i>');

                	}
                }  
            }); 
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
          
        </script>   
    @stack('scripts')
    <script type="text/javascript">
        $(document).ready(function(){ 
            $('input[type="search"]').addClass('form-control form--control'); 
           
        });
    </script>
         {!! Toastr::message() !!}
</body> 
</html>

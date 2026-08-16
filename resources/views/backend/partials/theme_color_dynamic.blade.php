<style> 
    :root {
        --white-clr: #ffffff; 
        --base-clr: {{ settings('theme_background_color') }}!important; 
        --primary-clr: {{ settings('theme_background_color') }}!important;
    } 
    .dark-theme {
        --base-clr: "{{ settings('theme_background_color') }}!important"; 
        --primary-clr: "{{ settings('theme_background_color') }}!important"; 
    } 

    .main-loader .loader::before { 
        background: url({{settings('logo') }}) no-repeat;  
    }
 
</style>
 


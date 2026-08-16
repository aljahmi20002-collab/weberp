$(document).ready(function(){ 
 
      $('#stock-alert-search').keyup(function(){  
          var url         = $(this).data('url'); 
          $.ajax({
              url: url,
              method: 'post',
              dataType: 'html',
              data:{ 
                  search: $(this).val(), 
              },
              success: (response) => {  
                  $('#stock_alert_content').html(response); 
                  
              },
              error: (error) => {  
                  console.log(error);
              }
          })
    });
 
});
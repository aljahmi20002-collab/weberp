$(document).ready(function() { 
 
        $('.table-responsive .table').DataTable({
            dom: '<"row position-relative  margin-bottom-20 text-center"<"export-row text-left"BP<"search-box"tip>r>tip', 
            paging: false,
         
            searching: false, 
            info:false,
            language: { search: "",searchPlaceholder: "Search...",
            emptyTable:function(){
                return '<div class="text-center"><img class="emptyTables" src="'+$('#data-not-available').data('image')+'" /></div>';
            }  },
            
            ordering:false,  
            className:"test",
            buttons: [
                {  
                    extend: 'colvis', 
                    text: '<i class="fa fa-columns"></i> Column visibility' ,  
                    className: 'btn btn-sm export-btn' , 
                    exportOptions: { columns: ':visible' } 
                },
                { 
                    extend: 'csv',    
                    text: '<i class="fa fa-file-csv"></i> Eoxprt to CSV' ,      
                    className: 'btn btn-sm export-btn', 
                    exportOptions: { columns: ':visible' } 
                },
                { 
                    extend: 'excel',  
                    text: '<i class="fa fa-file-excel"></i> Export to Excel' ,    
                    className: 'btn btn-sm export-btn', 
                    exportOptions: { columns: ':visible' } 
                },
    
                { 
                    extend: 'pdf',    
                    text: '<i class="fa fa-file-pdf"></i> Download to PDF' ,    
                    className: 'btn btn-sm export-btn', 
                    exportOptions: { columns: ':visible' } 
                 },
                { 
                    extend: 'print',  
                    text: '<i class="fa fa-print"></i> Print' ,              
                    className: 'btn btn-sm export-btn', 
                    exportOptions: { columns: ':visible' },
                    title:$(this).data('title')
                },
            ] 
        } ); 

    $('.dt-buttons').addClass('btn-group');
    $('.dataTables_filter input').addClass('form-control form--control');
    $('.dataTables_filter').addClass('d-inline-block float-left');
    $('.dt-buttons.btn-group').addClass('float-right');
 

} );  
 
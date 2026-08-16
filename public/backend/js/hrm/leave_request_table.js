$(document).ready(function() {  
    var url = $('#get-leave-request').data('url');
    $('.table').DataTable({
        dom: '<"row position-relative  margin-bottom-20 text-center"<"export-row text-left"lBPfr>tip',  
        //filter
        processing: false,
        serverSide: true,
        ajax: {
            url: url,
            data: function (d) {
                // console.log(d);
            }
        },
    
        columns: [ 
            {data: 'DT_RowIndex',      name: 'DT_RowIndex'}, 
            {data: 'applicant',        name: 'applicant'}, 
            {data: 'leave_type',       name: 'leave_type'}, 
            {data: 'leave_from',       name: 'leave_from'}, 
            {data: 'leave_to',         name: 'leave_to'}, 
            {data: 'file',             name: 'file'}, 
            {data: 'reason',           name: 'reason'}, 
            {data: 'status',           name: 'status'}, 
            {data: 'submited',         name: 'submited'},  
            {data: 'action',           name: 'action', orderable: false, searchable: false},    
        ],
        lengthMenu: [
            [ 10, 25, 50,100,500,1000,-1],
            [ 10, 25, 50,100,500,1000,'All']
        ],
        //filter 
        searching: true, 
        info:true,
        language: { search: "",searchPlaceholder: "Search...",
        emptyTable:function(){
            return '<div class="text-center"><img class="emptyTables" src="'+$('#data-not-available').data('image')+'" /></div>';
        } }, 
        ordering:true,   
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
     
    $('.dataTables_processing').html('<p>Processing...</p>');
    $('.dt-buttons').addClass('btn-group');   
     
});
<script type="text/javascript">

    //attendance  
    var options = {
                    chart: {
                        type: 'donut'
                    }, 
                    series: [0,0,0,0],
                    labels: ['{{ __("total_holiday") }}', '{{ __("total_leave") }}','{{ __("total_absent") }}','{{ __("total_present") }}'],
                    colors: ['#ffc107','#ff1752','#7367f0','#28a745'],  
                    plotOptions: {
                        pie: {
                            donut: {
                                    labels: {
                                        show: true,
                                        name: { 
                                        show:true
                                        },
                                        value:{ 
                                            show:true
                                        }
                                    } 
                                }
                        }
                    } 
                }
    var chart = new ApexCharts(document.querySelector("#attendance_pie_chart"), options);
    chart.render(); 

    function attendance(lifetime=null){ 
        var url       = $('#user_date').data('url');
        var date      = $('#user_date').val(); 
        $.ajax({
            url: url,
            method: 'get',
            dataType: 'json', 
            data:{  
                employee_id: {{ $user->id }},
                date:date,
                from_user_view:'from_user_view'
            },
            success: (response) => {   
                var series  = [
                        response.total_holidays,
                        response.total_leave_days,
                        response.total_absent,
                        (response.total_present+response.total_pending)
                    ];
                chart.updateSeries(series);

                        $('#total_holiday').html(response.total_holidays);
                        $('#total_leave_days').html(response.total_leave_days);
                        $('#total_absent').html(response.total_absent);
                        $('#total_present').html((response.total_present+response.total_pending));
            },
            error: (error) => {    
                console.log(error);
            }
        })   
    } 

    attendance();
 
    $('.attendance-date').on('click',function(e){ 
        e.preventDefault();
        $('.attendance-date').removeClass('active');
        $(this).addClass('active');

        $('#user_date').val($(this).data('date'));
        $('.attendanceDateBtn').html($(this).data('report')); 
         
        attendance(); 
    }); 

</script>
 
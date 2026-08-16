<script type="text/javascript">
    //purchase invoice
    var options = {
                  chart: {
                      type: 'donut'
                  },
                
                  series: [{{ $totalTodo['total_pending'] }},{{ $totalTodo['total_processing']}},{{ $totalTodo['total_completed'] }}],
                  labels: ['{{ __("pending") }}', '{{ __("processing") }}','{{ __("completed") }}'],
                  colors: ['#ff1752','#ffc107','#28a745'],  
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
  var chart = new ApexCharts(document.querySelector("#todo_pie_chart"), options);
  chart.render();
</script>
 
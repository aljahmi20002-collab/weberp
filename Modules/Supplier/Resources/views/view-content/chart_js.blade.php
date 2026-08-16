<script type="text/javascript">
    //purchase invoice
    var options = {
                  chart: {
                      type: 'donut'
                  },
                
                  series: [{{ $purchaseTotal['total_payments'] }}, {{ $purchaseTotal['total_due'] }}],
                  labels: ['{{ __("total_payments") }}', '{{ __("total_due") }}'],
                  colors: ['#28a745', '#ff1752'],  
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
  var chart = new ApexCharts(document.querySelector("#supplier_purchase_pie_chart"), options);
  chart.render();
</script>
 
<script type="text/javascript">
    //purchase return invoice
    var options = {
                  chart: {
                      type: 'donut'
                  },
                
                  series: [{{ $purchaseReturnTotal['total_payments'] }}, {{ $purchaseReturnTotal['total_due'] }}],
                  labels: ['{{ __("total_payments") }}', '{{ __("total_due") }}'],
                  colors: ['#28a745', '#ff1752'],  
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
  var chart = new ApexCharts(document.querySelector("#supplier_purchase_return_pie_chart"), options);
  chart.render();
</script>
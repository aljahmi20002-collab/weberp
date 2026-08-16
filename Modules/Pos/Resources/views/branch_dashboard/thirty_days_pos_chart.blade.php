 
<script type="text/javascript"> 
    $(document).ready(function(){ 
        var posAmount      =  [@foreach ($ThirtyDaysposData['dates'] as $date)   {{ $ThirtyDaysposData['pos_amount'][$date] }}, @endforeach];
        var posPayments    =  [@foreach ($ThirtyDaysposData['dates'] as $date)   {{ $ThirtyDaysposData['pos_payment'][$date] }}, @endforeach];
  
        var options = {
            series: [{ 
                name: 'Total pos amount',
                type: 'area',
                data: posAmount
            }, {
                name: 'Total Payments',
                type: 'line',
                data:posPayments
            }],
            chart: {
                height: 450,
                type: 'line',
            },
            stroke: {
                curve: 'smooth'
            },
            fill: {
                type: 'solid',
                opacity: [0.051, 1],
            },
            title: {
                text: 'Last 30 days pos analytics',
            },
            labels: [@foreach ($ThirtyDaysposData['dates'] as $date)'{{ $date}}', @endforeach],
            markers: {
                size: 0
            },
            yaxis: [
                {
                    title: {
                        text: 'POS Analytics',
                    },
                },
    
            ],
            tooltip: {
                shared: true,
                intersect: false,
                y: {
                    formatter: function (y) {
                        if (typeof y !== "undefined") {
                            return y.toFixed(0);
                        }
                        return y;
                    }
                }
            }
        }; 
        var chart = new ApexCharts(document.querySelector("#pos_chart"), options);
        chart.render();
    });
</script>
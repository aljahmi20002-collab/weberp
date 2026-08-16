 <script type="text/javascript">
     $(document).ready(function() {
         var saleAmount = [
             @foreach ($ThirtyDaysSalesData['dates'] as $date)
                 {{ $ThirtyDaysSalesData['sales_amount'][$date] }},
             @endforeach
         ];
         var salePayments = [
             @foreach ($ThirtyDaysSalesData['dates'] as $date)
                 {{ $ThirtyDaysSalesData['sales_payment'][$date] }},
             @endforeach
         ];

         var options = {
             series: [{
                 name: 'Total sales amount',
                 type: 'area',
                 data: saleAmount
             }, {
                 name: 'Total Payments',
                 type: 'line',
                 data: salePayments
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
                 text: 'Last 30 days sales analytics',
             },
             labels: [
                 @foreach ($ThirtyDaysSalesData['dates'] as $date)
                     '{{ $date }}',
                 @endforeach
             ],
             markers: {
                 size: 0
             },
             yaxis: [{
                     title: {
                         text: 'Sale Analytics',
                     },
                 },

             ],
             tooltip: {
                 shared: true,
                 intersect: false,
                 y: {
                     formatter: function(y) {
                         if (typeof y !== "undefined") {
                             return y.toFixed(0);
                         }
                         return y;
                     }
                 }
             }
         };
         var chart = new ApexCharts(document.querySelector("#sales_chart"), options);
         chart.render();
     });
 </script>


<div class="row">
    <div class="col-md-8"><h3>Google Analytics Report of Last 30 Days</h3></div>
    <div class="col-md-4"><span class="text-info">For details go to <a href="https://analytics.google.com">Google Analytics</a></span> </div>

</div>
<div class="chart-container" style="position: relative; height:60vh; width:80vw">
    <canvas id="myChart" style="width: 100%; height: 80%;"></canvas>
</div>
<script>
    $(function () {
        var ctx = document.getElementById("myChart").getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: [
                    @foreach(collect($data)->all() as $item)
                    {!! "'" . $item['date']->format("d M, Y") . "' ," !!}
                    @endforeach
                ] ,
                datasets: [{
                    label: 'Visitors',
                    data: ['{!!  str_replace("," , "' , '",collect($data)->implode('visitors',",")) !!}'],
                    borderWidth: 3,
                    borderColor:'rgba(255, 99, 132, 0.5)',
                    backgroundColor: 'rgba(0,0,0,0)',
                },
                    {
                        label: 'PageViews',
                        data: ['{!!  str_replace("," , "' , '",collect($data)->implode('pageViews',",")) !!}'],
                        borderWidth: 3,
                        borderColor:'rgba(0, 120, 100, 0.5)',
                        backgroundColor: 'rgba(0,0,0,0)',
                    }
                ]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero:true
                        }
                    }]
                }
            }
        });
    });
</script>
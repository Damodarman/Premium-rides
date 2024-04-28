<?php 
$count = count($weeklyData['uberNeto']);
$weekCount = $count;

?>

<div class="container">
	<div class="row">
		 <label for="weekCount" class="label">Odaberi broj tjedana za prikazati na grafu:</label>
        <select id="weekCount" onchange="updateChart()" class="form-control">
            <option value="<?=$count?>" selected>Svi</option>
            <option value="5">5 tjedana</option>
            <option value="10">10 tjedana</option>
            <option value="15">15 tjedana</option>
            <option value="20">20 tjedana</option>
            <option value="25">25 tjedana</option>
            <!-- Add more options as needed -->
        </select>
		<canvas id="myChart" width="400" height="300"></canvas>
	</div>
</div>


<script>
    var originalData = <?php echo json_encode($weeklyData); ?>;
    var myChart;
	console.log(typeof originalData),
    function updateChart() {
        var weekCount = document.getElementById('weekCount').value;

        // Slice the data arrays based on the selected number of weeks
        var slicedData = {
            uberNeto: originalData['uberNeto'].slice(-weekCount),
            boltNeto: originalData['boltNeto'].slice(-weekCount),
            ukupnoNeto: originalData['ukupnoNeto'].slice(-weekCount),
            gotovina: originalData['gotovina'].slice(-weekCount),
            zaradaFirme: originalData['zaradaFirme'].slice(-weekCount)
        };

        // Update the chart data
        myChart.data.datasets[0].data = slicedData.uberNeto;
        myChart.data.datasets[1].data = slicedData.boltNeto;
        myChart.data.datasets[2].data = slicedData.ukupnoNeto;
        myChart.data.datasets[3].data = slicedData.gotovina;
        myChart.data.datasets[4].data = slicedData.zaradaFirme;

        // Update the chart
        myChart.update();
    }

    document.addEventListener('DOMContentLoaded', function() {
        var ctx = document.getElementById('myChart').getContext('2d');
        myChart = new Chart(ctx, {
            type: 'line',
            data: {
                datasets: [
                    {
                        type: 'line',
                        label: 'Uber Neto',
                        data: originalData['uberNeto']
                    },
                    {
                        type: 'line',
                        label: 'Bolt Neto',
                        data: originalData['boltNeto']
                    },
                    {
                        type: 'bar',
                        label: 'Ukupno Neto',
                        data: originalData['ukupnoNeto']
                    },
                    {
                        type: 'bar',
                        label: 'Gotovina',
                        data: originalData['gotovina']
                    },
                    {
                        type: 'line',
                        label: 'Zarada',
                        data: originalData['zaradaFirme']
                    },
                ]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    });
</script>


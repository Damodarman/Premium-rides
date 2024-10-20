<!-- app/Views/partials/footer.php -->
<footer class="main-footer">
    <strong>Copyright &copy; <?= date('Y') ?> Vehicle Management System.</strong>
    All rights reserved.
</footer>

</div> <!-- Wrapper End -->

<!-- Include jQuery first -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>

<!-- Bootstrap JS (Bootstrap 5 already includes Popper.js) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Other necessary scripts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/js/adminlte.min.js" integrity="sha512-KBeR1NhClUySj9xBB0+KRqYLPkM6VvXiiWaSz/8LCQNdRpUm38SWUrj0ccNDNSkwCD9qPA4KobLliG26yPppJA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- Flatpickr JS -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
 

<?php if (current_url() == site_url('driver/index')): ?>
    <script>
        // Assuming $driversPerWeek contains both driver_count and zaradaFirmi data
        const weeks = [];
        const driverCounts = [];
        const zaradaFirmeData = [];

        <?php foreach ($driversPerWeek as $row): ?>
            weeks.push('<?= $row["week"] ?>');
            driverCounts.push(<?= $row["driver_count"] ?>);
            zaradaFirmeData.push(<?= $row["zaradaFirme"]?>);
        <?php endforeach; ?>

        // Render the chart using Chart.js
        const ctx = document.getElementById('driversChart').getContext('2d');
        const chart = new Chart(ctx, {
            type: 'line', // You can also use 'bar' for a bar chart
            data: {
                labels: weeks, // X-axis labels (weeks)
                datasets: [{
                    label: 'Number of Drivers',
                    data: driverCounts, // Y-axis data (driver counts)
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderWidth: 1,
                    pointStyle: 'circle',
                    pointRadius: 5,
                    pointHoverRadius: 10,
                    yAxisID: 'y',
                },
                {
                    label: 'Company Earnings',
                    data: zaradaFirmeData, // Y-axis data (zaradaFirmi)
                    borderColor: 'rgba(255, 99, 132, 1)',
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderWidth: 1,
                    pointStyle: 'circle',
                    pointRadius: 5,
                    pointHoverRadius: 10,
                    yAxisID: 'y1',
                }]
            },
            options: {
                responsive: true,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                stacked: false,
                plugins: {
                    title: {
                        display: true,
                        text: 'Grafički prikaz broja vozača i zarade kroz odabrano vrijeme'
                    }
                },
                scales: {
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',

                        // grid line settings
                        grid: {
                            drawOnChartArea: false, // only want the grid lines for one axis to show up
                        },
                    },
                }
            },
        });
    </script>
<script>
$(document).ready(function () {
    let weeks = [];
    let percentages = [];
    let quarters = [];
    let currentQuarterIndex = 0; // Initialize to the first quarter
    let quarterKeys = []; // To store keys of quarters

    // Initialize the date pickers (for single week and week range)
    $('#weekPicker').datepicker();
    $('#weekRangePicker').daterangepicker();

    // Fetch initial data (Overall/All Time data)
    fetchData();

    function fetchData(startWeek = null, endWeek = null) {
        if (!startWeek && !endWeek) {
            console.log("Fetching overall data...");
            // Hide quarter navigation on page load (overall data)
            $('#quarterNavContainer').hide();
        } else {
            console.log("Fetching data for weeks:", startWeek, endWeek);
            // Show quarter navigation only when specific weeks (quarters) are selected
            $('#quarterNavContainer').show();
        }

        $.ajax({
            url: '<?= site_url('/api/getPlatformRatio') ?>',
            type: 'POST',
            dataType: 'json',
            data: {
                startWeek: startWeek,
                endWeek: endWeek
            },
            success: function (data1) {
                if (!weeks.length) {
                    weeks = data1.weeks; // Populate weeks array
                }
                if (!quarters || !Object.keys(quarters).length) {
                    quarters = data1.quarters; // Populate quarters array only once
                    quarterKeys = Object.keys(quarters); // Initialize quarterKeys
                }
                percentages = data1.percentages;
                populateWeekDropdowns(weeks); // Populate dropdowns if needed
                updateChart(percentages); // Update chart with overall or filtered data
            },
            error: function (xhr, status, error) {
                console.error('Error fetching data:', error);
            }
        });
    }

    // Populate year options
    function populateWeekDropdowns(weeks) {
        $('#year').empty(); // Clear year options
        weeks.forEach(week => {
            let year = week.substring(0, 4); // Extract year from week
            if (!$('#year option[value="' + year + '"]').length) {
                $('#year').append(new Option(year, year));
            }
        });
    }

    let platformRatioChart;
    function updateChart(percentages) {
        const ctx = document.getElementById('platformRatioChart').getContext('2d');
        if (platformRatioChart) {
            platformRatioChart.data.datasets[0].data = [percentages.uber, percentages.bolt, percentages.taximetar];
            platformRatioChart.update();  // Call update to re-render the chart
        } else {
            platformRatioChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: ['Uber', 'Bolt', 'Taximetar'], // Platform names
                    datasets: [{
                        data: [percentages.uber, percentages.bolt, percentages.taximetar], // Use percentages from server-side
                        backgroundColor: [
                            'rgb(0, 78, 186)', // Uber
                            'rgb(33, 240, 10)', // Bolt
                            'rgb(235, 231, 9)'  // Taximetar
                        ],
                        borderColor: [
                            'rgba(255, 255, 255, 1)',
                            'rgba(255, 255, 255, 1)',
                            'rgba(255, 255, 255, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        title: {
                            display: true,
                            text: 'Platform Activity Breakdown (Percentages)'
                        }
                    }
                }
            });
        }
    }

    // Populate the quarter dropdown dynamically from the 'quarters' data
    function populateQuarterDropdown() {
        let $quarterSelect = $('#quarter');
        $quarterSelect.empty(); // Clear previous options
        $.each(quarters, function (key, value) {
            $quarterSelect.append(new Option(key, key)); // Populate quarter dropdown with labels
        });
    }

    // Show/Hide form fields based on filter type
    $('#filterType').on('change', function () {
        let filterType = $(this).val();
        $('#weekSelection, #weekRangeSelection, #quarterSelection, #yearSelection').addClass('d-none');
        if (filterType === 'week') {
            $('#weekSelection').removeClass('d-none');
        } else if (filterType === 'weekRange') {
            $('#weekRangeSelection').removeClass('d-none');
        } else if (filterType === 'quarter') {
            $('#quarterSelection').removeClass('d-none');
            populateQuarterDropdown(); // Populate quarters dynamically when "Quarter" is selected
        } else if (filterType === 'year') {
            $('#yearSelection').removeClass('d-none');
        }
    });

    // Open modal on icon click
    $('#openFilterModal').on('click', function () {
        $('#filterModal').modal('show');
    });

    // Apply filter from modal
    $('#applyFilterModal').on('click', function () {
        let filterType = $('#filterType').val();
        let startWeek = null, endWeek = null;
        if (filterType === 'week') {
            startWeek = $('#weekPicker').val();
            endWeek = startWeek;
        } else if (filterType === 'weekRange') {
            let range = $('#weekRangePicker').val().split(' - ');
            startWeek = range[0];
            endWeek = range[1];
        } else if (filterType === 'quarter') {
            let selectedQuarter = $('#quarter').val(); // Get selected quarter
            let quarterData = quarters[selectedQuarter]; // Fetch startWeek and endWeek from the quarters object
            startWeek = quarterData.startWeek;
            endWeek = quarterData.endWeek;
        } else if (filterType === 'year') {
            let year = $('#year').val();
            startWeek = `${year}01`;
            endWeek = `${year}52`;
        }
        // Fetch data based on the selected filter
        fetchData(startWeek, endWeek);
        // Close modal
        $('#filterModal').modal('hide');
    });

    // ==================== QUARTER NAVIGATION FUNCTIONALITY ====================

    // Function to update the chart based on the selected quarter
    function updateChartForQuarter(quarterIndex) {
        let selectedQuarter = quarterKeys[quarterIndex];
        let quarterData = quarters[selectedQuarter];
        if (quarterData) {
            $('#currentQuarterDisplay').text(selectedQuarter); // Update displayed quarter
            let startWeek = quarterData.startWeek;
            let endWeek = quarterData.endWeek;
            fetchData(startWeek, endWeek); // Fetch data for the selected quarter
            updateQuarterButtons(quarterIndex); // Update the visibility of the buttons
            $('#quarterNavContainer').show(); // Show the navigation buttons
        }
    }

    // Function to update the visibility of the quarter navigation buttons
    function updateQuarterButtons(quarterIndex) {
        if (quarterIndex === 0) {
            $('#firstQuarterBtn, #prevQuarterBtn').hide();
        } else {
            $('#firstQuarterBtn').show();
            $('#prevQuarterBtn').show().attr('title', `Previous quarter: ${quarterKeys[quarterIndex - 1]}`);
        }
        if (quarterIndex === quarterKeys.length - 1) {
            $('#nextQuarterBtn, #lastQuarterBtn').hide();
        } else {
            $('#nextQuarterBtn').show().attr('title', `Next quarter: ${quarterKeys[quarterIndex + 1]}`);
            $('#lastQuarterBtn').show();
        }
    }

    // First quarter button click
    $('#firstQuarterBtn').on('click', function () {
        currentQuarterIndex = 0;
        updateChartForQuarter(currentQuarterIndex);
    });

    // Previous quarter button click
    $('#prevQuarterBtn').on('click', function () {
        if (currentQuarterIndex > 0) {
            currentQuarterIndex--;
            updateChartForQuarter(currentQuarterIndex);
        }
    });

    // Next quarter button click
    $('#nextQuarterBtn').on('click', function () {
        if (currentQuarterIndex < quarterKeys.length - 1) {
            currentQuarterIndex++;
            updateChartForQuarter(currentQuarterIndex);
        }
    });

    // Last quarter button click
    $('#lastQuarterBtn').on('click', function () {
        currentQuarterIndex = quarterKeys.length - 1;
        updateChartForQuarter(currentQuarterIndex);
    });
});
</script>

<?php endif; ?>


<!-- Initialize DataTables -->
<script>
    $(document).ready(function() {
        $('#vehiclesTable').DataTable({
            paging: false, // Disable DataTables pagination
            ordering: true, // Enable sorting
            info: false, // Disable the information display
            dom: 'Bfrtip', // Show buttons for exporting
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ]
        });
    });
</script>
<script>
    $(document).ready(function() {
        // Initialize DataTable with export buttons and column-specific filters
        var table = $('#taximetarReportsTable').DataTable({
			lengthMenu: [
				[10, 25, 50, -1],
				[10, 25, 50, 'All']
			],
			"responsive": true, "lengthChange": false, "autoWidth": false,
            paging: true, // Enable pagination
            ordering: true, // Enable sorting
            info: false, // Disable the information display
            dom: 'Bfrtip', // Show buttons for exporting
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print','pageLenght'
            ]
        });

        // Apply column-specific search for each column
        $('#taximetarReportsTable thead input').on('keyup change', function() {
            var columnIndex = $(this).parent().index(); // Get the column index
            table.column(columnIndex).search(this.value).draw(); // Apply search to the column
        });
    });
</script>
<script>
    $(document).ready(function() {
        // Initialize DataTable with export buttons and column-specific filters
        var table = $('#taximetarReportsTable').DataTable({
			lengthMenu: [
				[10, 25, 50, -1],
				[10, 25, 50, 'All']
			],
			"responsive": true, "lengthChange": false, "autoWidth": false,
            paging: true, // Enable pagination
            ordering: true, // Enable sorting
            info: false, // Disable the information display
            dom: 'Bfrtip', // Show buttons for exporting
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print','pageLenght'
            ]
        });

        // Apply column-specific search for each column
        $('#taximetarReportsTable thead input').on('keyup change', function() {
            var columnIndex = $(this).parent().index(); // Get the column index
            table.column(columnIndex).search(this.value).draw(); // Apply search to the column
        });
    });
</script>



<script>
    $(document).ready(function() {
        // Initialize DataTable with export buttons and column-specific filters
        var table = $('#driverReportsTable').DataTable({
			lengthMenu: [
				[10, 25, 50, -1],
				[10, 25, 50, 'All']
			],
			"responsive": true, "lengthChange": false, "autoWidth": false,
            paging: true, // Enable pagination
            ordering: true, // Enable sorting
            info: false, // Disable the information display
            dom: 'Bfrtip', // Show buttons for exporting
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print','pageLenght'
            ]
        });

        // Apply column-specific search for each column
        $('#driverReportsTable thead input').on('keyup change', function() {
            var columnIndex = $(this).parent().index(); // Get the column index
            table.column(columnIndex).search(this.value).draw(); // Apply search to the column
        });
    });
</script>
<script>
    $(document).ready(function() {
        // Initialize DataTable with export buttons and column-specific filters
        var table = $('#uberReportsTable').DataTable({
			lengthMenu: [
				[10, 25, 50, -1],
				[10, 25, 50, 'All']
			],
			"responsive": true, "lengthChange": false, "autoWidth": false,
            paging: true, // Enable pagination
            ordering: true, // Enable sorting
            info: false, // Disable the information display
            dom: 'Bfrtip', // Show buttons for exporting
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print','pageLenght'
            ]
        });

        // Apply column-specific search for each column
        $('#uberReportsTable thead input').on('keyup change', function() {
            var columnIndex = $(this).parent().index(); // Get the column index
            table.column(columnIndex).search(this.value).draw(); // Apply search to the column
        });
    });
</script>
<script>
    $(document).ready(function() {
        // Initialize DataTable with export buttons and column-specific filters
        var table = $('#boltReportsTable').DataTable({
			lengthMenu: [
				[10, 25, 50, -1],
				[10, 25, 50, 'All']
			],
			"responsive": true, "lengthChange": false, "autoWidth": false,
            paging: true, // Enable pagination
            ordering: true, // Enable sorting
            info: false, // Disable the information display
            dom: 'Bfrtip', // Show buttons for exporting
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print','pageLenght'
            ]
        });

        // Apply column-specific search for each column
        $('#boltReportsTable thead input').on('keyup change', function() {
            var columnIndex = $(this).parent().index(); // Get the column index
            table.column(columnIndex).search(this.value).draw(); // Apply search to the column
        });
    });
</script>

<!-- Custom Script to Check jQuery and Run AJAX -->
<script>
    // Run when the document is ready
    $(document).ready(function() {
        // Send AJAX request to fetch vehicle counts
        $.ajax({
            url: '<?= site_url('/api/vehicle-counts') ?>',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                console.log(response); // Check the console for the API response

                // Update the badges with the fetched data
                $('#allVehiclesBadge').text(response.allVehiclesCount);
                $('#operatingVehiclesBadge').text(response.operatingVehiclesCount);
                $('#nonWorkingVehiclesBadge').text(response.nonWorkingVehiclesCount);
            },
            error: function(xhr, status, error) {
                console.error("Error fetching vehicle counts: ", error);
            }
        });
    });
</script>
<script>
    // Run when the document is ready
    $(document).ready(function() {
        // Send AJAX request to fetch vehicle counts
        $.ajax({
            url: '<?= site_url('/api/drivers-counts') ?>',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                console.log(response); // Check the console for the API response

                // Update the badges with the fetched data
                $('#allDriversBadge').text(response.allDriversCount);
                $('#operatingDriversBadge').text(response.operatingDriversCount);
                $('#nonWorkingDriversBadge').text(response.nonWorkingDriversCount);
            },
            error: function(xhr, status, error) {
                console.error("Error fetching drivers counts: ", error);
            }
        });
    });
</script>







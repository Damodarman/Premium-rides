<div class="container mt-5">

    <!-- Filtering Form -->
<!--
    <form id="filter-form" class="mb-3">
        <div class="form-row">
            <div class="form-group col-md-3">
                <label for="user">User</label>
                <input type="text" class="form-control" id="user" name="user" placeholder="Enter user">
            </div>
            <div class="form-group col-md-3">
                <label for="date">Date</label>
                <input type="date" class="form-control" id="date" name="date">
            </div>
            <div class="form-group col-md-3">
                <label for="start_date">Start Date</label>
                <input type="date" class="form-control" id="start_date" name="start_date">
            </div>
            <div class="form-group col-md-3">
                <label for="end_date">End Date</label>
                <input type="date" class="form-control" id="end_date" name="end_date">
            </div>
        </div>

        <button type="button" id="filter-button" class="btn btn-primary">Filter</button>
    </form>
-->

    <!-- Data Table -->
<div class="container">
    <h1>Pregled naplate</h1>

<form id="filter-form" class="row g-3">
    <!-- User input -->
	<?php if($role != 'admin'): ?>
	<div class="col-md-2">
		<label for="user" class="form-label">User</label>
        <input type="text" class="form-control" id="" name="" value ="<?=$name?>" disabled>
        <input type="text" class="form-control" id="user" name="user" value ="<?=$name?>" hidden>
	  </div>
	<?php else:?>
	<div class="col-md-2">
		<label for="user" class="form-label">User</label>
        <input type="text" class="form-control" id="user" name="user" placeholder="Enter user">
	  </div>
	<?php endif ?>
	<div class="col-md-2">
		<label for="user" class="form-label">Vozac</label>
        <input type="text" class="form-control" id="vozac" name="vozac" placeholder="Enter vozac">
    <input type="hidden" id="startDate" name="start_date">
    <input type="hidden" id="endDate" name="end_date">
	  </div>
	<div class="col-md-2">
		<label for="nacin_placanja" class="form-label">Način plačanja</label>
        <input type="text" class="form-control" id="nacin_placanja" name="nacin_placanja" placeholder="Enter nacin plačanja">
	  </div>
	<div class="col-md-1">
		<label for="iznos" class="form-label">Iznos</label>
        <input type="text" class="form-control" id="iznos" name="iznos" placeholder="Enter Iznos">
	  </div>
<!--
	<div class="col-md-2">
		<label for="user" class="form-label">User</label>
        <input type="text" class="form-control" id="user" name="user" placeholder="Enter user">
	  </div>
-->
	<div class="col-md-3">
		<label for="daterange" class="form-label">Raspon</label>
        <div id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc;">
            <i class="fa fa-calendar"></i>&nbsp;
            <span>Pick a date range</span> 
            <i class="fa fa-caret-down"></i>
        </div>
	  </div>
	<div class="col-md-1">
		<label for="predano" class="form-label">Predano</label>
        <input type="text" class="form-control" id="predano" name="predano" placeholder="DA / NE">
	  </div>
	<div class="col-md-1">
		<label for="primljeno" class="form-label">Primljeno</label>
        <input type="text" class="form-control" id="primljeno" name="primljeno" placeholder="DA / NE">
	  </div>

    <!-- Vozac input -->
<!--
    <div class="form-group mx-2">
        <input type="text" class="form-control" id="vozac" name="vozac" placeholder="Enter vozac">
    </div>


    <div class="form-group mx-2">
        <div id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc;">
            <i class="fa fa-calendar"></i>&nbsp;
            <span>Pick a date range</span> 
            <i class="fa fa-caret-down"></i>
        </div>
    </div>
	-->

</form>
    <table class="table table-striped" id="filtered-table">
		<thead>
            <tr>
                <th>User</th>
                <th>Vozac</th>
                <th>Nacin Placanja</th>
                <th>Iznos</th>
                <th>Vrijeme plaćanja</th>
                <th>Predano</th>
                <th>Primljeno</th>
            </tr>
        </thead>
        <tbody id="table-body">
            <!-- Start with placeholder rows -->
            <tr class="skeleton-row"><td colspan="6"></td></tr>
            <tr class="skeleton-row"><td colspan="6"></td></tr>
            <tr class="skeleton-row"><td colspan="6"></td></tr>
        </tbody>
    </table>
</div>

</div>


<script>
$(document).ready(function() {
    const tableBody = $('#table-body');
// Variable to store the extracted number from span ID
    let naplataId = null;

    // Event delegation to listen for clicks on span buttons
    tableBody.on('click', 'span.btn', function() {
        // Get the ID of the clicked span
        const clickedSpanId = $(this).attr('id'); // Save the ID to a variable


        // Extract numbers from the ID
        const numbers = clickedSpanId.match(/\d+/); // Extract the numeric part

        if (numbers && numbers.length > 0) {
            naplataId = numbers[0]; // Get the first numeric match
            console.log("Naplata ID:", naplataId); // Debug output
        }

        // Further logic based on the extracted number
        let ajaxUrl;
        if (clickedSpanId.startsWith('predano_')) {
            ajaxUrl = '<?= base_url("index.php/dugovi/predano") ?>'; // URL to the predano method
        } else if (clickedSpanId.startsWith('primljeno_')) {
            ajaxUrl = '<?= base_url("index.php/dugovi/primljeno") ?>'; // URL to the primljeno method
        }

        // Send an AJAX request to the appropriate controller method
        if (ajaxUrl) {
            $.ajax({
                url: ajaxUrl,
                method: 'POST', // Or 'GET' depending on your implementation
                data: { id: naplataId }, // Pass the extracted ID as data
                success: function(response) {
                    console.log('AJAX success:', response); // Handle successful response
                    // You can update the UI or perform other actions based on the response
					fetchData(); // Refresh the data to reflect changes
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error('AJAX error:', textStatus, errorThrown); // Handle errors
                }
            });
        }
	});

    // Initial data load
    function fetchData() {
        const formData = $('#filter-form').serialize(); // Serialize form data
        console.log("Fetching data with:", formData);

        $.ajax({
            url: '<?= base_url("index.php/dugovi/getFilteredData") ?>', // Your endpoint
            method: 'POST', // Or 'GET' depending on your implementation
            data: formData, // Data for filtering
            success: function(response) {
                tableBody.empty(); // Clear existing content

                if (Array.isArray(response)) {
                    response.forEach(item => {
						 let rowClass = '';
						if (item.predano === 'DA' && item.primljeno === 'DA') {
                            rowClass = 'table-success'; // Both are 'DA'
                        } else if (
                            (item.predano === 'DA' && item.primljeno === 'NE') ||
                            (item.predano === 'NE' && item.primljeno === 'DA')
                        ) {
                            rowClass = 'table-warning'; // One is 'DA' and the other is 'NE'
                        } else if (item.predano === 'NE' && item.primljeno === 'NE') {
                            rowClass = 'table-danger'; // Both are 'NE'
                        }
                        const row = `<tr class="${rowClass}">
                            <td>${item.user}</td>
                            <td>${item.vozac}</td>
                            <td>${item.nacin_placanja}</td>
                            <td>${item.iznos}</td>
                            <td>${item.timestamp}</td>
                            <td><span id="predano_${item.id}" class="btn btn-sm btn-success">${item.predano}</span></td>
                            <td><span id="primljeno_${item.id}" class="btn btn-sm btn-success">${item.primljeno}</span></td>
                        </tr>`;
                        tableBody.append(row);
                    });
                } else {
                    const errorRow = `<tr><td colspan="6">Unexpected response format.</td></tr>`;
                    tableBody.append(errorRow);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('Error fetching data:', textStatus, errorThrown);
                const errorRow = `<tr><td colspan="6">Error fetching data.</td></tr>`;
                tableBody.append(errorRow);
            }
        });
    }

    // Load all data on the first page load
    fetchData();

    // Trigger when user types in any input field
    $('#filter-form').on('input', fetchData);

    // Trigger when Date Range Picker value is applied
    $('#reportrange').on('apply.daterangepicker', function() {
        // Update the hidden input fields
        fetchData(); // Manually trigger the AJAX call
    });

    // Configure Date Range Picker
		var start = moment().startOf('isoWeek'); 
		var end = moment().endOf('isoWeek');
    function cb(start, end) {
        $('#reportrange span').html(start.format('D MMMM, YYYY') + ' - ' + end.format('D MMMM, YYYY'));
        $('#startDate').val(start.format('YYYY-MM-DD'));
        $('#endDate').val(end.format('YYYY-MM-DD'));
    }

    $('#reportrange').daterangepicker({
        startDate: start,
        endDate: end,
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'This Week': [moment().startOf('isoWeek'), moment().endOf('isoWeek')],
			'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    }, cb);

    // Initial callback to set the hidden input fields
    cb(start, end);
});

</script>

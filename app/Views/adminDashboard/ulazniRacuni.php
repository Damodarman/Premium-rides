<div class="container">
	<div class="row">
		<div class="col-10">
			<form action="<?php echo base_url('index.php/getRacuni'); ?> " method="post">
			<div class="form-group">
				<label for="start_date">Start Date:</label>
				<input type="text" class="form-control datepicker" id="start_date" name="start_date">
			</div>
			<div class="form-group">
				<label for="end_date">End Date:</label>
				<input type="text" class="form-control datepicker" id="end_date" name="end_date">
			</div>
			<div class="form-group">
			<button type="submit" class="btn btn-secondary mt-4" type="button">Dohvati račune</button>

			</div>
		</div>
			</form>
		<div class="col-2">
			<div class="btn-group-vertical">
				<button class="btn btn-secondary" type="button" id="btnThisYear">This Year</button>
				<button class="btn btn-secondary" type="button" id="btnThisMonth">This Month</button>
				<button class="btn btn-secondary" type="button" id="btnLastMonth">Last Month</button>
				<button class="btn btn-secondary" type="button" id="btnThisWeek">This Week</button>
				<button class="btn btn-secondary" type="button" id="btnLastWeek">Last Week</button>
			</div>
		</div>
	</div>
</div>

<?php if(isset($racuni)) : ?>
 <button type="button" class="btn btn-primary" id="dwnldBtn">Skini tablicu u excelu</button> 
<?= $table ?>


<?php endif ?>


<script>
    $(document).ready(function () {
        $('#dwnldBtn').on('click', function () {
            // Get the current date
            var currentDate = new Date();
            
            // Format the date in Croatian date format
            var options = { year: 'numeric', month: 'numeric', day: 'numeric' };
            var formattedDate = currentDate.toLocaleDateString('hr-HR', options);
            
            // Set the filename with the current date
            var filename = "Ulazni računi" + formattedDate + ".xls";

            // Trigger the table2excel plugin with the dynamic filename
            $("#DataTable").table2excel({
                filename: filename
            });
        });
    });
</script>






<script>
$(document).ready(function() {
	$('.datepicker').datepicker({
		format: 'yyyy-mm-dd',
		autoclose: true
	});

	$('#btnThisYear').click(function() {
		var date = new Date();
		var year = date.getFullYear();
		$('#start_date').datepicker('setDate', year + '-01-01');
		$('#end_date').datepicker('setDate', year + '-12-31');
	});

	$('#btnThisMonth').click(function() {
		var date = new Date();
		var year = date.getFullYear();
		var month = date.getMonth() + 1;
		$('#start_date').datepicker('setDate', year + '-' + month + '-01');
		$('#end_date').datepicker('setDate', year + '-' + month + '-' + getDaysInMonth(month, year));
	});

	$('#btnLastMonth').click(function() {
		var date = new Date();
		var year = date.getFullYear();
		var month = date.getMonth();
		if (month === 0) {
			month = 12;
			year--;
		}
		$('#start_date').datepicker('setDate', year + '-' + month + '-01');
		$('#end_date').datepicker('setDate', year + '-' + month + '-' + getDaysInMonth(month, year));
	});

	$('#btnThisWeek').click(function() {
		var date = new Date();
		var startOfWeek = new Date(date.getFullYear(), date.getMonth(), date.getDate() - date.getDay());
		var endOfWeek = new Date(date.getFullYear(), date.getMonth(), date.getDate() + (6 - date.getDay()));
		$('#start_date').datepicker('setDate', formatDate(startOfWeek));
		$('#end_date').datepicker('setDate', formatDate(endOfWeek));
	});

	$('#btnLastWeek').click(function() {
		var date = new Date();
		var startOfWeek = new Date(date.getFullYear(), date.getMonth(), date.getDate() - date.getDay() - 7);
		var endOfWeek = new Date(date.getFullYear(), date.getMonth(), date.getDate() - date.getDay() - 1);
		$('#start_date').datepicker('setDate', formatDate(startOfWeek));
		$('#end_date').datepicker('setDate', formatDate(endOfWeek));
	});

	// Helper function to format the date as "yyyy-mm-dd"
	function formatDate(date) {
		var year = date.getFullYear();
		var month = date.getMonth() + 1;
		var day = date.getDate();
		return year + '-' + (month < 10 ? '0' : '') + month + '-' + (day < 10 ? '0' : '') + day;
	}

	// Helper function to get the number of days in a month
	function getDaysInMonth(month, year) {
		return new Date(year, month, 0).getDate();
	}
});
</script>

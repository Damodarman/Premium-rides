<!DOCTYPE html>
<html>
<head>
	<title>Number Generator</title>
</head>
<body>
	<h1>Number Generator</h1>
	<form action="<?php echo base_url('index.php/NumberGenerationController/generate');?>" method="post">
		<label for="total_sum">Total Sum:</label>
		<input type="number" id="total_sum" name="total_sum" required><br><br>

		<label for="start_date">Start Date:</label>
		<input type="date" id="start_date" name="start_date" required><br><br>

		<label for="end_date">End Date:</label>
		<input type="date" id="end_date" name="end_date" required><br><br>
		
		<input type="submit" value="Generate">
	</form>
	
	
	
	
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>	
	
	
<script>
var wrapper = $("#input_fields");
var add_button = $("#add_field");

var x = 1;

$(add_button).click(function (e) {
    e.preventDefault();
    x++;
    $(wrapper).append('<div><label for="place_' + x + '">Place:</label><input type="text" name="place[]" id="place_' + x + '" required><label for="order_' + x + '">Order:</label><input type="text" name="order[]" id="order_' + x + '" required><a href="#" class="remove_field">Remove</a></div>');
});

$(wrapper).on("click", ".remove_field", function (e) {
    e.preventDefault();
    $(this).parent('div').remove();
    x--;
});	
</script>
</body>
</html>
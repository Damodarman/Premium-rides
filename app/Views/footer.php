    <div class="container footer footer-bottom clearfix mt-15 pt-15">
      <div class="copyright">
        &copy; Copyright <strong><span>Premium Rides</span></strong>. All Rights Reserved
      </div>
      <div class="credits">
        Designed by <a href="https://premium-rides.com/">Premium Rides</a>
      </div>

    
    
    </div>

</body>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

<script src="<?php echo base_url()?>/assets/vendor/aos/aos.js"></script>
  <script src="<?php echo base_url()?>/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="<?php echo base_url()?>/assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="<?php echo base_url()?>/assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
  <script src="<?php echo base_url()?>/assets/vendor/php-email-form/validate.js"></script>
  <script src="<?php echo base_url()?>/assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="<?php echo base_url()?>/assets/vendor/waypoints/noframework.waypoints.js"></script>

  <!-- Template Main JS File -->
<script type="text/javascript" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
  <script src="<?php echo base_url()?>/assets/js/main.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <!-- Moment.js (for date handling) -->
    
    <!-- Date Range Picker JavaScript -->
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








</html>
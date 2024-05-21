<?php
// Function to check if a date exists in both arrays
function dateExistsInBoth($date, $boltDates, $uberDates) {
    return in_array($date, $boltDates) && in_array($date, $uberDates);
}


// Function to get a color and tooltip based on date existence
function getColorAndTooltip($date, $boltDates, $uberDates) {
    if (in_array($date, $boltDates) && in_array($date, $uberDates)) {
        return ['bg-success', 'Both Uber and Bolt activity uploaded'];
    } elseif (in_array($date, $boltDates)) {
        return ['bg-warning', 'Missing Uber activity'];
    } elseif (in_array($date, $uberDates)) {
        return ['bg-info', 'Missing Bolt activity'];
    } else {
        return ['bg-light', 'No activity uploaded']; // Default: no data
    }
}

// Get current month and year
$currentMonth = date('m');
$currentYear = date('Y');

// Calculate first and last day of the month
$firstDay = mktime(0, 0, 0, $currentMonth, 1, $currentYear);
$lastDay = mktime(0, 0, 0, $currentMonth + 1, 0, $currentYear);
?>


<div class="container">
	<div class="row border border-danger">


			<div class="col-md-6 mt-12">
				<div class="card">
					<div class="card-header text-center">
						<strong>Upload CSV Files Uber Driver Activity</strong>
						<p>Nije potrebno nikakvo konvertiranje. Dovoljno je samo odabrati "Driver Activity" datoteke koje generirate na Uberu na Engleskom jeziku</p>
					</div>
					<div class="card-body">
						<div class="mt-2">
							<?php if (session()->has('msgActivityUber')) { ?>
								<div class="alert <?= session()->getFlashdata('alert-class') ?>">
									<?= session()->getFlashdata('msgActivityUber') ?>
								</div>
							<?php } ?>
						</div>
						<form action="<?php echo base_url('index.php/ImportController/uberActivityReportImport'); ?>" method="post" enctype="multipart/form-data">
							<div class="form-group mb-3">
								<div class="mb-3">
									<input type="file" name="files[]" class="form-control" id="files" multiple> 
								</div>
							</div>
							<div class="d-grid">
								<input type="submit" name="submit" value="Upload" class="btn btn-dark" />
							</div>
						</form>
					</div>
				</div>
			</div>

		
		<div class="col-md-6 mt-12">
			<div class="card">
				<div class="card-header text-center">
					<strong>Upload CSV Files Bolt Driver Activity</strong>
					<p>Upload your Bolt activity CSV files as-is.</p> 
				</div>
				<div class="card-body">
							<div class="mt-2">
								<?php if (session()->has('msgActivityBolt')) { ?>
									<div class="alert <?= session()->getFlashdata('alert-class') ?>">
										<?= session()->getFlashdata('msgActivityBolt') ?>
									</div>
								<?php } ?>
							</div>
				   <form action="<?php echo base_url('index.php/ImportController/boltActivityImport'); ?>" method="post" enctype="multipart/form-data">
						<div class="form-group mb-3">
							<div class="mb-3">
								<input type="file" name="files[]" class="form-control" id="files" multiple>

							</div>
						</div>
						<div class="d-grid">
							<input type="submit" name="submit" value="Upload" class="btn btn-dark" />
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	
<div class="row border border-dark mt-5">
	<h1 class="h1 text-center border-dark"> Kalendar sa informacijama o dostupnim aktivnostima vozača</h1>
	<div id="calendar">
	
	</div> 

</div>	
	
</div>


    <script>
        $(document).ready(function() {
        // Access the PHP arrays directly since they are already in JS format
        var boltDates = <?= json_encode(array_values($boltDates)); ?>;
        var uberDates = <?= json_encode(array_values($uberDates)); ?>;
       
        function getColorAndTooltip(date) {
            var formattedDate = moment(date).format('YYYY-MM-DD');
            if (boltDates.includes(formattedDate) && uberDates.includes(formattedDate)) {
                return { color: 'green', tooltip: 'I Uber i Bolt su dostupni' };
            } else if (boltDates.includes(formattedDate)) {
                return { color: 'orange', tooltip: 'Samo Bolt je dostupan' };
            } else if (uberDates.includes(formattedDate)) {
                return { color: 'blue', tooltip: 'Samo Uber je dostupan' };
            } else {
                return { color: 'lightgray', tooltip: 'Ni Uber ni Bolt nisu dostupni' }; 
            }
        }

           var calendarEl = document.getElementById('calendar');
     var calendar = new FullCalendar.Calendar(calendarEl, {
		 locale: 'hr',
         themeSystem: 'bootstrap5', // Set the theme to Bootstrap 5
         headerToolbar: {
             left: 'prev,next today',
             center: 'title',
             right: 'dayGridMonth'
         },
         initialView: 'dayGridMonth', // Choose the initial view (e.g., month, week, day)
         events: function(fetchInfo, successCallback, failureCallback) {
             var events = [];
             var momentStart = moment(fetchInfo.start);
             var momentEnd = moment(fetchInfo.end);

             while (momentStart <= momentEnd) {
                 var eventData = getColorAndTooltip(momentStart);
                 events.push({
					title: momentStart.format('D'),
					start: momentStart.format('YYYY-MM-DD'),
					backgroundColor: eventData.color, 
					  allDay: true,
					extendedProps: { tooltip: eventData.tooltip } // Add tooltip here
				});
                 momentStart.add(1, 'days');
             }

             successCallback(events);
         },
         // Additional FullCalendar options as needed
         eventDidMount: function(info) {
             new bootstrap.Tooltip(info.el, {
                 title: info.event.extendedProps.tooltip,
                 placement: 'top',
                 trigger: 'hover',
                 container: 'body'
             });
         }
     });
		

     calendar.render();
        });
    </script>

<script>
const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
</script>

<script>
    // Bootstrap form validation script
    (function () {
        'use strict';
        window.addEventListener('load', function () {
            var forms = document.getElementsByClassName('needs-validation');
            var validation = Array.prototype.filter.call(forms, function (form) {
                form.addEventListener('submit', function (event) {
                    if (form.checkValidity() === false) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        }, false);
    })();
</script>

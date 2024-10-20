<?php
// Set the default timezone to your desired timezone
date_default_timezone_set('Europe/Zagreb');

// Get the current day of the month
$currentDayOfMonth = date('d');
$currentYear = date('Y');

// Determine the preselected month based on the current day of the month
if ($currentDayOfMonth > 5) {
    $preselectedMonth = date('n'); // Current month
} else {
    $preselectedMonth = date('n', strtotime('-1 month')); // Previous month
}

// Array of Croatian month names
$months = array(
    1 => 'Siječanj',
    2 => 'Veljača',
    3 => 'Ožujak',
    4 => 'Travanj',
    5 => 'Svibanj',
    6 => 'Lipanj',
    7 => 'Srpanj',
    8 => 'Kolovoz',
    9 => 'Rujan',
    10 => 'Listopad',
    11 => 'Studeni',
    12 => 'Prosinac'
);
?>



<div class="container">
	<div class="row">
		<h1 class="text-center mt-4">Kreiraj uskratu na plaći za vozača <?= $driver['vozac'] ?></h1>
		<?php if($placaDugRazlika < 0): ?>
		<h2 class="text-center mt-2 border border-danger text-danger"> Dug ukupno iznosi <?= $dug['iznos'] ?> €</h2>
		<h2 class="text-center mt-2 border border-warning text-warning"> Neto plaća  iznosi <?= $netoPlaca ?> €</h2>
		<h2 class="text-center mt-2 border border-danger text-danger"> Neto plaća ne pokriva iznos duga te za podmiriti cijeli dug ostaje <?= $placaDugRazlika?> €</h2>
		<?php else:?>
		<h2 class="text-center mt-2 border border-danger text-danger"> Dug ukupno iznosi <?= $dug['iznos'] ?> €</h2>
		<h2 class="text-center mt-2 border border-success text-success"> Neto plaća  iznosi <?= $netoPlaca ?> €</h2>
		<h2 class="text-center mt-2 border border-success text-success"> Neto plaća pokriva iznos duga te za isplatu ostaje <?= $placaDugRazlika?> €</h2>
		<?php endif ?>
	</div>
	<div class="row">
		<form class="row g-3 needs-validation" action="<?php echo site_url('AdminController/uskrataSave');?>" method="post" novalidate>
			<div class="col-md-4">
				<label for="iznos" class="form-label">Iznos</label>
				<input type="text" name="iznos" class="form-control" value="<?= $uskraceno ?>" required>
				
				<input type="hidden" name="vozac_id" value="<?= $driver['id'] ?>">
				<input type="hidden" name="vozac" value="<?= $driver['vozac'] ?>">
				<input type="hidden" name="adresa" value="<?= $driver['adresa'] ?>">
				<input type="hidden" name="grad" value="<?= $driver['grad'] ?>">
				<input type="hidden" name="drzava" value="<?= $driver['drzava'] ?>">
				<input type="hidden" name="oib" value="<?= $driver['oib'] ?>">
				<input type="hidden" name="postanskiBroj" value="<?= $driver['postanskiBroj'] ?>">
				<input type="hidden" name="radno_mjesto" value="<?= $driver['radno_mjesto'] ?>">
				<input type="hidden" name="netoPlaca" value="<?= $netoPlaca ?>">

				<input type="hidden" name="user" value="<?= $user ?>">

				<input type="hidden" name="dug_id" value="<?= $dug['id'] ?>">
				<input type="hidden" name="dug_iznos" value="<?= $dug['iznos'] ?>">
				<input type="hidden" name="tvrtka_id" value="<?= $tvrtka['id'] ?>">
				
				<div class="invalid-feedback">
					Molimo unesite iznos.
				</div>
			</div>
			<div class="col-md-4">
				<label for="mjesec" class="form-label">Odaberi mjesec za koji se obračunava sljedeća plaća</label>
				<select class="form-select" id="mjesec" name="mjesec" required>
					<?php foreach ($months as $monthNumber => $monthName): ?>
						<option value="<?php echo $monthNumber; ?>" <?php if ($monthNumber == $preselectedMonth) echo 'selected'; ?>><?php echo $monthName; ?></option>
					<?php endforeach; ?>
				</select>
				<div class="invalid-feedback">
					Molimo odaberite mjesec.
				</div>
			</div>
			<div class="col-md-4">
				<label for="godina" class="form-label">Godina</label>
				<input type="text" name="godina" class="form-control" value="<?= $currentYear ?>" required>
				<div class="invalid-feedback">
					Molimo unesite godinu.
				</div>
			</div>
			<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#confirmationModal">
			  Potvrdi
			</button>
		</form>
	</div>
</div>
<div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="confirmationModalLabel">Potvrda podataka</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p class="text-danger">Pritiskom na gumb Potvrdi podaci se unose u bazu podataka, budite oprezni.</p>
        <p>Potvrdite unesene podatke:</p>
        <p><strong>Iznos:</strong> <span id="confirmIznos"></span></p>
        <p><strong>Mjesec:</strong> <span id="confirmMjesec"></span></p>
        <p><strong>Godina:</strong> <span id="confirmGodina"></span></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Izaberi ponovno</button>
        <button type="submit" class="btn btn-primary" id="finalSubmitButton">Potvrdi</button>
      </div>
    </div>
  </div>
</div>

<script>
    $(document).ready(function() {
        // Additional custom validation if needed
        // For example, you may want to validate the format of the year or the amount entered.
        // You can add custom validation logic here using jQuery.

        // Handle form submissions if there are invalid fields
        $('form').submit(function(event) {
            if (!this.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            } else {
                // Form is valid, show the modal for confirmation
                $('#confirmationModal').modal('show');
            }

            $(this).addClass('was-validated');
        });

        // Populate confirmation modal with entered data
        $('#confirmationModal').on('show.bs.modal', function (event) {
            var iznos = $('input[name="iznos"]').val();
            var mjesec = $('select[name="mjesec"]').find('option:selected').text();
            var godina = $('input[name="godina"]').val();

            var modal = $(this);
            modal.find('#confirmIznos').text(iznos);
            modal.find('#confirmMjesec').text(mjesec);
            modal.find('#confirmGodina').text(godina);
        });

        // Handle form submission when confirmed
        $('#finalSubmitButton').click(function() {
            var form = $('form')[0];
            if (form.checkValidity()) {
                form.submit(); // Submit the form if it's valid
            }
        });
    });
</script>

<script>
    $(document).ready(function() {
        // Disable form submissions if there are invalid fields
        $('form').submit(function(event) {
            if (!this.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            $(this).addClass('was-validated');
        });

        // Additional custom validation if needed
        // For example, you may want to validate the format of the year or the amount entered.
        // You can add custom validation logic here using jQuery.
    });
</script>
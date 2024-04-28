<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href='https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/css/intlTelInput.min.css" />
	<!-- JS -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/intlTelInput.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/utils.min.js"></script>
    <title>Registriraj se na našu aplikaciju</title>
</head>
<body>
	<div class="container mt-5">
		<div class="row justify-content-md-center">
			<div class="col-12 col-md-6 col-lg-5">

					<button type="button" id="emailomButton" class="btn btn-light">e-mailom</button>
					<button type="button" id="mobitelomButton" class="btn btn-dark"> Mobitelom</button>
			</div>
		</div>
	</div>
	<div class="container mt-5">
		<div class="row justify-content-md-center">
			<div class="col-12 col-md-6 col-lg-5">
					<?php if (session()->has('msgPoruka')){ ?>
					<div class="alert <?=session()->getFlashdata('alert-class') ?>">
						<?=session()->getFlashdata('msgPoruka') ?>
					</div>
  				<?php } ?>
			</div>
		</div>
	</div>

    <div id="withEmail" class="container mt-5">
        <div class="row justify-content-md-center">
            <div class="col-12 col-md-6 col-lg-5">
                <h2>Registriraj se putem email-a</h2>
                <?php if(isset($validation)):?>
                <div class="alert alert-warning">
                   <?= $validation->listErrors() ?>
                </div>
                <?php endif;?>
				<form action="<?php echo base_url(); ?>/index.php/SignupController/store" method="post">
					<div class="form-group mb-3">
						<label for="name">Ime</label>
						<input type="text" name="name" id="name" value="<?= set_value('name') ?>" class="form-control">
					</div>
					<div class="form-group mb-3">
						<label for="email">Email</label>
						<input type="email" name="email" id="email" value="<?= set_value('email') ?>" class="form-control">
					</div>
					<div class="form-group mb-3">
						<label for="password">Lozinka</label>
						<input type="password" name="password" id="password" class="form-control">
					</div>
					<div class="form-group mb-3">
						<label for="confirmpassword">Potvrdi lozinku</label>
						<input type="password" name="confirmpassword" id="confirmpassword" class="form-control">
					</div>
					<div class="d-grid">
						<button type="submit" class="btn btn-dark">Registriraj se</button>
					</div>
				</form>
			</div>
        </div>
    </div>
    <div id="withMobitel" class="container mt-5">
        <div class="row justify-content-md-center">
            <div class="col-12 col-md-6 col-lg-5">
                <h2>Registriraj se brojem mobitela</h2>
				<h5 class="alert">Potrebno je imati WhatsApp</h5>
              <?php if(isset($validation)):?>
                <div class="alert alert-warning">
                   <?= $validation->listErrors() ?>
                </div>
                <?php endif;?>
                <form action="<?php echo base_url(); ?>/index.php/SignupController/storeMob" method="post">
					<div class="form-group mb-3">
						<label for="name">Ime</label>
						<input type="text" name="name" id="name" value="<?= set_value('name') ?>" class="form-control">
					</div>
					<div class="form-group mb-3">
						<label for="phone">Phone Number</label>
						<input type="text" id="phone" name="phone_number" id="phone" class="form-control" autocomplete="off">
					</div>
					<div class="form-group mb-3">
						<label for="password">Lozinka</label>
						<input type="password" name="password" id="password" class="form-control">
					</div>
					<div class="form-group mb-3">
						<label for="confirmpassword">Potvrdi lozinku</label>
						<input type="password" name="confirmpassword" id="confirmpassword" class="form-control">
					</div>
					<div class="d-grid">
						<button type="submit" class="btn btn-dark">Registriraj se</button>
					</div>
				</form>
            </div>
        </div>
    </div>
	
<script>
    var input = document.querySelector("#phone");
    var iti = window.intlTelInput(input, {
        initialCountry: "auto",
        geoIpLookup: function(success, failure) {
            $.get("https://ipinfo.io", function() { }, "jsonp").always(function(resp) {
                var countryCode = (resp && resp.country) ? resp.country : "us";
                success(countryCode);
            });
        },
    });

    // Listen to the input event and update the input field value automatically
    input.addEventListener('input', function() {
        input.value = iti.getNumber();
    });
</script>
<script>
    // JavaScript to show/hide registration forms based on button clicks
    const emailomButton = document.getElementById("emailomButton");
    const mobitelomButton = document.getElementById("mobitelomButton");
    const withEmail = document.getElementById("withEmail");
    const withMobitel = document.getElementById("withMobitel");
   // Initially, show the email registration form and hide the phone registration form
        withEmail.style.display = "block";
        withMobitel.style.display = "none";
    emailomButton.addEventListener("click", () => {
        withEmail.style.display = "block";
        withMobitel.style.display = "none";

        // Add btn-light class to the active button
        emailomButton.classList.add("btn-light");
        emailomButton.classList.remove("btn-dark");

        // Remove btn-light class from the other button
        mobitelomButton.classList.remove("btn-light");
        mobitelomButton.classList.add("btn-dark");
    });

    mobitelomButton.addEventListener("click", () => {
        withEmail.style.display = "none";
        withMobitel.style.display = "block";

        // Add btn-light class to the active button
        mobitelomButton.classList.add("btn-light");
        mobitelomButton.classList.remove("btn-dark");

        // Remove btn-light class from the other button
        emailomButton.classList.remove("btn-light");
        emailomButton.classList.add("btn-dark");
    });
</script>	
</body>
</html>

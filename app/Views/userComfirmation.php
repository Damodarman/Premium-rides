    <div class="container mt-5">
        <div class="row justify-content-md-center">
            <div class="col-12 col-md-6 col-lg-5">
                <h2>Potvrdi broj mobitela</h2>
                <?php if(isset($validation)):?>
                <div class="alert alert-warning">
                   <?= $validation->listErrors() ?>
                </div>
                <?php endif;?>
				<div id="countdown" data-initial-time="<?= $time ?>"></div>
				<form id="ponovoPosalji" action="<?php echo base_url(); ?>/index.php/SignupController/noviKod" method="post" style="display: none;">
					<input type="hidden" name="user_id" value="<?= $user_id ?>">
					<div class="d-grid">
						<button type="submit" class="btn btn-dark">Potvrdi broj</button>
					</div>
				</form>

				<form action="<?php echo base_url(); ?>/index.php/SignupController/comfirmNumber" method="post">
					<div class="form-group mb-3">
						<label for="name">Ime</label>
						<input disabled type="text" name="name" id="name" value="<?= $name ?>" class="form-control">
					</div>
					<div class="form-group mb-3">
						<label for="phone">Broj mobitela</label>
						<input disabled name="phone" id="phone" value="<?= $phone ?>" class="form-control">
					</div>
					<div class="form-group mb-3">
						<label for="token">Kod</label>
						<input name="token" id="token" class="form-control">
					</div>
					<div class="d-grid">
						<button type="submit" class="btn btn-dark">Potvrdi broj</button>
					</div>
				</form>
			</div>
        </div>
    </div>
<script>
// Get the initial time from the data attribute
const initialTime = document.getElementById('countdown').getAttribute('data-initial-time');
const targetTime = new Date(initialTime).getTime() + 4 * 60 * 1000; // Add 4 minutes in milliseconds

function updateCountdown() {
  const currentTime = new Date().getTime();
  const timeLeft = targetTime - currentTime;

  if (timeLeft <= 0) {
    document.getElementById('countdown').innerHTML = 'Kod je istekao';
	  document.getElementById('ponovoPosalji').style.display = 'block';
  } else {
    const minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
    const seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);
    const countdownSentence = `Preostalo je još ${minutes} minuta i ${seconds} sekundi do isteka koda.`;
    document.getElementById('countdown').innerHTML = countdownSentence;
  }
}

// Update the countdown every second
setInterval(updateCountdown, 1000);

// Initial call to update the countdown immediately
updateCountdown();
</script>
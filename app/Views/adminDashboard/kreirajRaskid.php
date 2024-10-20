<div class="container">
	<div class="row">
		<div class="alert alert-danger text-center fs-1" role="alert">
		  Molimo te da oprezno popunjavaš sljedeće podatke
		</div>
		<div class="alert alert-danger text-center fs-4" role="alert">
		  Obavezno završi proces jer je već poslan zahtjev za odjavom
		</div>
	</div>
	<div class="row">
	<h1 class="text-center">Raskid ugovora o radu za vozača/icu <?php echo $driver['vozac'] ?></h1>
		
		<form class="row g-3" action="<?php echo site_url('AdminController/raskidUgovora');?>" method="post">
			<div class="form-group">
				<label for="vrstaRaskida">Vrsta otkaza ugovora</label>
				   <select class="form-control" id="vrstaRaskida" name="vrstaRaskida">
					   <option>Molimo odaberite s popisa</option>
					  <option value="0">Sporazumni otkaz ugovora o radu</option>
					  <option value="1">Redoviti otkaz ugovora od strane radnika</option>
					  <option value="2">Redoviti otkaz ugovora od strane poslodavca</option>
					  <option value="3">Izvanredni otkaz ugovora o radu</option>
					</select>
			  </div>
			  <div class="form-group d-none" id="option2">
				<label for="redovitiOtkaz">Razlog redovitog otkaza ugovora o radu</label>
				   <select class="form-control" id="redovitiOtkaz" name="redovitiOtkaz">
					  <option>Molimo odaberite s popisa</option>
					  <option value="0">Radnik se nije odazvao na poziv poslodavca</option>
					  <option value="1">Radnik je nedosljedan pri pojavljivanju na poslu</option>
					  <option value="2">Radnik neodgovorno troši radno vrijeme</option>
					</select>
			  </div>
			  <div class="form-group d-none" id="option3">
				<label for="izvanredniOtkaz">Razlog izvanrednog otkaza ugovora o radu</label>
				   <select class="form-control" id="izvanredniOtkaz" name="izvanredniOtkaz">
					  <option>Molimo odaberite s popisa</option>
					  <option value="0">Radnik nije predao u tvrtku prikupljenu gotovinu</option>
					  <option value="1">Radnik je za vrijeme radnog vremena obavljao nezakonite radnje</option>
					  <option value="2">Radnik se na veoma grub i bezobrazan način odnosio prema kolegama</option>
					</select>
			  </div>
			<input class="d-none" type="text" name="vozac_id" value="<?php echo $driver['id']?>">
			<button type="submit" class="btn btn-primary d-none">Kreiraj</button>
		</form>
	</div>
</div>


<script>
  // Function to handle select change
  function handleSelectChange() {
    const vrstaRaskida = document.getElementById('vrstaRaskida');
    const option2 = document.getElementById('option2');
    const option3 = document.getElementById('option3');
    const redovitiOtkaz = document.getElementById('redovitiOtkaz');
    const izvanredniOtkaz = document.getElementById('izvanredniOtkaz');
    const submitButton = document.querySelector('button[type="submit"]');

    vrstaRaskida.addEventListener('change', function () {
      const selectedValue = vrstaRaskida.value;

      // Hide all option divs initially
      option2.classList.add('d-none');
      option3.classList.add('d-none');

      // Show divs based on selected value
      if (selectedValue === '2') {
        option2.classList.remove('d-none');
      } else if (selectedValue === '3') {
        option3.classList.remove('d-none');
      }

      // Show submit button for values 0 and 1, hide for 2 and 3
      submitButton.classList.toggle('d-none', selectedValue === '2' || selectedValue === '3');
    });

    // Handle select change inside option divs
    redovitiOtkaz.addEventListener('change', function () {
      submitButton.classList.remove('d-none');
    });

    izvanredniOtkaz.addEventListener('change', function () {
      submitButton.classList.remove('d-none');
    });
  }

  // Call the function
  handleSelectChange();
</script>
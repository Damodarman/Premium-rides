
<div class="accordion accordion-flush" id="accordionFlushExample">
<div class="container mt-5">
	<?php if(isset($validation)):?>
	<div class="alert alert-warning">
	   <?= $validation->listErrors() ?>
	</div>
	<?php endif;?>
<?php 		
	$fiks = $postavkeFlote['provizija_fiks'];
	$postotak = $postavkeFlote['provizija_postotak'];
	
?>
	<form class="row g-3" action="<?php echo base_url('index.php/AdminController/addDriverSave');?>" method="post">
		<div class="col-md-6">
			<label for="ime" class="form-label">Ime</label>
			<input type="text" name ="ime" class="form-control" placeholder="Marko">
		  </div>
		  <div class="col-md-6">
			<label for="prezime" class="form-label">Prezime</label>
			<input type="text" name ="prezime" class="form-control" id="prezime" placeholder="Markić">
		  </div>
		<div class="col-md-6">
			<label for="email" class="form-label">Email</label>
			<input type="email" name ="email" class="form-control" id="email" placeholder="marko.markic@gmail.com">
		  </div>
		<div class="col-md-3">
			<label for="mobitel" class="form-label">Broj mobitela</label>
			<input type="text" name ="mobitel" class="form-control" id="mobitel" placeholder="+385945784244">
		  </div>
			<div class="col-md-3">
				<label for="whatsApp" class="form-label">Broj whatsApp</label>
				<input type="text" name ="whatsApp" class="form-control" id="whatsApp" placeholder="+385945784244">
			  </div>
		  <div class="col-6">
			<label for="adresa" class="form-label">Adresa</label>
			<input type="text" name ="adresa" class="form-control" id="adresa" placeholder="Zagrebačka cesta 55">
		  </div>
		  <div class="col-md-6">
			<label for="grad" class="form-label">Grad</label>
			<input type="text" class="form-control" name="grad" placeholder="Zagreb">
		  </div>
		  <div class="col-md-6">
			<label for="postanskiBroj" class="form-label">Poštanski broj</label>
			<input type="text" class="form-control" name="postanskiBroj">
		  </div>
		  <div class="col-md-6">
			<label for="drzava" class="form-label">Država</label>
			<input type="text" class="form-control" name="drzava">
		  </div>
		  <div class="col-md-6">
			<label for="oib" class="form-label">OIB</label>
			<input type="text" class="form-control" name="oib">
		  </div>
		<div class="col-md-6">
			  <label for="datepicker2">Datum rođenja</label>
			<div class="form-floating input-group mb-4">
			  <i class="bi bi-calendar-date input-group-text"></i>
			  <input type="text" name="dob" id="datepicker2" class="form-control" placeholder="DD/MM/YYYY" required>
			</div>
		</div>
			<hr class="hr" />

		  <div class="col-md-12">
			  <label for="inputState" class="form-label">Platforma: </label></br>
			<div class="form-check form-check-inline">
			  <input class="form-check-input" name="uberCheck" type="hidden" id="inlineCheckbox1" value="0">
			  <input class="form-check-input" name="uberCheck" type="checkbox" id="inlineCheckbox1" value="1">
			  <label class="form-check-label" for="inlineCheckbox1">Uber</label>
			</div>
			<div class="form-check form-check-inline">
			  <input class="form-check-input" name="boltCheck" type="hidden" id="inlineCheckbox2" value="0">
			  <input class="form-check-input" name="boltCheck" type="checkbox" id="inlineCheckbox2" value="1">
			  <label class="form-check-label" for="inlineCheckbox2">Bolt</label>
			</div>
			<div class="form-check form-check-inline">
			  <input class="form-check-input" name="taximetarCheck" type="hidden" id="inlineCheckbox3" value="0">
			  <input class="form-check-input" name="taximetarCheck" type="checkbox" id="inlineCheckbox3" value="1">
			  <label class="form-check-label" for="inlineCheckbox3">Taximetar</label>
			</div>
			<div class="form-check form-check-inline">
			  <input class="form-check-input" name="myPosCheck" type="hidden" id="inlineCheckbox4" value="0">
			  <input class="form-check-input" name="myPosCheck" type="checkbox" id="inlineCheckbox4" value="1">
			  <label class="form-check-label" for="inlineCheckbox4">MyPos</label>
			</div>
		</div>
		<hr class="hr" />

		<div class="col-md-3">
			<label for="nacin_rada" class="form-label">Način rada</label>
			<select class="form-select" name="nacin_rada" aria-label="Default select example">
			  <option value ="provizija">Provizija</option>
			  <option value="placa">Plaća</option>
			</select>
	</div>
		<div class="col-md-3">
			<label for="vrsta_provizije" class="form-label">Vrsta provizije</label>
			<select class="form-select" name="vrsta_provizije" aria-label="Default select example">
			  <option value ="Fiksna">Fiksna</option>
			  <option value="Postotak">Postotak</option>
			</select>
		</div>
		<div class="col-md-3">
			<label disabled for="iznos_provizije" class="form-label">Iznos provizije</label>
			<select class="form-select" name="iznos_provizije" aria-label="Default select example">
			  <option value="40">40 €</option>
			  <option value="10">10 %</option>
			</select>
		</div>	
		<div class="col-md-3">
			<label for="popust_na_proviziju" class="form-label">Popust na proviziju</label>
			<select class="form-select" name="popust_na_proviziju" aria-label="Default select example">
				  <option value="0">0 €</option>
				  <option value="7">7 €</option>
				  <option value="13">13 €</option>
				  <option value="1">1 %</option>
				  <option value="2">2 %</option>
				  <option value="3,4">3,4 %</option>
				  <option value="3">3 %</option>
				  <option value="4">4 %</option>
				  <option value="5">5 %</option>
				  <option value="6">6 %</option>
				  <option value="7">7 %</option>
				  <option value="8">8 %</option>
				  <option value="9">9 %</option>
				  <option value="5">5 €</option>
				  <option value="10">10 €</option>
				  <option value="15">15 €</option>
				  <option value="20">20 €</option>
				  <option value="25">25 €</option>
				  <option value="30">30 €</option>
				  <option value="35">35 €</option>
				  <option value="40">40 €</option>
			</select>
		</div>
				  <div class="col-md-3">
				<label for="provizijaNaljepnice" class="form-label">Provizija na naljepnice %</label>
				<input type="text" class="form-control" name="provizijaNaljepnice"  value ="0" placeholder="0-100">
			  </div>
		<div class="col-md-3">
			<label for="popust_na_taximetar" class="form-label">Popust na taximetar</label>
			<select class="form-select" name="popust_na_taximetar" aria-label="Default select example">
			  <option value="0">0 €</option>
			  <option value="1">1 €</option>
			</select>
		</div>
		<div class="col-md-6"></div>
		<hr class="hr" />

	<div class="col-md-3">
			<label for="refered_by" class="form-label">Preporuka od</label>
			<select class="form-select" name="refered_by" aria-label="Default select example">
				<?php   
				if(!empty($drivers)){
					foreach($drivers as $driver){
						echo('<option value="'. $driver['vozac'] . '">' . $driver['vozac'] . '</option>');
					}
				}else{
					echo('<option value="'. $sessData['name'] . '">' . $sessData['name'] . '</option>');
					
				}
				
				?>				
			</select>
		</div>
		<div class="col-md-3">
			<label for="vrsta_nagrade" class="form-label">Vrsta nagrade</label>
			<select class="form-select" name="vrsta_nagrade" aria-label="Default select example">
			  <option value ="jednokratno">Jednokratno</option>
			  <option value="tjedno">Tjedno</option>
			</select>
	</div>
		  <div class="col-md-3">
			<label for="referal_reward" class="form-label">Nagrada preporučitelju</label>
			<input type="text" class="form-control" name="referal_reward" id="referal_reward" value="0" placeholder="13 € ili 3%">
		  </div>
	<div class="col-md-3"></div>


	<hr class="hr" />
		  <div class="col-md-2">
			<label for="pocetakRada" class="form-label">Početak rada </label>
			<input type="text" class="form-control" name="pocetak_rada"  placeholder="<?php echo date('Y-m-d') ?>">
		  </div>
		<div class="col-md-2">
			<label for="prijava" class="form-label">Prijava</label>
			<select class="form-select" name="prijava" aria-label="Default select example">
			  <option value="0"  >NE</option>
			  <option value="1">DA</option>
			</select>
		</div>
		<hr class="hr" />

    <div id="vrstaZaposlenja" class="col-md-2">
        <label for="vrsta_zaposlenja" class="form-label">Vrsta zaposlenja</label>
        <select class="form-select" name="vrsta_zaposlenja" aria-label="Default select example" id="vrsta_zaposlenja">
            <option value="neodredeno" selected>Neodređeno</option>
            <option value="odredeno">Određeno</option>
        </select>
    </div>
    <div class="col-md-2">
        <label for="pocetakPrijave" class="form-label">Početak prijave </label>
        <input type="text" class="form-control" name="pocetak_prijave" placeholder="YYYY-MM-DD">
    </div>
    <div id="krajPrijave" class="col-md-2">
        <label for="kraj_prijave" class="form-label">Kraj prijave </label>
        <input type="text" class="form-control" name="kraj_prijave" placeholder="YYYY-MM-DD">
    </div>
	
	<div class="col-md-2">
					<label for="radniOdnos" class="form-label">Radni odnos</label>
					<select class="form-select" name="radniOdnos" aria-label="Default select example">
					<option value ="obrtnik"> Obrtnik </option>
					<option value ="ugovor" selected > Ugovor o radu</option>
					<option value ="student"> Studentski ugovor </option>
					<option value ="obrtnik_sa_agregatorom"> Obrtnik sa Agregatorom </option>
					</select>
				</div>		
		<div class="col-md-2">
			<label for="broj_sati" class="form-label">Broj sati</label>
			<select class="form-select" name="broj_sati" aria-label="Default select example">
			<option value ="2" > 2 </option>
			<option value ="1.5" > 1,5 </option>
			<option value ="4"> 4 </option>
			<option value ="6"> 6 </option>
			<option value ="8"> 8 </option>
			</select>
		</div>
		<hr class="hr" />

		  <div class="col-md-6">
			<label for="radno_mjesto" class="form-label">Radno mjesto</label>
			<input type="text" class="form-control" name="radno_mjesto" id="radno_mjesto" value="Taxi Vozač">
		  </div>
		<div class="col-md-2">
			<label for="aktivan" class="form-label">Aktivan</label>
			<select class="form-select" name="aktivan" aria-label="Default select example">
			  <option value="0" >NE</option>
			  <option value="1"  >DA</option>
			</select>
		</div>
		<hr class="hr" />
		<div class="col-md-3">
			<label for="isplata" class="form-label">Isplata na </label>
			<select class="form-select" name="isplata" aria-label="Default select example">
			  <option value="hrIBAN" >HR IBAN</option>
			  <option value="Revolut"  >Revolut</option>
			</select>
		</div>

			  <div class="col-md-3">
				<label for="IBAN" class="form-label">IBAN računa za isplatu </label>
				<input type="text" class="form-control" name="IBAN"  placeholder="HR...../LT....">
			  </div>
			  <div class="col-md-3">
				<label for="zasticeniIBAN" class="form-label">IBAN zaštičenog računa za isplatu </label>
				<input type="text" class="form-control" name="zasticeniIBAN"  placeholder="HR...../LT....">
			  </div>
			  <div class="col-md-3">
				<label for="strani_IBAN" class="form-label">IBAN Stranog Računa za isplatu(REVOLUT) </label>
				<input type="text" class="form-control" name="strani_IBAN"  placeholder="LT....">
			  </div>
		<hr class="hr" />

			  <div class="col-md-2">
				<label for="blagMin" class="form-label">Blagajnički minimum </label>
				<input type="text" class="form-control" name="blagMin"  placeholder="30">
			  </div>
			  <div class="col-md-2">
				<label for="blagMax" class="form-label">Blagajnički maksimum </label>
				<input type="text" class="form-control" name="blagMax"  placeholder="700">
			  </div>

		<div class="d-grid gap-2">
	  <button type="submit" class="btn btn-primary">Dodaj Vozača</button>
</div>

	</form>
</div>
</div>

<script>
    // Function to check and toggle visibility of 'krajPrijave'
    function toggleKrajPrijave() {
        var vrstaZaposlenjaSelect = document.getElementById('vrsta_zaposlenja');
        var krajPrijaveDiv = document.getElementById('krajPrijave');

        // Get the selected value from the 'vrsta_zaposlenja' select
        var selectedValue = vrstaZaposlenjaSelect.value;

        // Toggle visibility based on the selected value
        if (selectedValue === 'neodredeno') {
            krajPrijaveDiv.style.display = 'none';
        } else {
            krajPrijaveDiv.style.display = 'block';
        }
    }

    // Add an event listener to the 'vrsta_zaposlenja' select element
    document.getElementById('vrsta_zaposlenja').addEventListener('change', toggleKrajPrijave);

    // Initial check when the page loads
    toggleKrajPrijave();
</script>

<script>
  document.addEventListener("DOMContentLoaded", function () {
    // Get references to the select elements
    const vrstaProvizijeSelect = document.querySelector("select[name='vrsta_provizije']");
    const iznosProvizijeSelect = document.querySelector("select[name='iznos_provizije']");
	  const fiks = <?php echo $fiks; ?>;
	const postotak = <?php echo $postotak; ?>;
			// Add an event listener to the vrsta_provizije select element
    vrstaProvizijeSelect.addEventListener("change", function () {
      // Check the selected option
      const selectedOption = vrstaProvizijeSelect.value;

      // Update the iznos_provizije select element based on the selected option
      if (selectedOption === "Fiksna") {
        iznosProvizijeSelect.value = fiks;
      } else if (selectedOption === "Postotak") {
        iznosProvizijeSelect.value = postotak;
      }
    });
  });
</script>


<!-- Bootstrap 5 JavaScript Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>

<!-- Vanilla Datepicker JS -->
<script src='https://cdn.jsdelivr.net/npm/vanillajs-datepicker@1.1.4/dist/js/datepicker-full.min.js'></script>
<script>
$(document).ready(function() {
    // Initialize datepicker with 'dd/mm/yyyy' format for specific element
    $('#datepicker2').datepicker({
        format: 'dd/mm/yyyy',
        autoclose: true,
		beforeShow: function(input, inst) {
        // Adjust the size of the datepicker here
        inst.dpDiv.css({ fontSize: '12px' });
    }
    }).on('show', function(e) {
        // Increase z-index to make sure the datepicker appears on top
        $('.datepicker').css('z-index', 9999);
    });

    // Initialize datepicker with 'yyyy-mm-dd' format for other elements
    $('.other-datepicker').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true,
		beforeShow: function(input, inst) {
        // Adjust the size of the datepicker here
        inst.dpDiv.css({ fontSize: '12px' });
    }
    }).on('show', function(e) {
        // Increase z-index to make sure the datepicker appears on top
        $('.datepicker').css('z-index', 9999);
    });

    // Helper function to format the date as "yyyy-mm-dd"
    function formatDate(date) {
        var year = date.getFullYear();
        var month = date.getMonth() + 1;
        var day = date.getDate();
        return year + '-' + (month < 10 ? '0' : '') + month + '-' + (day < 10 ? '0' : '') + day;
    }
});
</script>

</body>
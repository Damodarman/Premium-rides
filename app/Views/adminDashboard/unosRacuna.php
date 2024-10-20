<div class="container">
    <div class="row">

        <div class="col-lg-12 col-md-6 col-sm-4">
			
	<?php if(session()->has('msgRacuni')){ ?>
		<div class="alert <?=session()->getFlashdata('alert-class') ?>">
			<?=session()->getFlashdata('msgRacuni') ?>
			<?php } ?>
		</div>
	<?php if(session()->has('msgProdajnoMjesto')){ ?>
		<div class="alert <?=session()->getFlashdata('alert-class') ?>">
			<?=session()->getFlashdata('msgProdajnoMjesto') ?>
			<?php } ?>
		</div>
			
            <form  action="<?php echo site_url('UlazniRacuniController/saveRacuna');?>" method="post">
                <div class="row">
                            <input type="hidden" value="0" class="form-control" name="tip_retka" id="tip_retka">
                            <input type="hidden" value="UFA" class="form-control" name="klasa" id="klasa">
                            <input type="hidden" value = "<?php echo $brojDokumenta ?>" class="form-control" name="broj_dokumenta" id="broj_dokumenta">
                            <input type="hidden" value= "" class="form-control" name="Originalni_vezni_broj_dokumenta" id="Originalni_vezni_broj_dokumenta">
                            <input type="hidden" value="" class="form-control" name="skladište" id="skladište">
                            <input type="hidden" value="" class="form-control" name="skladište_2" id="skladište_2">
							<div class="col-lg-3 col-md-6 col-sm-12 mb-3">
								<label for="datum_dokumenta" class="form-label">Datum računa</label>
								<div class="input-group date">
									<input type="text" class="form-control" name="datum_dokumenta" id="datum_dokumenta">
									<span class="input-group-text"><i class="bi bi-calendar"></i></span>
								</div>
							</div>
                            <input type="hidden" class="form-control" name="datum_računa" id="datum_racuna">
                            <input type="hidden" class="form-control" name="datum_dospijeća" id="datum_dospijeca">
                                            <div class="col-lg-3 col-md-6 col-sm-12 mb-3">
                            <label for="komitent_id" class="form-label">Odaberi Trgovca</label>
                            <select class="form-select" name="komitent_id" id="komitent_id" required>
								<option > Odaberi sa liste</option>
								<?php foreach ($trgovci as $data) : ?>
									<option value="<?php echo $data['id']; ?>"><?php echo $data['nazivTrgovca']; ?></option>
								<?php endforeach; ?>
							</select>
                        </div>
							<input type="hidden" class="form-control" name="komitent_naziv" id="komitent_naziv" value="">
							<input type="hidden" class="form-control" name="komitent_adresa1" id="komitent_adresa1" value="">
							<input type="hidden" class="form-control" name="komitent_grad" id="komitent_grad" value="">
							<input type="hidden" class="form-control" name="komitent_porezni_broj" id="komitent_porezni_broj" value="">
                            <input type="hidden" value="" class="form-control" name="komitent_adresa2" id="komitent_adresa2">
                            <input type="hidden" value="" class="form-control" name="komitent_žiro_račun" id="komitent_žiro_račun">
                            <input type="hidden" value="" class="form-control" name="komitent_telefon" id="komitent_telefon">
                            <input type="hidden" value="" class="form-control" name="komitent_fax" id="komitent_fax">
                            <input type="hidden" value="1" class="form-control" name="međunarodna_oznaka_devize" id="međunarodna_oznaka_devize">
                            <input type="hidden" value="1" class="form-control" name="tečaj_devize" id="tečaj_devize">
                            <input type="hidden" value="0" class="form-control" name="tečaj_devize_za_carinu" id="tečaj_devize_za_carinu">
                                            <div class="col-lg-3 col-md-6 col-sm-12 mb-3">
                            <label for="devizni_iznos_dokumenta" class="form-label">Iznos bez PDV-a</label>
                            <input type="text" class="form-control" name="devizni_iznos_dokumenta" id="devizni_iznos_dokumenta" required>
                        </div>
                            <input type="hidden" value="0" class="form-control" name="neto_devizni_iznos_dokumenta" id="neto_devizni_iznos_dokumenta">
                            <input type="hidden" value="0" class="form-control" name="nabavni_iznos_robe" id="nabavni_iznos_robe">
                            <input type="hidden" value="0" class="form-control" name="iznos_marže" id="iznos_marže">
                            <input type="hidden" value="" class="form-control" name="iznos_robe_bez_PDV" id="iznos_robe_bez_PDV">
                            <input type="hidden" value="" class="form-control" name="iznos_robe_s_PDV-om" id="iznos_robe_s_PDV-om">
                            <input type="hidden" value="0" class="form-control" name="iznos_usluge_bez_PDV" id="iznos_usluge_bez_PDV">
                            <input type="hidden" value="0" class="form-control" name="iznos_usluge_s_PDV" id="iznos_usluge_s_PDV">
                            <input type="hidden" value="0" class="form-control" name="ukupan_iznos_rabata" id="ukupan_iznos_rabata">
                            <input type="hidden" value="" class="form-control" name="iznos_računa_s_PDV" id="iznos_racuna_s_PDV">
                            <input type="hidden" value="" class="form-control" name="ukupan_iznos_PDV" id="ukupan_iznos_PDV">
                            <input type="hidden" value="0" class="form-control" name="osnovica_poreza_na_luksuz" id="osnovica_poreza_na_luksuz">
                            <input type="hidden" value="0" class="form-control" name="iznos_poreza_na_luksuz" id="iznos_poreza_na_luksuz">
                            <input type="hidden" value="0" class="form-control" name="osnovica_poreza_na_potrošnju" id="osnovica_poreza_na_potrošnju">
                            <input type="hidden" value="0" class="form-control" name="iznos_poreza_na_potrošnju" id="iznos_poreza_na_potrošnju">
                            <input type="hidden" value="0" class="form-control" name="osnovica_za_tarifni_broj_1" id="osnovica_za_tarifni_broj_1">
                            <input type="hidden" value="0" class="form-control" name="iznos_poreza_za_tarifni_broj_1" id="iznos_poreza_za_tarifni_broj_1">
                            <input type="hidden" value="0" class="form-control" name="osnovica_za_tarifni_broj_2" id="osnovica_za_tarifni_broj_2">
                            <input type="hidden" value="0" class="form-control" name="iznos_poreza_za_tarifni_broj_2" id="iznos_poreza_za_tarifni_broj_2">
                            <input type="hidden" value="0" class="form-control" name="osnovica_za_tarifni_broj_3" id="osnovica_za_tarifni_broj_3">
                            <input type="hidden" value="0" class="form-control" name="iznos_poreza_za_tarifni_broj_3" id="iznos_poreza_za_tarifni_broj_3">
                            <input type="hidden" value="0" class="form-control" name="osnovica_za_tarifni_broj_5" id="osnovica_za_tarifni_broj_5">
                            <input type="hidden" value="0" class="form-control" name="iznos_poreza_za_tarifni_broj_5" id="iznos_poreza_za_tarifni_broj_5">
                            <input type="hidden" value="0" class="form-control" name="povratna_naknada" id="povratna_naknada">
                            <input type="hidden" value="1" class="form-control" name="način_obračuna_PDV-a_za_ulazne_račune" id="način_obračuna_PDV-a_za_ulazne_račune">
                            <input type="hidden" value="1" class="form-control" name="način_obračuna_PDV-a_za_izlazne_račune" id="način_obračuna_PDV-a_za_izlazne_račune">
                            <input type="hidden" value="1" class="form-control" name="sredstvo_plaćanja" id="sredstvo_plaćanja">
                            <input type="hidden" value="" class="form-control" name="komercijalist" id="komercijalist">
                            <input type="hidden" value="" class="form-control" name="cassa_sconto" id="cassa_sconto">
                            <input type="hidden" value="" class="form-control" name="način_otpreme" id="način_otpreme">
                            <input type="hidden" value="" class="form-control" name="poziv_na_broj" id="poziv_na_broj">
                            <input type="hidden" value="" class="form-control" name="kratki_opis_napomena_dokumenta" id="kratki_opis_napomena_dokumenta">
                                            <div class="col-lg-3 col-md-6 col-sm-12 mb-3">
                            <label for="dodatni_opis_napomena_dokumenta" class="form-label">Broj Računa</label>
                            <input type="text" class="form-control" name="dodatni_opis_napomena_dokumenta" id="dodatni_opis_napomena_dokumenta" required>
                        </div>
                                            <div class="col-lg-3 col-md-6 col-sm-12 mb-3">
                            <label for="konto_s_dokumenta" class="form-label">Šifra troška</label>
                            <input type="text" value="4077" class="form-control" name="konto_s_dokumenta" id="konto_s_dokumenta">
                        </div>
                            <input type="hidden" value="" class="form-control" name="mjesto_troška_s_dokumenta" id="mjesto_troška_s_dokumenta">
                            <input type="hidden" value="" class="form-control" name="dimenzija_1_s_dokumenta" id="dimenzija_1_s_dokumenta">
                            <input type="hidden" value="" class="form-control" name="dimenzija_2_s_dokumenta" id="dimenzija_2_s_dokumenta">
                            <input type="hidden" value="" class="form-control" name="dimenzija_3_s_dokumenta" id="dimenzija_3_s_dokumenta">
                            <input type="hidden" value="0" class="form-control" name="osnovica_za_tarifni_broj_4" id="osnovica_za_tarifni_broj_4">
                            <input type="hidden" value="0" class="form-control" name="iznos_poreza_za_tarifni_broj_4" id="iznos_poreza_za_tarifni_broj_4">
                            <input type="hidden" value="" class="form-control" name="naš_broj" id="naš_broj">
                            <input type="hidden" value="" class="form-control" name="poštanski_broj" id="poštanski_broj">
                            <input type="hidden" value="191" class="form-control" name="međunarodna_šifra_države" id="međunarodna_šifra_države">
                            <input type="hidden" value="HR" class="form-control" name="međunarodna_oznaka_države" id="međunarodna_oznaka_države">
                            <input type="hidden" value="" class="form-control" name="konto_kupca" id="konto_kupca">
                            <input type="hidden" value="" class="form-control" name="konto_dobavljača" id="konto_dobavljača">
                            <input type="hidden" value="4077" class="form-control" name="konto_troška" id="konto_troška">
                            <input type="hidden" value="" class="form-control" name="datum_kreiranja" id="datum_kreiranja">
                            <input type="hidden" value="" class="form-control" name="vrijeme_kreiranja" id="vrijeme_kreiranja">
                            <input type="hidden" value="" class="form-control" name="datum_izmjene" id="datum_izmjene">
                            <input type="hidden" value="" class="form-control" name="vrijeme_izmjene" id="vrijeme_izmjene">
                            <input type="hidden" value="" class="form-control" name="datum_odobravanja" id="datum_odobravanja">
                            <input type="hidden" value="" class="form-control" name="vrijeme_odobravanja" id="vrijeme_odobravanja">
                            <input type="hidden" value="0" class="form-control" name="nepriznata_osnovica_za_tarifni_broj_1" id="nepriznata_osnovica_za_tarifni_broj_1">
                            <input type="hidden" value="0" class="form-control" name="nepriznati_iznos_poreza_za_tarifni_broj_1" id="nepriznati_iznos_poreza_za_tarifni_broj_1">
                            <input type="hidden" value="0" class="form-control" name="nepriznata_osnovica_za_tarifni_broj_3" id="nepriznata_osnovica_za_tarifni_broj_3">
                            <input type="hidden" value="0" class="form-control" name="nepriznati_iznos_poreza_za_tarifni_broj_3" id="nepriznati_iznos_poreza_za_tarifni_broj_3">
                            <input type="hidden" value="0" class="form-control" name="nepriznata_osnovica_za_tarifni_broj_4" id="nepriznata_osnovica_za_tarifni_broj_4">
                            <input type="hidden" value="0" class="form-control" name="nepriznati_iznos_poreza_za_tarifni_broj_4" id="nepriznati_iznos_poreza_za_tarifni_broj_4">
                            <input type="hidden"  class="form-control" name="broj_JCD" id="broj_JCD">
                            <input type="hidden"  class="form-control" name="datum_JCD" id="datum_JCD">
                            <input type="hidden" value="" class="form-control" name="osnovica_za_tarifni_broj_6" id="osnovica_za_tarifni_broj_6">
                            <input type="hidden" value="" class="form-control" name="iznos_poreza_za_tarifni_broj_6" id="iznos_poreza_za_tarifni_broj_6">
                            <input type="hidden" value="0" class="form-control" name="nepriznata_osnovica_za_tarifni_broj_6" id="nepriznata_osnovica_za_tarifni_broj_6">
                            <input type="hidden" value="0" class="form-control" name="nepriznati_iznos_poreza_za_tarifni_broj_6" id="nepriznati_iznos_poreza_za_tarifni_broj_6">
                            <input type="hidden" value="0" class="form-control" name="osnovica_za_tarifni_broj_7" id="osnovica_za_tarifni_broj_7">
                            <input type="hidden" value="0" class="form-control" name="iznos_poreza_za_tarifni_broj_7" id="iznos_poreza_za_tarifni_broj_7">
                            <input type="hidden" value="" class="form-control" name="nepriznata_osnovica_za_tarifni_broj_7" id="nepriznata_osnovica_za_tarifni_broj_7">
                            <input type="hidden" value="" class="form-control" name="nepriznati_iznos_poreza_za_tarifni_broj_7" id="nepriznati_iznos_poreza_za_tarifni_broj_7">
                            <input type="hidden" value="" class="form-control" name="broj_paragon_bloka" id="broj_paragon_bloka">
                            <input type="hidden" value="" class="form-control" name="zaštitni_kod_izdavatelja" id="zaštitni_kod_izdavatelja">
                            <input type="hidden" value="" class="form-control" name="jedinstveni_identifikator_računa" id="jedinstveni_identifikator_računa">
                            <input type="hidden" value="" class="form-control" name="fiskalni_broj_računa" id="fiskalni_broj_računa">
                            <input type="hidden" value="" class="form-control" name="vrijeme_izdavanja_računa" id="vrijeme_izdavanja_računa">
                            <input type="hidden" value="0" class="form-control" name="broj_narudžbe" id="broj_narudžbe">
                            <input type="hidden" value="0" class="form-control" name="datum_narudžbe" id="datum_narudžbe">
                            <input type="hidden" value="0" class="form-control" name="osnovica_za_tarifni_broj_8" id="osnovica_za_tarifni_broj_8">
                            <input type="hidden" value="0" class="form-control" name="iznos_poreza_za_tarifni_broj_8" id="iznos_poreza_za_tarifni_broj_8">
                            <input type="hidden" value="" class="form-control" name="nepriznata_osnovica_za_tarifni_broj_8" id="nepriznata_osnovica_za_tarifni_broj_8">
                            <input type="hidden" value="" class="form-control" name="nepriznati_iznos_poreza_za_tarifni_broj_8" id="nepriznati_iznos_poreza_za_tarifni_broj_8"> 
                            <input type="hidden" value="" class="form-control" name="plaćeni_iznos" id="plaćeni_iznos">
                            <input type="hidden" value="" class="form-control" name="datum_plaćanja" id="datum_plaćanja">
                            <input type="hidden" value="" class="form-control" name="izvod" id="izvod">
                            <input type="hidden" value="" class="form-control" name="dodatni_opis_ili_napomena_dokumenta" id="dodatni_opis_ili_napomena_dokumenta">
                            <input type="hidden" value="" class="form-control" name="oznaka_procesa" id="oznaka_procesa">
                            <input type="hidden" value="" class="form-control" name="datum_ugovora" id="datum_ugovora">
                            <input type="hidden" value="" class="form-control" name="broj_ugovora" id="broj_ugovora">
                            <input type="hidden" value="" class="form-control" name="primatelj_plaćanja" id="primatelj_plaćanja">
                            <input type="hidden" value="" class="form-control" name="konto_prihoda" id="konto_prihoda">
                            <input type="hidden" value="" class="form-control" name="model_plaćanja" id="model_plaćanja">
                            <input type="hidden" value="" class="form-control" name="referentni_broj_kupca" id="referentni_broj_kupca">
                                    </div>

                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#devizni_iznos_dokumenta').on('input', function() {
            var inputValue = $(this).val();
            var dotValue = inputValue.replace(',', '.');
            $(this).val(dotValue);
        });
    });
</script>



<script>
    $(document).ready(function() {
        // Initialize datepicker
        $('#datum_dokumenta').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true
        });

        // Autofill other inputs
       $('#datum_dokumenta').on('change', function() {
			var datumDokumenta = $(this).val();

			// Autofill inputs with "datum" in their name
			$('#datum_racuna').val(datumDokumenta);
			$('#datum_dospijeca').val(datumDokumenta);
			$('#datum_kreiranja').val(datumDokumenta);
		});
    });
</script>
<script>
    $(document).ready(function() {
        // Listen for change event on the select input
        $('#komitent_id').on('change', function() {
            var selectedOption = $(this).val(); // Get the selected option value

            // Find the corresponding data object based on the selected option value
            var selectedData = <?php echo json_encode($trgovci); ?>.find(function(data) {
                return data.id == selectedOption;
            });

            // Populate the hidden input fields with the corresponding values
            $('#komitent_naziv').val(selectedData.nazivTrgovca);
            $('#komitent_adresa1').val(selectedData.adresaTrgovca);
            $('#komitent_grad').val(selectedData.gradTrgovca);
            $('#komitent_porezni_broj').val(selectedData.porezniBrojTrgovca);
			$('#poštanski_broj').val(selectedData.postanskiBroj);
			
			
        });

        
    });
</script>

<script>
    $(document).ready(function() {
        $('#devizni_iznos_dokumenta').on('input', function() {
            var inputVal = $(this).val(); // Get the user input value

            // Populate the hidden input fields with the corresponding values
            $('#iznos_robe_bez_PDV').val(inputVal);
            $('#iznos_robe_s_PDV-om').val(calculateIznosRobeSPDV(inputVal));
			$('#iznos_racuna_s_PDV').val(calculateIznosRobeSPDV(inputVal));
			$('#ukupan_iznos_PDV').val(calculateIznosPDV(inputVal));
			$('#osnovica_za_tarifni_broj_6').val(inputVal);
			$('#iznos_poreza_za_tarifni_broj_6').val(calculateIznosPDV(inputVal));
			$('#nepriznata_osnovica_za_tarifni_broj_8').val(calculateIznosRobeSPDV(inputVal));
        });
		function calculateIznosPDV(inputVal) {
			var iznosPDV = parseFloat(inputVal) * 0.25;
			return iznosPDV.toFixed(2);
		}

        function calculateIznosRobeSPDV(inputVal) {
            var iznosRobeSPDV = parseFloat(inputVal) * 1.25; // Perform the necessary calculation
            return iznosRobeSPDV.toFixed(2); // Return the calculated value rounded to 2 decimal places
        }
    });
</script>

<script>
  $(document).ready(function() {
    $('#konto_s_dokumenta').on('input', function() {
      var newValue = $(this).val(); // Get the new value from the visible field
      $('#konto_troška').val(newValue); // Update the hidden field with the new value
    });
  });
</script>





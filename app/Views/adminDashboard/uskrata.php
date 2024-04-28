<div class="container">
	<div class="row">
        <?php if (session()->has('dugPlacen')){ ?>
            <div class="alert <?=session()->getFlashdata('alert-class') ?>">
                <?=session()->getFlashdata('dugPlacen') ?>
            </div>
        <?php } ?>
		
		<button class="btn btn-primary btn-lg m-3" onclick="printDiv()">Print Suglasnost za Uskratu</button> 

	</div>
</div>
<div class="container"  id="izjava">
	<div class="row">
			<div class="col-1">
			</div>
			<div class="col-10 fs-4" >
				</br></br></br></br>
			<p class="text-center">
		<?php echo $tvrtka['naziv'] ?>, <?php echo $tvrtka['adresa'] ?>, <?php echo $tvrtka['grad'] ?>, OIB: <?php echo $tvrtka['OIB'] ?>. zastupan po, <?php echo $tvrtka['direktor'] ?> (u daljnjem tekstu: Poslodavac), s jedne strane i </br>
		<?php echo $driver['vozac'] ?>, <?php echo $driver['adresa'] ?>, iz <?php echo $driver['grad'] ?>, OIB: <?php echo $driver['oib'] ?>, (u dalinjem tekstu: Zaposlenik), s druge strane usuglasili su dana: <?php echo $datum ?> sljedeću: </p></br></br></br>
		<h3 class="text-center">SUGLASNOST ZAPOSLENIKA ZA USKRATU NA PLAĆI</h4></br></br></br></br>
<p class="text-center"> 
Ovom suglasnosti Zaposlenik dozvoljava poslodavcu da, prema odredbi članka 96. Zakona o radu (Narodne novine, br. 93/14, 127/17, 98/19 i 151/22), namiri svoja potraživanja od Zaposlenika koja su nastala u mjesecu <?=$mjesec?> iz plaće za mjesec <?=$mjesec?>.
		</p>
		<p class="text-center">
		Zaposlenik se usuglasio da mu se plaća za mjesec <?=$mjesec?> umanji za iznos od <?= $postData['iznos']?> €.
</p>
</br></br></br>


<div class="row">
		<div class="col-1"></div>
	<div class="col-6">
		<p>      U <?=$tvrtka['grad']?>-u, <?php echo $datum ?></p></br></br></br>
	</div>
</div>
<div class="row">
		<div class="col-1"></div>
	<div class="col-6">
		<p>      Zaposlenik, <?=$driver['vozac'] ?></p></br>
	<p>_____________________________</p>
	</div>
</div>



		</dvi>
			<div class="col-1">
			</div>

	</div>
</div>
</div>
</br></br></br></br></br></br>




<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.5.0-beta4/html2canvas.min.js"></script>

<script>
function printDiv() {
    var printContents = document.getElementById('izjava').innerHTML;
    var originalContents = document.body.innerHTML;

    document.body.innerHTML = printContents;
    window.print();

    document.body.innerHTML = originalContents;
}
</script>

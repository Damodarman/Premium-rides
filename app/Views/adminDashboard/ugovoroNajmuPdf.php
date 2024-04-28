<!doctype html>
<html>
<head>
<meta charset="utf-8">
	<style>
@import url('https://fonts.googleapis.com/css2?family=Courier+Prime:ital,wght@0,400;0,700;1,400;1,700&family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap');
</style>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>	
<link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">	
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Jost:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">	
<link href="https://fonts.googleapis.com/css2?family=Courier+Prime:ital,wght@0,400;0,700;1,400;1,700&family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">	
<style>
	html {
		font-size: 95%;
		font-family: "Courier Prime";
	}
	p {
		font-family: "Courier Prime";
	}
	h4 {
		font-family: "Courier Prime";
	}
	
	.text-center {
  text-align: center !important;
}
	.float-start{
		float: left;
	}
	.float-end{
		float: right;
	}
	
	
	</style>
<title>Ugovor o najmu <?php echo $driver['vozac'] ?></title>
</head>

<body>



	<div class="container" id="izjava">
		<div class="row">
			<div class="col-1">
			</div>
			<div class="col-10 fs-7">
				</br>
<p class="text-center"><?php echo $driver['vozac'] ?>, iz <?php echo $driver['adresa'] ?>, <?php echo $driver['grad'] ?>, OIB: <?php echo $driver['oib'] ?> (u daljnjem tekstu : Zakupodavac) i 
<?php echo $tvrtka['naziv'] ?> , <?php echo $tvrtka['adresa'] ?>, <?php echo $tvrtka['grad'] ?>, OIB: <?php echo $tvrtka['OIB'] ?>, zastupa direktor <?php echo $tvrtka['direktor'] ?>,
	(u daljnjem tekstu: Zakupnik) sklopili su dana <?php echo $pocetakNajma ?> godine</p> 
				<h3 class="text-center">UGOVOR O ZAKUPU/PODZAKUPU OSOBNOG AUTOMOBILA</h3> 
<h4 class="text-center">Članak 1.</h4> 
<p class="text-center">Zakupodavac daje, a Zakupnik prima u zakup osobni automobil marke <?php echo $vozilo['proizvodac'] ?> <?php echo $vozilo['model'] ?>, registarskih
oznaka <?php echo $vozilo['reg'] ?>.</p>
<h4 class="text-center">Članak 2.</h4> 
<p class="text-center">Zakupodavac dopušta Zakupniku da koristi zakupljeni osobni automobil od <?php echo $pocetakNajma ?>. a s navedenim
danom zakupnik stupa u posjed osobnog automobila.
Ugovorne strane suglasno utvrđuju da od navedenog dana počinju teći obveze plaćanja najma
troškova iz članka 3. i 6. ovog Ugovora</p>
<h4 class="text-center">Članak 3.</h4> 
<p class="text-center">Stranke ugovaraju tjednu zakupninu za osobni automobil iz članka prvog ovog Ugovora u visini od
<?php echo $vozilo['cijena_tjedno'] ?> € u koju je uključeno prijeđenih <?php echo $ukljuceniKM ?> km + <?php echo $vozilo['cijena_po_km'] ?> € po kilometru za svaki kilometar iznad <?php echo $ukljuceniKM ?> km prijeđenih u istom razdoblju, koja se uplaćuje na tekući račun ili u gotovini.
Zakupnina iz prethodnog stavka obuhvaća troškove tekućeg održavanja zakupljenog automobila iz
članka prvog ovog Ugovora kao što su troškovi potrošnje goriva, održavanja, godišnjih premija
osiguranja i slično, te je zakupodavaoc dužan podmirivati sve troškove. Zakupninu iz prethodnog stavka Zakupnik se obvezuje plaćati na temelju ovog ugovora te se od strane Zakupodavca neće izdavati nikakav račun jer isti nije u obvezi vođenja poslovnih knjiga, a iznos će zakupodavac plaćati za zakupninu unatrag do četvrtka idući tjedan. Zakupodavac sam snosi troškove poreza i prireza koji proizlaze temeljem zakona o porezu na dohodak od najma osobne imovine.</p>
<h4 class="text-center">Članak 4.</h4> 
<p class="text-center">Zakupodavac potpisom ovog Ugovora jamči Zakupniku da je osobni automobil iz članka 1. ovog Ugovora
njegovo isključivo vlasništvo ili da temeljem drugih ugovora ima pravo na upravljanje istim.</p></br></br></br>
<h4 class="text-center">Članak 5.</h4> 
<p class="text-center">Zakupnik je dužan upotrebljavati osobni automobil pažnjom dobrog privrednika te u skladu s uobičajenim načinom upotrebe,
Ugovorne stranke su suglasne da je po isteku ovog Ugovora Zakupnik obvezan vratiti Zakupodavcu
osobni automobil dan u zakup u ispravnom stanju.
Zakupnik ne odgovara za smanjenje vrijednosti osobnog automobila nastalo uslijed normalnog
korištenjə. Zakupnik ne može vršiti preinake, veće popravke ll uredenje osobnog automobila bez prethodne pisane suglasnosti zakupodavca, a u slučaju suglasnosti zakupodavca za izvođenje navedenih radova zakupnik Ima pravo na povrat odnosno uračunavanje u zakupninu uloženih novčanih sredstava u automobil.</p>
<h4 class="text-center">Članak 6.</h4> 
<p class="text-center">Ovaj Ugovor o zakupu sklapa se na neodređeno vrijeme i primjenjuje se od <?php echo $pocetakNajma ?>.
Prestankom važenja ovog Ugovora o zakupu Zakupnik se obvezuje Zakupodavatelju vratiti u posjed
zakupljeni automobil slobodan od osoba iod stvari.
Svaka od ugovornih strana može jednostrano u pismenoj formi rakinuti ovaj Ugovor uz otkazni rok od
30 (trideset) dana.</p>
<h4 class="text-center">Članak 7.</h4> 
<p class="text-center">Ugovorne strane suglasno utvrģjuju da odredbe ovog U govora predstavljaju njihovu pravu volju, te da
sve eventualne izmjene i dopune ovog Ugovora mogu biti samo u pisanom obliku.
Na odnose između ugovornih strana koji nisu regulirani ovim Ugovorom primjenjuju se odredbe Zakona
o zakupu poslovnog prostora i Zakona o obveznim odnosima.</p>
<h4 class="text-center">Članak 8.</h4> 
<p class="text-center">Ugovorne stranke su suglasne da će sve eventualne sporove iz ovog Ugovora nastojati rješavati
sporazumno, a u slučaju spora ugovaraju nadležnost, stvarno nadležnog suda u Zagrebu.</p>
<h4 class="text-center">Članak 9.</h4> 
<p class="text-center">Ovaj Ugovor sastavljen je u 4 (četiri) istovjetna primjerka, od kojih svaka stranka dobiva po 2 (dva)
primjerka.</p> </br> </br>



			</div>
			<div class="col-1">
			</div>

		</div>
<div class="row">
	<div class="float-start">
		<p class="text-center">
			Zakupodavaoc	</br>
			_______________________	
		</p>
	</div>
	
	<div class="float-end">
		<p class="text-center">
			Zakupnik	</br>
			_______________________	
		</p>
	</div>
	
</div>
	</div>
</body>
</html>
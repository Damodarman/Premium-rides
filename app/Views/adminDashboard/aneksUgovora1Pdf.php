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
		font-size: 70%;
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
<title>Aneks ugovora o radu <?php echo $driver['vozac'] ?></title>
</head>

<body>




	<div class="container" id="izjava">
		<div class="row">
			<div class="col-1">
			</div>
			<div class="col-10 fs-4" >
				</br>
	<p class="text-center">
<?php echo $tvrtka['naziv'] ?>, <?php echo $tvrtka['adresa'] ?>, <?php echo $tvrtka['grad'] ?>, OIB: <?php echo $tvrtka['OIB'] ?>. zastupan po, <?php echo $tvrtka['direktor'] ?> (u daljnjem tekstu: Poslodavac), s jedne strane i </br>
<?php echo $driver['vozac'] ?>, <?php echo $driver['adresa'] ?>, iz <?php echo $driver['grad'] ?>, OIB: <?php echo $driver['oib'] ?>, (u dalinjem tekstu:Zaposlenik), s druge strane zaključili su dana: <?php echo $pocetakPromjene ?> godine sljedeći: </p></br></br>

<h1 class="text-center"> ANEKS UGOVORA O RADU</h1></br></br>
<h4 class="text-center">Članak 1.</h4> </br>
<p class="text-center"> 
Poslodavac i Radnik suglasno utvrđuju da je između njih sklopljen ugovor o radu na neodređeno vrijeme, od  <?php echo $pocetakRada ?>, a  kojim se Radnik  obvezao obavljati poslove na radnom mjestu <?php echo $driver['radno_mjesto'] ?>.
		</p></br>
<h4 class="text-center">Članak 2.</h4> </br>
<p class="text-center"> 
Ovim Aneksom ugovora mijenja se i nadopunjuje ugovor o radu na neodređeno vrijeme koji su poslodavac i radnik sklopili dana <?php echo $pocetakRada ?> godine i to u dijelu koji se odnosi na vrijeme rada, raspodjelu radnog vremena, bruto plaću te se dodaje klauzula o zabrani tržišnog natjecanja.
		</p></br>
<h4 class="text-center">Članak 3.</h4> </br>
<p class="text-center"> 
Članak 5. ugovora o radu na neodređeno vrijeme, od <?php echo $pocetakRada ?> godine  se mijenja, tako da isti sada glasi: </br>
Tjedno radno vrijeme utvrđuje se u trajanju od <?php echo $tjedniSati ?> sati. Radni tjedan raspoređen je u pet radnih dana od ponedjelika do nedjelje.
		</p></br>
<h4 class="text-center">Članak 4.</h4> </br>
<p class="text-center"> 
Članak 9. ugovora o radu na neodređeno vrijeme, od <?php echo $pocetakRada ?> godine  se mijenja, tako da isti sada glasi: </br>
Zaposlenik ima pravo na mjesečnu plaću u iznosu od <?php echo $brutoPlaca ?> € bruto.
</p></br>
<h4 class="text-center">Članak 5.</h4> </br>
<p class="text-center"> 
Sve ostale odredbe Ugovora o radu na neodređeno vrijeme sklopljenog dana  <?php echo $pocetakRada ?> godine ostaju neizmijenjene.
</p></br></br></br></br>
<h4 class="text-center">Članak 6.</h4> </br>
<p class="text-center"> 
Ovaj Aneks se primjenjuje od <?php echo $pocetakPromjene ?> godine.
</p></br>
<h4 class="text-center">Članak 7.</h4> </br>
<p class="text-center"> 
Ovaj Aneks sačinjen je u 2 (dva) istovjetna primjerka od kojih jedan zadržava Radnik, a jedan Poslodavac.
</p></br>
	



<div class="row">
		<div class="col-1"></div>
	<div class="col-6">
		<p>      U Zagrebu, <?php echo $pocetakPromjene ?></p>
	</div>
</div>

			</div>
			<div class="col-1">
			</div>

		</div>
<div class="row">
	<div class="float-start"></br></br>
		<h4 class="text-center">
			Poslodavac	</br></br></br>
			_______________________	
		</h4>
	</div>
	
	<div class="float-end"></br></br>
		<h4 class="text-center">
			Zaposlenik	</br></br></br>
			_______________________	
		</h4>
	</div>
	
</div>
	</div>
</body>
</html>

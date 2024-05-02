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
<title>Ugovor o radu <?php echo $driver['vozac'] ?></title>
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
<?php echo $driver['vozac'] ?>, <?php echo $driver['adresa'] ?>, iz <?php echo $driver['grad'] ?>, OIB: <?php echo $driver['oib'] ?>, (u dalinjem tekstu:Zaposlenik), s druge strane zaključili su dana: <?php echo $pocetakRada ?> sljedeći: </p></br>
<h3 class="text-center">
	<?php if($driver['broj_sati'] != '1.5'){
				if($driver['vrsta_zaposlenja']!= 'odredeno'){
					echo 'UGOVOR O RADU NA NEODREĐENO VRIJEME';
				}else{
					echo 'UGOVOR O RADU NA ODREĐENO VRIJEME';
				}
				
			}else{
				echo 'UGOVOR O DOPUNSKOM RADU';
			} 
	?>
	</h4>
	
	
	
	
<h4 class="text-center">Članak 1.</h4> 
<p class="text-center"> 
Ovim ugovorom stranke ugovaraju dan stupanja na rad zoposlenika, radno mjesto na kojem će zoposlenik raditi, trajanje radnog odnosa, plaću zaposlenika i naknade plaće zoposlenika, radno vrijeme, odmore, dopuste. otkazni rok, te druga prava, obveze i odgovornosti zaposlenika i poslodavca.
		</p>
	<h4 class="text-center">	
Članak 2.
	</h4> 
	<p class="text-center">	
Zaposlenik će stupiti na radno mjesto dana <?php echo $pocetakRada ?> godine. Zaposlenik otpočinje s radom dana <?php echo $pocetakRada ?> godine.
		</p>
<h4 class="text-center">		
Članak 3.
		</h4>
	<p class="text-center">		
Zaposlenik će raditi na radnom mjestu <?php echo $driver['radno_mjesto'] ?>. te će obavljati sve poslove vezane uz prijevoz motornim vozilom, te druge poslove na zahtjev poslodavca.
		</p>
		<h4 class="text-center">	
Članak 4.
		</h4>
		<p class="text-center">	
			<?php
			if($driver['vrsta_zaposlenja'] != 'odredeno'):?>
Ovim ugovorom zaposlenik zasniva kod poslodavca radni odnos na neodređeno vrijeme.
			<?php else: ?>
Ovim ugovorom zaposlenik zasniva kod poslodavca radni odnos na određeno vrijeme sa trajanjem do <?php echo $krajRada ?>
			<?php endif ?>
		</p>
		<h4 class="text-center">
		Članak 5.
			</h4>
		<p class="text-center">
Tjedno radno vrijeme utvrduje se u trajanju od <?php echo $tjedniSati ?> sati. Radni tjedan raspoređen je u šest radnih dana od ponedjelika do subote.
			</p>
		<h4 class="text-center">
Članak 6.
		</h4>
		<p class="text-center">
Početak i zavšetak radnog vremena nije moguće unaprijed odrediti radi nepredvidivog obrasca rada, a radnik je dužan biti pripravan odazvati se pozivu poslodavca te pristupiti radu.
			</p>
		<h4 class="text-center">
Članak 7.</h4>
<p class="text-center">Zaposlenik ima pravo na odmor u tijeku dnevnog rada /stanka a vrijeme korištenja odmora u tijeku dnevnog rada odreduje poslodavac.
<h4 class="text-center">Članak 8.</h4>
<p class="text-center">Zaposlenik ima pravo na godišnji odmor na način utvrđen Zakonom o radu.
<h4 class="text-center">Članak 9.</h4>
<p class="text-center">Zaposlenik ima pravo na mjesečnu plaću u iznosu od <?php echo $brutoPlaca ?> € bruto.
<h4 class="text-center">Članak 10.</h4>
<p class="text-center">Poslodavac je dužan radniku isplatiti plaću najkasnije do 15.-og u mjesecu za protekli mjesec.
<h4 class="text-center">Članak 11.</h4>
<p class="text-center">Ovaj ugovor prestaje u skladu sa Zakonom o radu te istekom roka na koji je zakjučen Ugovor mogu otkazati i poslodavac i zaposlenik u slučajevima, na način i pod uvjetom propisanim Otkazni rad može biti redoviti i izvanredni.</p>
	</br>
<h4 class="text-center">Članak 12.</h4>
<p class="text-center">Pod kršenjem obveza iz radnog odnosa zbog kojh poslodavac redovito otkazuje ovaj ugovor smatraju se: neostvarivanje predviđenih rezultata rada iz neopravdanih razloga od strane radnika, neopravdani izostanak s rada dva dana u mjesecu ili tri ili više dana u kalendarskoj godini. Povrede drugih obveza iz rada radnika koje ovim ugovorom nisu utvrdene, ali su utvrdene kao povrede obveze iz rada pravillma poslodavca.</p>
<h4 class="text-center">Članak 13.</h4>
<p class="text-center">Pod osobito teškim povredama iz radnog odnosa zbog kojih poslodavac izvanredno otkazuje ova] ugovor smatraju se: zloupotreba polożaja i prekoračenje od strane radnika, odavanje poslovne ili druge tajne utvrđene zakonom, propisima ili pravilima društva zoupotreba prava korištenja bolovanja od strane radnika, druge osobito teške povrede obveze iz radnog odnosa koje za posijedicu imaju nemogućnost radnog odnosa.</p>
<h4 class="text-center">Članak 14.</h4>
<p class="text-center">Zaposlenik ima pravo ostati na radu do isteka otkaznog roka Otkazni rok traje sukladno Zakonu o radu, Otkazni rok iz predhodnog stavka u pojedinom slučaju poslodavac i radnik suglasno mogu skratiti I u cijelosti odustati od otkaznog roka.</p>
<h4 class="text-center">Članak 15.</h4>
<p class="text-center"> 
Ovim člankom utvđuje se zabrana tržišnog natjecanja. Radniku nije dozvoljeno bez suglasnosti poslodavca obavljati poslove iz ovog ugovora samostalno ili za treće osobe 90 dana nakon raskida ovog ugovora. Poslodavac može, u odluci o raskidu ovog ugovora, ukinuti zabranu. 
</p>

<h4 class="text-center">Članak 16.</h4>
<p class="text-center">Stranke prihvaćaju prava i obveze iz ovog Ugovora, te ga u znak prihvata vlastoručno potpisuju.</p>
<h4 class="text-center">Članak 17.</h4>
<p class="text-center">Za sve sporove koji proisteknu iz ovog ugovora stranke sporazumno ugovaraju nadležnost stvamo nadleżnog suda u Zagrebu.</p>
<h4 class="text-center">Članak 18.</h4>
<p class="text-center">Na sva pitanja koja nisu regulirana ovim ugovorom primjenjuju se odredbe Zakona o radu.</p>
<h4 class="text-center">Članak 19.</h4>
<p class="text-center">Ovaj Ugovor je zaključen u 2 (dva) jednakovažna primjerka od kojih svaka strana zadržava po 1 (jedan).</p></br></br>
	




<div class="row">
		<div class="col-1"></div>
	<div class="col-6">
		<p>      U Zagrebu, <?php echo $pocetakRada ?></p>
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

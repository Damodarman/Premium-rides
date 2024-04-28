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
		font-size: 85%;
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
	.border{
		border-style: solid;
		border-width: 2px;
		border-color: black;
		margin: 2px;
		padding: 4px;
	}
	
	</style>
<title>Blagajnički minimum/maximum <?php echo $driver['vozac'] ?></title>
</head>

<body>


	<div class="container">
		<div class="row">
			<div class="col-1">
			</div>
			<div class="col-12 fs-6" id="izjava">
				</br></br></br>
				<div class="border">
					<p>
					Sukladno ćI. 29., st. 2. Zakona o fiskalizaciji u prometu gotovinom (Nar. nov, br. 133/12.), <?php echo $tvrtka['direktor'] ?> iz <?php echo $tvrtka['grad'] ?>, direktor <?php echo $tvrtka['naziv'] ?>  OIB: <?php echo $tvrtka['OIB'] ?>, donosi:
					
					</p>
					<h3>ODLUKU O VISINI BLAGAJNIČKOG MAKSIMUMA</h3>
					<p>
					Utvrđuje se visina blagajničkog maksimuma za <?php echo $tvrtka['naziv'] ?> u cjelini u svoti od <?php echo $driver['blagMax'] ?> €, 
					a odnosi se na blagajnu koja se nalazi u vozilu <?php echo $vozilo['proizvodac'] ?> <?php echo $vozilo['model'] ?>, registarskih tablica
						<?php echo $vozilo['reg'] ?>.</br>
					Odgovorna osoba obavezuje se da svotu iznad blagajničkog maksimuma koja je utvrđena na kraju
					radnog dana polożi na račun tvrtke najkasnije slijedeći radni dan.
					</p>
					<h3>ODLUKU O VISINI BLAGAJNIČKOG MINIMUMA</h3>
					<p>
						Utvrđuje se visina blagajničkog minimuma za <?php echo $tvrtka['naziv'] ?> u cjelini u svoti od <?php echo $driver['blagMin'] ?> €,
						Odluka se primjenjuje od  <?php echo $pocetakNajma ?> do opoziva (donošenja nove odluke).

					</p>


						
								</br><h4>Potpis i pečat odgovorne osobe</h4>	</br>
								<p>	_______________________________	
							</p>

	
				</div>
			</div>
			<div class="col-1">
			</div>

		</div>
	
</div>
	</div>
</body>
</html>
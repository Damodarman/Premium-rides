<button class="btn btn-primary btn-lg m-3" onclick="printDiv()">Print Izjava</button> 




	<div class="container" id="izjava" style="text-decoration-color: #000000;" @important">
		<div class="row">
			<div class="col-1">
			</div>
			<div class="col-10 fs-4" >
				</br>
	<p class="text-center">
<?php echo $tvrtka['naziv'] ?>, <?php echo $tvrtka['adresa'] ?>, <?php echo $tvrtka['grad'] ?>, OIB: <?php echo $tvrtka['OIB'] ?>. zastupan po, <?php echo $tvrtka['direktor'] ?> (u daljnjem tekstu: Poslodavac), s jedne strane i </br>
<?php echo $driver['vozac'] ?>, <?php echo $driver['adresa'] ?>, iz <?php echo $driver['grad'] ?>, OIB: <?php echo $driver['oib'] ?>, (u dalinjem tekstu:Zaposlenik), s druge strane zaključili su dana: <?php echo $pocetakRada ?> sljedeći: </p></br>
<h3 class="text-center"><?php if($driver['broj_sati'] != '1.5'){echo 'UGOVOR O RADU NA NEODREĐENO VRIJEME';}else{echo 'UGOVOR O DOPUNSKOM RADU';} ?></h4>
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
Ovim ugovorom zaposlenik zasniva kod poslodavca radni odnos na neodređeno vrijeme.
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
<p class="text-center">Ovaj ugovor prestaje u skladu sa Zakonom o radu te istekom roka na koji je zakjučen Ugovor mogu otkazati i poslodavac i zaposlenik u slučajevima, na način i pod uvjetom propisanim Otkazni rad može biti redoviti i izvanredni.
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
	<div class="col-6"></br></br>
		<h4 class="text-center">
			Poslodavac	</br></br></br>
			_______________________	
		</h4>
	</div>
	
	<div class="col-6"></br></br>
		<h4 class="text-center">
			Zaposlenik	</br></br></br>
			_______________________	
		</h4>
	</div>
	
</div>
	</div>
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


<script>
function downloadPDF() {
    // Create a new jsPDF instance
	import { jsPDF } from "jspdf";
    var pdf = new jsPDF('p', 'pt', 'a4');

    // Get the HTML content from the element you want to convert to PDF
    var element = document.getElementById('izjava');

    // Use html2canvas to render the HTML content as an image
    html2canvas(element).then(function (canvas) {
        // Convert the canvas to an image data URL
        var imgData = canvas.toDataURL('image/png');

        // Add the image to the PDF document
        pdf.addImage(imgData, 'PNG', 0, 0);

        // Save the PDF with a filename 'download.pdf'
        pdf.save('download.pdf');
    });
}
</script>
</body>
</html>
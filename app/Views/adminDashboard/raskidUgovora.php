<button class="btn btn-primary btn-lg m-3" onclick="printDiv()">Print Raskid</button> 



	<div class="container" id="izjava">
		<div class="row">
			<div class="col-1">
			</div>
			<div class="col-10 fs-4" >
				</br>

			<p class="text-center">
<?php echo $tvrtka['naziv'] ?>, <?php echo $tvrtka['adresa'] ?>, <?php echo $tvrtka['grad'] ?>, OIB: <?php echo $tvrtka['OIB'] ?>. zastupan po, <?php echo $tvrtka['direktor'] ?> (u daljnjem tekstu: Poslodavac), s jedne strane i </br>
<?php echo $driver['vozac'] ?>, <?php echo $driver['adresa'] ?>, iz <?php echo $driver['grad'] ?>, OIB: <?php echo $driver['oib'] ?>, (u dalinjem tekstu:Radnik), s druge strane zaključili su dana: <?php echo $pocetakPromjene ?> sljedeći: </p></br>
<h1 class="text-center"> SPORAZUMNI RASKID UGOVORA O RADU</h1></br></br>
<h4 class="text-center">Članak 1.</h4> </br>
<p class="text-center"> 
Poslodavac i Radnik suglasno utvrđuju da je između njih sklopljen ugovor o radu na neodređeno vrijeme, od  <?php echo $pocetakRada ?>, a  kojim se Radnik  obvezao obavljati poslove na radnom mjestu <?php echo $driver['radno_mjesto'] ?>.
		</p></br>
<h4 class="text-center">Članak 2.</h4> </br>
<p class="text-center"> 
Poslodavac i Radnik suglasno utvrđuju da je između njih došlo do sporazumnog Raskida ugovora o radu.
		</p></br>
<h4 class="text-center">Članak 3.</h4> </br>
<p class="text-center"> 
Sporazumni raskid ugovora stupa na snagu dana <?php echo $pocetakPromjene ?>
		</p></br>
<h4 class="text-center">Članak 4.</h4> </br>
<p class="text-center"> 
Obje strane su se usuglasile da nemaju međusobnih potraživanja.
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

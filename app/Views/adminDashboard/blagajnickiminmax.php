<button onclick="printDiv()">Print Izjava</button> 

<button onclick="downloadPDF()">Download izjava u PDF-u</button>


	<div class="container">
		<div class="row">
			<div class="col-1">
			</div>
			<div class="col-12 fs-6" id="izjava">
				</br></br></br>
				<div class="border border-dark border-3 m-2 p-2">
					<p>
					Sukladno ćI. 29., st. 2. Zakona o fiskalizaciji u prometu gotovinom (Nar. nov, br. 133/12.), <?php echo $tvrtka['direktor'] ?> iz <?php echo $tvrtka['grad'] ?>, direktor <?php echo $tvrtka['naziv'] ?>  OIB: <?php echo $tvrtka['OIB'] ?>, donosi:
					
					</p>
					<h5>ODLUKU O VISINI BLAGAJNIČKOG MAKSIMUMA</h5>
					<p>
					Utvrđuje se visina blagajničkog maksimuma za <?php echo $tvrtka['naziv'] ?> u cjelini u svoti od <?php echo $driver['blagMax'] ?> €, 
					a odnosi se na blagajnu koja se nalazi u vozilu <?php echo $vozilo['proizvodac'] ?> <?php echo $vozilo['model'] ?>, registarskih tablica
						<?php echo $vozilo['reg'] ?>.</br>
					Odgovorna osoba obavezuje se da svotu iznad blagajničkog maksimuma koja je utvrđena na kraju
					radnog dana polożi na račun tvrtke najkasnije slijedeći radni dan.
					</p>
					<h5>ODLUKU O VISINI BLAGAJNIČKOG MINIMUMA</h5>
					<p>
						Utvrđuje se visina blagajničkog minimuma za <?php echo $tvrtka['naziv'] ?> u cjelini u svoti od <?php echo $driver['blagMin'] ?> €,
						Odluka se primjenjuje od  <?php echo $pocetakNajma ?> do opoziva (donošenja nove odluke).

					</p>


						
								</br><h6>Potpis i pečat odgovorne osobe</h6>	</br>
								<p>	_______________________________	
							</p>

	
				</div>
			</div>
			<div class="col-1">
			</div>

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
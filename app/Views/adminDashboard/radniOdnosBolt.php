

<!-- Add a button or trigger to call the printDiv function -->
<button onclick="printDiv()">Print Izjava</button> 

<button onclick="printDivPDF()">Download izjava u PDF-u</button>

<div id="izjava" class="container mt-8 " style="width: 8.27in; height: 11.69in" >
	</br></br></br>	
<div  class="text-center">
<img src="<?php echo base_url('/img/bolt.jpg')?>" style="width: 2.30in; height: 1.30in" alt="Bolt logo"/>
	<h2 class="mt-3">Vrsta ugovornog odnosa između</br> licencirane taxi tvrtke/obrta i vozača</h2>
</br></br></br>
<h3> Ispunjavanjem ove izjave potvrđujem vrstu ugovornog odnosa između:</h3>
</br></br></br>
</div>
<h4 class="mt-5 text-dark">
Naziv tvrtka/obrt: <u>_____<?php echo $tvrtka['naziv'] ?>_____</u>,
</h4>
<h4 class="mt-5 text-dark">
OIB ili MBO (tvrtka/obrt): <u>_____<?php echo $tvrtka['OIB'] ?>_____</u>,
</h4>

<h4 class="mt-5 text-dark">i </h4>

<h4 class="mt-5 text-dark">
Ime i prezime vozača: <u>_____<?php echo $driver['vozac'] ?>_____</u>,
</h4>
<h4 class="mt-5 text-dark">
OIB vozača: <u>_____<?php echo $driver['oib'] ?>_____</u>,
</h4>
</br>
<h4 class="mt-5 text-dark">
Vrsta ugovornog odnosa (molimo označite odgovarajuću vrstu ugovornog odnosa):
</h4>
</br></br>


<div class="container">
    <div class="row">
        <div class="col-12 ms-0">
            <div class="form-check d-flex align-items-center mb-4 ps-0">
				<svg xmlns="http://www.w3.org/2000/svg" width="0.25in" height="0.25in" fill="currentColor" class="bi bi-check-square me-3" viewBox="0 0 16 16">
  <path d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z"/>
					<?php if($radniOdnos === 'ugovor'){
							echo '<path d="M10.97 4.97a.75.75 0 0 1 1.071 1.05l-3.992 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.235.235 0 0 1 .02-.022z"/>';
							} ?>
</svg>
                <label class="form-check-label h4 mb-0" for="checkbox1" style="max-height: 3em; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; width: calc(100% - 0.5in);"><h4>Ugovor o radu / Employment contract</h4></label>
            </div>
             <div class="form-check d-flex align-items-center mb-4 ps-0">
				<svg xmlns="http://www.w3.org/2000/svg" width="0.25in" height="0.25in" fill="currentColor" class="bi bi-square me-3" viewBox="0 0 16 16">
  <path d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z"/>
					<?php if($radniOdnos === 'obrtnik'){
							echo '<path d="M10.97 4.97a.75.75 0 0 1 1.071 1.05l-3.992 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.235.235 0 0 1 .02-.022z"/>';
							} ?>
</svg>
                <label class="form-check-label h1 mb-0" for="checkbox1" style="max-height: 3em; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; width: calc(100% - 0.5in);"><h4>Ugovor o djelu / Service contract</h4></label>
            </div>
           <div class="form-check d-flex align-items-center mb-4 ps-0">
				<svg xmlns="http://www.w3.org/2000/svg" width="0.25in" height="0.25in" fill="currentColor" class="bi bi-square me-3" viewBox="0 0 16 16">
  <path d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z"/>
					<?php if($radniOdnos === 'student'){
							echo '<path d="M10.97 4.97a.75.75 0 0 1 1.071 1.05l-3.992 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.235.235 0 0 1 .02-.022z"/>';
							} ?>
</svg>

                <label class="form-check-label h4 mb-0" for="checkbox1" style="max-height: 3em; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; width: calc(100% - 0.5in);"><h4>Studentski ugovor / Student contract for employment</h4> </label>
            </div>
            <div class="form-check d-flex align-items-center mb-4 ps-0">
				<svg xmlns="http://www.w3.org/2000/svg" width="0.25in" height="0.25in" fill="currentColor" class="bi bi-square me-3" viewBox="0 0 16 16">
  <path d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z"/>
					<?php if($radniOdnos === 'obrtnik_sa_agregatorom'){
							echo '<path d="M10.97 4.97a.75.75 0 0 1 1.071 1.05l-3.992 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.235.235 0 0 1 .02-.022z"/>';
							} ?>
</svg>

                <label class="form-check-label h4 mb-0" for="checkbox1" style="max-height: 3em; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; width: calc(100% - 0.5in);"><h4>Ugovor o suradnji sa samozaposlenim obrtnikom / Self-employed craft </h4></label>
            </div>
        </div>
    </div>
</div>		
</br></br></br>
<h4>U <u>___Zagrebu___,_____<?php echo $pocetakRada ?>_____</u><i class=" text-muted">(grad, datum izjave)</i>.</h4>
    </div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.5.0-beta4/html2canvas.min.js"></script>
<script>
function printDivPDF() {
    // Create a new jsPDF instance
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
<script>
function printDiv() {
    var printContents = document.getElementById('izjava').innerHTML;
    var originalContents = document.body.innerHTML;

    document.body.innerHTML = printContents;
    window.print();

    document.body.innerHTML = originalContents;
}
</script>



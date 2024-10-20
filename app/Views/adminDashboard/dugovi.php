<div class="container">
    <div class="row">
        <?php if (session()->has('dugPlacen')){ ?>
            <div class="alert <?=session()->getFlashdata('alert-class') ?>">
                <?=session()->getFlashdata('dugPlacen') ?>
            </div>
        <?php } ?>
		
		<?php if(isset($resultOpomena)){
			print_r($result);
	
} ?>
		
		<a href="<?php echo site_url('dugovi/tablicaDugova')?>" class="btn btn-sm btn-warning">Pogledaj tablicu dugova</a>
		</br>
        <input type="text" id="searchInput" placeholder="Pretraži po iznosu...">
        <table class="table table-dark table-striped">
            <thead>
                <tr>
                    <th scope="col">Vozac</th>
                    <th scope="col">Iznos</th>
                    <th scope="col">Neto Plaća</th>
                    <th scope="col">Plaćeno Aircash</th>
                    <th scope="col">Plaćeno Poslovnica</th>
                    <th scope="col">Uskrata na Plaći</th>
                </tr>
				 <tr>
                    <th scope="col">Total:</th>
                    <th scope="col" id="totalIznos"></th>
                    <th scope="col"></th>
                    <th scope="col"></th>
                    <th scope="col"></th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $totalIznos = 0; // Initialize the total iznos variable
		   		$totalNetoPlace = 0;
                foreach($dugovi as $dug): 
                    $totalIznos += $dug['iznos']; // Add each iznos to the total
                    $totalNetoPlace += $dug['netoPlaca']; // Add each iznos to the total
                ?>
                <tr>
                    <td><a href="<?php echo site_url('drivers/'). '/' .$dug['vozac_id']?>"><?php echo $dug['vozac'] ?></a> </td>
					<?php if($role == 'admin'): ?>
                    <td><a href="<?php echo site_url('dug/'). '/' .$dug['id']?>" class=""><?php echo $dug['iznos'] ?></a></td>
					<?php else: ?>
                    <td> <?php echo $dug['iznos'] ?></td>
					<?php endif ?>
					<?php 
						$razlika = $dug['iznos'] + $dug['netoPlaca'];
						if($razlika > 0): ?>
                     <td class="text-warning"> <?php echo $dug['netoPlaca'] ?></td>
					<?php else: ?>
                     <td class="text-danger"> <?php echo $dug['netoPlaca'] ?></td>
					<?php endif ?>
                   <td> <a href="<?php echo site_url('dugPlacen/'). '/' .$dug['id']?>" class="btn btn-sm btn-success">Plaćeno Aircash</a></td>
                   <td> <a href="<?php echo site_url('dugPlacenPoslovnica/'). '/' .$dug['id']?>" class="btn btn-sm btn-success">Plaćeno Poslovnica</a></td>
                    <td><a href="<?php echo site_url('dugovi/kreirajUskratu/'). '/' .$dug['id']?>"> Kreiraj uskratu</a> </td>
              </tr>
                <?php endforeach ?>
            </tbody>
            <tfoot>
                <tr>
                    <th>Total:</th>
                    <th><?php echo $totalIznos; ?></th> <!-- Display the total iznos here -->
                    <th><?php echo $totalNetoPlace; ?></th> <!-- Leave this column empty in the footer -->
                    <th></th> <!-- Leave this column empty in the footer -->
                    <th></th> <!-- Leave this column empty in the footer -->
                    <th></th> <!-- Leave this column empty in the footer -->
                </tr>
            </tfoot>
        </table>
	<?php if($role != 'admin'): ?>
	
	<?php else : ?>
<h3>Tablica nisko rizičnih dugova</h3>

<!-- Low-risk table -->
<table class="table table-dark table-striped" id="lowRiskTable">
    <thead>
        <tr>
            <th scope="col">Vozac</th>
            <th scope="col">Iznos</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($dugovi as $dug): ?>
        <?php if($dug['iznos'] <= -31 && $dug['iznos'] > -100): ?>
            <tr>
                <td><?php echo $dug['vozac'] ?></td>
                <td><?php echo abs($dug['iznos']); ?></td> <!-- Convert to positive -->
                <!-- Hidden data stored in data attributes for JS to gather -->
                <td hidden class="dugData" data-vozac-id="<?php echo $dug['vozac_id']; ?>"
                    data-vozac="<?php echo $dug['vozac']; ?>" 
                    data-iznos="<?php echo abs($dug['iznos']); ?>"></td>
            </tr>
        <?php endif ?>
        <?php endforeach ?>
    </tbody>
</table>

<!-- Form for sending reminder to low-risk drivers -->
<h4>Pošaljite podsjetnik za nisko rizične dugove</h4>
<form action="<?php echo site_url('sendReminder') ?>" method="POST" onsubmit="collectData('lowRiskTable', 'lowRiskJsonData'); return true;">
    <input type="hidden" id="lowRiskJsonData" name="jsonData"> <!-- JSON data will be inserted here -->
    
    <!-- Text input for reminder message -->
    <textarea name="message" rows="3" class="form-control" placeholder="Upišite podsjetnik" required>Poštovani/a {{Vozac}}, u sustavu nije zabilježena uplata tvog dugovanja u iznosu od {{Dug}}€. Sa dugom od {{Dug}}€ ulaziš u skupinu nisko rizičnih vozača/ica pa te samo podsjećamo na taj dug i molimo te da isti podmiriš ili da tokom ovog tjedna osiguraš dovoljan iznos na karticama kako bi to pokrio/la na sljedećem obračunu. Ova poruka je automatski generirana. Ukoliko si u međuvremenu podmirio/la svoj dug molimo te da nam se javiš kako bismo ga propisno proveli u sustavu.</textarea>

    <!-- Submit button -->
    <button type="submit" class="btn btn-primary mt-2">Pošalji podsjetnik</button>
</form>

<h3>Tablica srednje rizičnih dugova</h3>

<!-- Medium-risk table -->
<table class="table table-dark table-striped" id="mediumRiskTable">
    <thead>
        <tr>
            <th scope="col">Vozac</th>
            <th scope="col">Iznos</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($dugovi as $dug): ?>
        <?php if($dug['iznos'] <= -100 && $dug['iznos'] > -250): ?>
            <tr>
                <td><?php echo $dug['vozac'] ?></td>
                <td><?php echo abs($dug['iznos']); ?></td> <!-- Convert to positive -->
                <td hidden class="dugData" data-vozac-id="<?php echo $dug['vozac_id']; ?>"
                    data-vozac="<?php echo $dug['vozac']; ?>" 
                    data-iznos="<?php echo abs($dug['iznos']); ?>"></td>
            </tr>
        <?php endif ?>
        <?php endforeach ?>
    </tbody>
</table>

<!-- Form for sending reminder to medium-risk drivers -->
<h4>Pošaljite podsjetnik za srednje rizične dugove</h4>
<form action="<?php echo site_url('sendReminder') ?>" method="POST" onsubmit="collectData('mediumRiskTable', 'mediumRiskJsonData'); return true;">
    <input type="hidden" id="mediumRiskJsonData" name="jsonData"> <!-- JSON data will be inserted here -->
    
    <!-- Text input for reminder message -->
    <textarea name="message" rows="3" class="form-control" placeholder="Upišite podsjetnik" required>Poštovani/a {{Vozac}}, u sustavu nije zabilježena uplata tvog dugovanja u iznosu od {{Dug}}€. Sa dugom od {{Dug}}€ ulaziš u skupinu srednje rizičnih vozača/ica pa te molimo da do petka u 12:00 h podmiriš svoj dug. Ukoliko do petka ne zaprimimo tvoju uplatu postoji mogućnost da ti onemogućimo rad preko Bolt aplikacije i vožnje na gotovinu preko Uber aplikacije. Ova poruka je automatski generirana. Ukoliko si u međuvremenu podmirio/la svoj dug molimo te da nam se javiš kako bismo ga propisno proveli u sustavu.</textarea>

    <!-- Submit button -->
    <button type="submit" class="btn btn-primary mt-2">Pošalji podsjetnik</button>
</form>

<h3>Tablica visoko rizičnih dugova</h3>

<!-- High-risk table -->
<table class="table table-dark table-striped" id="highRiskTable">
    <thead>
        <tr>
            <th scope="col">Vozac</th>
            <th scope="col">Iznos</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($dugovi as $dug): ?>
        <?php if($dug['iznos'] <= -250): ?>
            <tr>
                <td><?php echo $dug['vozac'] ?></td>
                <td><?php echo abs($dug['iznos']); ?></td> <!-- Convert to positive -->
                <td hidden class="dugData" data-vozac-id="<?php echo $dug['vozac_id']; ?>"
                    data-vozac="<?php echo $dug['vozac']; ?>" 
                    data-iznos="<?php echo abs($dug['iznos']); ?>"></td>
            </tr>
        <?php endif ?>
        <?php endforeach ?>
    </tbody>
</table>

<!-- Form for sending reminder to high-risk drivers -->
<h4>Pošaljite podsjetnik za visoko rizične dugove</h4>
<form action="<?php echo site_url('sendReminder') ?>" method="POST" onsubmit="collectData('highRiskTable', 'highRiskJsonData'); return true;">
    <input type="hidden" id="highRiskJsonData" name="jsonData"> <!-- JSON data will be inserted here -->
    
    <!-- Text input for reminder message -->
    <textarea name="message" rows="3" class="form-control" placeholder="Upišite podsjetnik" required>Poštovani/a {{Vozac}}, u sustavu nije zabilježena uplata tvog dugovanja u iznosu od {{Dug}}€. Sa dugom od {{Dug}}€ ulaziš u skupinu visoko rizičnih vozača/ica pa te molimo da do petka u 12:00 h podmiriš svoj dug. Ukoliko do petka ne zaprimimo tvoju uplatu  onemogućiti ćemo ti rad preko Bolt aplikacije i vožnje na gotovinu preko Uber aplikacije. Ukoliko dug neće biti podmiren do idućeg obračuna postoji mogućnost trajnog isključenja. Ova poruka je automatski generirana. Ukoliko si u međuvremenu podmirio/la svoj dug molimo te da nam se javiš kako bismo ga propisno proveli u sustavu.</textarea>

    <!-- Submit button -->
    <button type="submit" class="btn btn-primary mt-2">Pošalji podsjetnik</button>
</form>

	<?php endif; ?>
</div>
</div>


<script>
// Function to collect data and populate the hidden JSON input field
function collectData(tableId, inputId) {
    var rows = document.querySelectorAll('#' + tableId + ' .dugData'); // Select all hidden data in the table
    var data = [];

    rows.forEach(function(row) {
        var vozacId = row.getAttribute('data-vozac-id');
        var vozac = row.getAttribute('data-vozac');
        var iznos = row.getAttribute('data-iznos');

        // Push the collected data to the array
        data.push({
            vozac_id: vozacId,
            vozac: vozac,
            iznos: iznos
        });
    });

    // Convert data array to JSON and put it in the hidden input field
    document.getElementById(inputId).value = JSON.stringify(data);
}
</script>

<script>
// PHP variable containing the totalIznos value
var totalIznosValue = <?php echo $totalIznos; ?>;

// Update the content of the HTML element with id "totalIznos"
document.getElementById('totalIznos').textContent = totalIznosValue;
</script>

<script>
    $(document).ready(function () {
        // Function to filter rows based on the search input
        $("#searchInput").on("input", function () {
            var searchText = $(this).val().toLowerCase().replace(".", ",");
            $("table tbody tr").each(function () {
                var iznosText = $(this).find("td:eq(1)").text().toLowerCase().replace(".", ",");
                if (iznosText.indexOf(searchText) === -1) {
                    $(this).hide();
                } else {
                    $(this).show();
                }
            });
        });
    });
</script>
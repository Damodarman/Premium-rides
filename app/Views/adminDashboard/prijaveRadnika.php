<div class="container">

<div class="row">
<button type="button" class="btn btn-primary" id="kontroler">Prikaži kronologiju</button>
</div>
</div>
<div class="row m-3" id="trenutno">
		 <div id="buttonsContainer">
		 <button type="button" class="btn btn-primary" id="dwnldBtn">Skini tablicu u excelu</button> 
		 <button type="button" class="btn btn-primary" id="togglePagingBtn">Toggle Paging</button>
	 </div>

 <table class="display"  id="dataTable">
        <thead>
        <tr>
            <th>Ime</th>
            <th>OIB</th>
            <th>Datum rođenja</th>
            <th>Adresa</th>
            <th>Grad</th>
            <th>Država</th>
            <th>Vrsta zaposlenja</th>
            <th>Početak prijave</th>
            <th>Kraj prijave</th>
            <th>Broj sati</th>
            <th>IBAN</th>
            <th>Zaštićeni IBAN</th>
            <th>Strani IBAN</th>
            <th>Radno mjesto</th>
            <th>Sati nakon promjene</th>
            <th>Početak promjene</th>
            <th>Prekid rada</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($radnici2 as $index => $radnik): ?>
			<?php
				$pocetakPrijave = $radnik['pocetak_prijave'];
				$pocetakPromjene = $radnik['pocetak_promjene'];

				// Create DateTime objects from the date strings with the new format 'd/m/Y'
				$datePrijave = DateTime::createFromFormat('d/m/Y', $pocetakPrijave);
				$datePromjene = DateTime::createFromFormat('d/m/Y', $pocetakPromjene);
				$timestampPromjene = $datePromjene->getTimestamp();
				

				// Get timestamps from DateTime objects
				$timestampPrijave = $datePrijave->getTimestamp();

				if ($timestampPrijave < $timestampPromjene) {
					$colorPrijave = '';
					$colorPromjene = 'bg-info'; // or any other color for older dates
				} else {
					$colorPrijave = 'bg-info';
					$colorPromjene = '';
				}
			?>
            <tr>
                <td><?= $radnik['vozac'] ?></td>
                <td><?= $radnik['OIB'] ?></td>
                <td><?= $radnik['dob'] ?></td>
                <td><?= $radnik['adresa'] ?></td>
                <td><?= $radnik['grad'] ?></td>
                <td><?= $radnik['drzava'] ?></td>
                <td><?= $radnik['vrsta_zaposlenja'] ?></td>
                <td class="text-nowrap <?=$colorPrijave?>"><?= $radnik['pocetak_prijave'] ?></td>
                <td><?= $radnik['kraj_prijave'] ?></td>
                <td><?= $radnik['broj_sati'] ?></td>
                <td><?= $radnik['IBAN'] ?></td>
                <td><?= $radnik['zasticeniIBAN'] ?></td>
                <td><?= $radnik['strani_IBAN'] ?></td>
                <td><?= $radnik['radno_mjesto'] ?></td>
                <td><?= $radnik['sati_nakon_promjene'] ?></td>
                <td class="text-nowrap <?=$colorPromjene?>"><?= $radnik['pocetak_promjene'] ?></td>
                <td class="text-nowrap"><?= $radnik['prekid_rada'] ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

	<div class="row m-3 d-none" id="kronologija">
		 <table id="example" class="table table-dark table-striped table-sm " style="font-size: 14px; width:80%">
			<thead>
				<tr>
					<th scope="col">Broj unosa</th>
					<th scope="col">Ime</th>
					<th scope="col">Prezime</th>
					<th scope="col">OIB</th>
					<th scope="col">Datum Rođenja</th>
					<th scope="col">Početak prijave</th>
					<th scope="col">Broj sati</th>
					<th scope="col">IBAN</th>
					<th scope="col">Zaštičeni IBAN</th>
					<th scope="col">Strani IBAN</th>
					<th scope="col">Radno mjesto</th>
					<th scope="col">Prekid rada</th>
					<th scope="col">Početak promjene</th>
					<th scope="col">Prvi unos</th>
					<th scope="col">Nadopuna</th>
					<th scope="col">Vrijeme unosa</th>
					<th scope="col">Administrator</th>
				</tr>
   			</thead>
            <tbody class="text-nowrap">
				<?php $ids = array(); ?>
				<?php foreach ($radnici as $radnik): ?>
					<?php
						$currentVozacId = $radnik['vozac_id'];
						if (in_array($currentVozacId, $ids)) {
							continue; // Skip if the ID has already been processed
						}
						$moreOccurrences = array_filter($radnici, function ($item) use ($currentVozacId) {
							return $item['vozac_id'] === $currentVozacId;
						});
						$num = count($moreOccurrences);
						if (count($moreOccurrences) > 1):
							// Sort the $moreOccurrences array by 'id' in descending order
						usort($moreOccurrences, function($a, $b) {
								return $b['id'] <=> $a['id'];
							});
					?>
					<?php $count = 0 ?>
					<?php foreach($moreOccurrences as $radnik): ?>
						<?php if($count === 0): ?>
						<tr>
							<!-- Display data for the latest row based on highest 'id' -->
							<th scope="row"><?php echo $num; ?></th>
							<td><a href="<?php echo site_url('drivers/'). '/' .$radnik['vozac_id']?>" style="text-decoration: none; color: inherit;"><?php echo $radnik['ime']; ?></a></td>
							<td><?php echo $radnik['prezime']; ?></td>
							<td><?php echo $radnik['OIB']; ?></td>
							<td><?php echo $radnik['dob']; ?></td>
							<td><?php echo $radnik['pocetak_prijave']; ?></td>
							<td><?php echo $radnik['broj_sati']; ?></td>
							<td><?php echo $radnik['IBAN']; ?></td>
							<td><?php echo $radnik['zasticeniIBAN']; ?></td>
							<td><?php echo $radnik['strani_IBAN']; ?></td>
							<td><?php echo $radnik['radno_mjesto']; ?></td>
							<td><?php echo $radnik['prekid_rada']; ?></td>
							<td><?php echo $radnik['pocetak_promjene']; ?></td>
							<td><?php if($radnik['prvi_unos'] != 0) echo 'DA'; else echo 'NE'; ?></td>
							<td><?php if($radnik['nadopuna'] != 0) echo 'DA'; else echo 'NE'; ?></td>
							<td><?php echo $radnik['timestamp']; ?></td>
							<td><?php echo $radnik['administrator']; ?></td>
						</tr>
						<?php else: ?>
						<tr>
							<!-- Display data for the latest row based on highest 'id' -->
							<th scope="row"><i class="bi bi-arrow-90deg-up ms-3"></i></th>
							<td><a href="<?php echo site_url('drivers/'). '/' .$radnik['vozac_id']?>" style="text-decoration: none; color: inherit;"><?php echo $radnik['ime']; ?></a></td>
							<td><?php echo $radnik['prezime']; ?></td>
							<td><?php echo $radnik['OIB']; ?></td>
							<td><?php echo $radnik['dob']; ?></td>
							<td><?php echo $radnik['pocetak_prijave']; ?></td>
							<td><?php echo $radnik['broj_sati']; ?></td>
							<td><?php echo $radnik['IBAN']; ?></td>
							<td><?php echo $radnik['zasticeniIBAN']; ?></td>
							<td><?php echo $radnik['strani_IBAN']; ?></td>
							<td><?php echo $radnik['radno_mjesto']; ?></td>
							<td><?php echo $radnik['prekid_rada']; ?></td>
							<td><?php echo $radnik['pocetak_promjene']; ?></td>
							<td><?php if($radnik['prvi_unos'] != 0) echo 'DA'; else echo 'NE'; ?></td>
							<td><?php if($radnik['nadopuna'] != 0) echo 'DA'; else echo 'NE'; ?></td>
							<td><?php echo $radnik['timestamp']; ?></td>
							<td><?php echo $radnik['administrator']; ?></td>
						</tr>
				
						<?php endif ?>
					<?php $count += 1 ?>
					<?php endforeach ?>
						<?php $ids[] = $radnik['vozac_id']; ?>
					<?php else: ?>
					
				<tr>
							<th scope="row"><?php echo $num; ?></th>
							<td><a href="<?php echo site_url('drivers/'). '/' .$radnik['vozac_id']?>" style="text-decoration: none; color: inherit;"><?php echo $radnik['ime']; ?></a></td>
							<td><?php echo $radnik['prezime']; ?></td>
							<td><?php echo $radnik['OIB']; ?></td>
							<td><?php echo $radnik['dob']; ?></td>
							<td><?php echo $radnik['pocetak_prijave']; ?></td>
							<td><?php echo $radnik['broj_sati']; ?></td>
							<td><?php echo $radnik['IBAN']; ?></td>
							<td><?php echo $radnik['zasticeniIBAN']; ?></td>
							<td><?php echo $radnik['strani_IBAN']; ?></td>
							<td><?php echo $radnik['radno_mjesto']; ?></td>
							<td><?php echo $radnik['prekid_rada']; ?></td>
							<td><?php echo $radnik['pocetak_promjene']; ?></td>
							<td><?php if($radnik['prvi_unos'] != 0) echo 'DA'; else echo 'NE'; ?></td>
							<td><?php if($radnik['nadopuna'] != 0) echo 'DA'; else echo 'NE'; ?></td>
							<td><?php echo $radnik['timestamp']; ?></td>
							<td><?php echo $radnik['administrator']; ?></td>
				</tr>
				<?php $ids[] = $radnik['vozac_id']; ?>
				<?php endif ?>
				<?php endforeach; ?>				
				
				
            </tbody>			
		</table>
	
</div>

<script>
    document.getElementById('kontroler').addEventListener('click', function() {
        // Toggle visibility of the divs
        document.getElementById('trenutno').classList.toggle('d-none');
        document.getElementById('kronologija').classList.toggle('d-none');
        
        // Change button text based on the currently visible div
        var button = document.getElementById('kontroler');
        if (document.getElementById('trenutno').classList.contains('d-none')) {
            button.textContent = 'Prikaži trenutno stanje';
        } else {
            button.textContent = 'Prikaži kronologiju';
        }
    });
</script>
<script>
$(document).ready(function () {
    var table = $('#dataTable').DataTable({
        ordering: false,
		 dom: 'Bfrtip', // Specify the buttons container
        buttons: [
            'excelHtml5', // Enable Excel export button
            'pdfHtml5',   // Enable PDF export button
            'print'       // Enable Print button
        ]
        
    });


    // Toggle paging on button click
    $('#togglePagingBtn').on('click', function () {
        var pagingEnabled = table.settings()[0].oFeatures.bPaginate;
        
        // Toggle paging
        table.settings()[0].oFeatures.bPaginate = !pagingEnabled;
        table.draw();
    });
});
</script>

<script>
    $(document).ready(function () {
        $('#dwnldBtn').on('click', function () {
            // Get the current date
            var currentDate = new Date();
            
            // Format the date in Croatian date format
            var options = { year: 'numeric', month: 'numeric', day: 'numeric' };
            var formattedDate = currentDate.toLocaleDateString('hr-HR', options);
            
            // Set the filename with the current date
            var filename = "Prijave_radnika_" + formattedDate + ".xls";

            // Trigger the table2excel plugin with the dynamic filename
            $("#dataTable").table2excel({
                filename: filename
            });
        });
    });
</script>

<script>
$(document).ready(function () {
    $('#example').DataTable({
        ordering: false,
    	 stripeClasses: ['odd', 'even']
	});
});
</script>

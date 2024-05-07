

<div class="container mt-4">
                 <?php if(session()->getFlashdata('msgPoruka')):?>
                    <div class="alert <?=session()->getFlashdata('alert-class') ?>">
                       <?= session()->getFlashdata('msgPoruka') ?>
                    </div>
                <?php endif;?>
    <h2>Neobrađene prijave</h2>
    <table id="exampleNeobradeno" class="table table-striped table-light table-sm" style="width:100%">
        <thead>
            <tr>
                <th>
                    <input type="checkbox" id="select-allNeobradeno" />
                </th>
                <th>Ime</th>
                <th>Prezime</th>
                <th>OIB</th>
                <th>Datum rođenja</th>
                <th scope="col">Početak prijave</th>
                 <th scope="col">Kraj prijave</th>
               <th scope="col">Broj sati</th>
                <th scope="col">Radno mjesto</th>
                <th scope="col">Prekid rada</th>
                <th scope="col">Početak promjene</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($neobradenePrijave as $driver):  ?>
            <tr  data-vozac-id="<?php echo $driver['vozac_id']; ?>">
                <td>
                    <input type="checkbox" class="row-checkbox" />
                </td>
                <td><?php echo $driver['ime'] ?></td>
                <td><?php echo $driver['prezime'] ?></td>
                <td><?php echo $driver['OIB'] ?></td>
                <td><?php echo $driver['dob'] ?></td>
                <td><?php echo $driver['pocetak_prijave']; ?></td>
                <td><?php echo $driver['kraj_prijave']; ?></td>
                <td><?php echo $driver['broj_sati']; ?></td>
                <td><?php echo $driver['radno_mjesto']; ?></td>
                <td><?php echo $driver['prekid_rada']; ?></td>
                <td><?php echo $driver['pocetak_promjene']; ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <th>
                    <input type="checkbox" id="select-all-footerNeobradeno" />
                </th>
                <th>Ime</th>
                <th>Prezime</th>
                <th>OIB</th>
                <th>Datum rođenja</th>
                <th scope="col">Početak prijave</th>
                <th scope="col">Kraj prijave</th>
                <th scope="col">Broj sati</th>
                <th scope="col">Radno mjesto</th>
                <th scope="col">Prekid rada</th>
                <th scope="col">Početak promjene</th>
            </tr>
        </tfoot>
    </table>
</div>




<div class="container mt-4">
    <h2>Obrađene prijave</h2>
    <table id="example" class="table table-striped table-light table-sm text-center" style="width:100%" >
        <thead>
            <tr>
                <th>
                    <input type="checkbox" id="select-all" />
                </th>
                <th>Ime</th>
                <th>Prezime</th>
                <th>OIB</th>
                <th>Datum rođenja</th>
                <th scope="col">Početak prijave</th>
                <th scope="col">Kraj prijave</th>
                <th scope="col">Broj sati</th>
                <th scope="col">Radno mjesto</th>
                <th scope="col">Prekid rada</th>
                <th scope="col">Početak promjene</th>
                <th scope="col">Odrađeno ?</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($obradenePrijave as $driver):  ?>
            <tr  data-vozac-id="<?php echo $driver['vozac_id']; ?>">
                <td>
                    <input type="checkbox" class="row-checkbox" />
                </td>
                <td><?php echo $driver['ime'] ?></td>
                <td><?php echo $driver['prezime'] ?></td>
                <td><?php echo $driver['OIB'] ?></td>
                <td class="text-nowrap"><?php echo $driver['dob'] ?></td>
                <td class="text-nowrap"><?php echo $driver['pocetak_prijave']; ?></td>
                <td class="text-nowrap"><?php echo $driver['kraj_prijave']; ?></td>
                <td><?php echo $driver['broj_sati']; ?></td>
                <td><?php echo $driver['radno_mjesto']; ?></td>
                <td class="text-nowrap"><?php echo $driver['prekid_rada']; ?></td>
                <td class="text-nowrap"><?php echo $driver['pocetak_promjene']; ?></td>
                <td><?php if($driver['obradeno'] != 0){echo 'DA </br>'.$driver['obradeno_timestamp'];} ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <th>
                    <input type="checkbox" id="select-all-footer" />
                </th>
                <th>Ime</th>
                <th>Prezime</th>
                <th>OIB</th>
                <th>Datum rođenja</th>
                <th scope="col">Početak prijave</th>
                <th scope="col">Kraj prijave</th>
                <th scope="col">Broj sati</th>
                <th scope="col">Radno mjesto</th>
                <th scope="col">Prekid rada</th>
                <th scope="col">Početak promjene</th>
            </tr>
        </tfoot>
    </table>
</div>

<div class="modal fade " id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title placeholder-glow" id="exampleModalLabel">Svi podaci vozača </h5>
            </div>
            <div class="modal-body">
				<table class="table table-bordered border-dark text-dark" id="modalTable">
					<tbody  >
						<tr>
							<td>Ime i prezime</td>
							<td id="imePrezimeVozaca"> Neki Vozač</td>
							
						</tr>
						<tr>
							<td>Ime</td>
							<td id="ime"></td>
						</tr>
						<tr>
							<td>Prezime</td>
							<td id="prezime"></td>
						</tr>
						<tr>
							<td>OIB</td>
							<td id="oib"></td>
						</tr>
						<tr>
							<td>Datum rođenja</td>
							<td id="datumRodjenja"></td>
						</tr>
						<tr>
							<td>Vrsta zaposlenja</td>
							<td id="vrstaZaposlenja"></td>
						</tr>
						<tr>
							<td>Početak prijave</td>
							<td id="pocetakPrijave"></td>
						</tr>
						<tr>
							<td>Kraj prijave</td>
							<td id="krajPrijave"></td>
						</tr>
						<tr>
							<td>Prekid rada</td>
							<td id="prekidRada"></td>
						</tr>
						<tr>
							<td>Radno mjesto</td>
							<td id="radnoMjesto"></td>
						</tr>
						<tr>
							<td>Sati rada</td>
							<td id="satiRada"></td>
						</tr>
						<tr>
							<td>Adresa</td>
							<td id="adresa"></td>
						</tr>
						<tr>
							<td>Grad</td>
							<td id="grad"></td>
						</tr>
						<tr>
							<td>IBAN</td>
							<td id="iban"></td>
						</tr>
						<tr>
							<td>IBAN zaštičeni</td>
							<td id="ibanZasticeni"></td>
						</tr>
						<tr>
							<td>IBAN Revolut</td>
							<td id="ibanRevolut"></td>
						</tr>
					</tbody>
					
				</table>
                <!-- Content inside the modal -->
            </div>
            <div class="modal-footer">
				<form action="<?php echo base_url(); ?>/index.php/obradenoDa" class="form">
					<input id="prijavaId" type="text" name="id"  hidden value="">
					<input id="imePrezime" type="text" name="vozac" hidden  value="">
				<button type="submit" class="btn btn-success">Označi kao obrađeno</button>
				</form>
				<button id="finish-button" type="button" class="btn btn-primary">Zatvori</button>
            </div>
        </div>
    </div>
</div>



</body>

<script>
$(document).ready(function () {
    var table = $('#example').DataTable({
        ordering: false
    });

    // Handler for "select-all" checkbox in the header
    $('#select-all').on('click', function () {
        var rows = table.rows({ 'search': 'applied' }).nodes();
        $('input:checkbox', rows).prop('checked', this.checked);
    });

    // Handler for "select-all" checkbox in the footer
    $('#select-all-footer').on('click', function () {
        var rows = table.rows({ 'search': 'applied' }).nodes();
        $('input:checkbox', rows).prop('checked', this.checked);
        $('#select-all').prop('checked', this.checked);
    });

    // If any individual checkbox is unchecked, uncheck the "select-all" checkbox
    $('#example').on('change', '.row-checkbox', function () {
        if (!this.checked) {
            var selectAllChecked = $('#select-all').prop('checked');
            if (selectAllChecked) {
                $('#select-all').prop('checked', false);
                $('#select-all-footer').prop('checked', false);
            }
        }
    });
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Get modal element
    var myModal = new bootstrap.Modal(document.getElementById('myModal'));
    var finishButton = document.getElementById('finish-button');

    // Function to handle modal show and hide
    function showModal() {
        myModal.show();
    }

    // Function to handle modal content based on clicked row
    function handleRowClick(row) {
        showModal();
    }

    // Get table rows for the first table
    var tableRowsObradene = document.querySelectorAll('#example tbody tr');
    tableRowsObradene.forEach(function(row) {
        row.addEventListener('click', function() {
            handleRowClick(row);
        });
    });

    // Get table rows for the second table
    var tableRowsNeobradene = document.querySelectorAll('#exampleNeobradeno tbody tr');
    tableRowsNeobradene.forEach(function(row) {
        row.addEventListener('click', function() {
            handleRowClick(row);
        });
    });

    // Event listener for "Završi" button
    finishButton.addEventListener('click', function() {
        myModal.hide(); // This line hides the modal when "Završi" button is clicked
    });
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Get all table headers inside the table with the ID 'modalTable'
    var tableHeaders = document.querySelectorAll('#modalTable thead th');
    
    // Remove sorting classes from each table header
    tableHeaders.forEach(function(header) {
        header.classList.remove('sortable');
    });
});
</script>


<script>
document.addEventListener('DOMContentLoaded', function() {
    // Function to handle AJAX request
    function fetchData(vozacId) {
        $.ajax({
            url: '<?php echo base_url('index.php/fetchDriverData'); ?>',
            method: 'GET',
            data: {
                vozac_id: vozacId
            },
            success: function(response) {
                console.log('Response:', response);
                // Populate table with response data
                populateTable(response);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('Error:', textStatus, errorThrown);
            }
        });
    }

	function formatDate(dateString) {
    // Check if dateString is not null and not '0000-00-00'
    if (dateString && dateString !== '0000-00-00') {
        // Parse the date string
        var date = new Date(dateString);
        // Check if the parsed date is valid
        if (!isNaN(date.getTime())) {
            // Format the date as dd/mm/yyyy
            var day = date.getDate();
            var month = date.getMonth() + 1; // Months are zero indexed
            var year = date.getFullYear();
            return (day < 10 ? '0' : '') + day + '.' + (month < 10 ? '0' : '') + month + '.' + year;
        }
    }
    // Return a placeholder message if dateString is null or '0000-00-00'
    return 'Još nema datum';
}
	
    // Function to populate table with response data
    function populateTable(data) {
		var pocetakPrijaveDate = formatDate(data.pocetak_prijave);
		var krajPrijaveDate = formatDate(data.kraj_prijave);
		var prekidRadaDate = formatDate(data.prekid_rada);
		var dob = formatDate(data.dob);
        document.getElementById('exampleModalLabel').innerHTML = '<a href="https://premium-rides.com/index.php/drivers/' + data.vozac_id + '">Otvori stranicu vozača</a>';
        document.getElementById('imePrezimeVozaca').innerText = data.ime + ' ' + data.prezime;
        document.getElementById('ime').innerText = data.ime;
        document.getElementById('prezime').innerText = data.prezime;
        document.getElementById('oib').innerText = data.oib;
        document.getElementById('datumRodjenja').innerText = dob;
        document.getElementById('vrstaZaposlenja').innerText = data.vrsta_zaposlenja;
		// Parse and format dates

		// Update table cells with formatted dates
		document.getElementById('pocetakPrijave').innerText = pocetakPrijaveDate;
		document.getElementById('krajPrijave').innerText = krajPrijaveDate;
		document.getElementById('prekidRada').innerText = prekidRadaDate;
		document.getElementById('radnoMjesto').innerText = data.radno_mjesto;
        document.getElementById('satiRada').innerText = data.broj_sati;
        document.getElementById('adresa').innerText = data.adresa;
        document.getElementById('grad').innerText = data.grad;
        document.getElementById('iban').innerText = data.IBAN;
        document.getElementById('ibanZasticeni').innerText = data.zasticeniIBAN;
        document.getElementById('ibanRevolut').innerText = data.strani_IBAN;
		document.getElementById('imePrezime').value = data.ime + ' ' + data.prezime;
		document.getElementById('prijavaId').value = data.id;    }

    // Function to handle click event on table rows
    function handleRowClick(row) {
        var vozacId = row.getAttribute('data-vozac-id');
        console.log('Vozac ID:', vozacId);
        fetchData(vozacId); // Call fetchData function with vozacId
    }

    // Attach click event listeners to table rows for table with ID 'example'
    var tableRowsObradene = document.querySelectorAll('#example tbody tr');
    tableRowsObradene.forEach(function(row) {
        row.addEventListener('click', function() {
            handleRowClick(row);
        });
    });

    // Attach click event listeners to table rows for table with ID 'exampleNeobradeno'
    var tableRowsNeobradene = document.querySelectorAll('#exampleNeobradeno tbody tr');
    tableRowsNeobradene.forEach(function(row) {
        row.addEventListener('click', function() {
            handleRowClick(row);
        });
    });
});
</script>
<script>
$(document).ready(function () {
    var table = $('#exampleNeobradeno').DataTable({
        ordering: false
    });

    // Handler for "select-all" checkbox in the header
    $('#select-allNeobradeno').on('click', function () {
        var rows = table.rows({ 'search': 'applied' }).nodes();
        $('input:checkbox', rows).prop('checked', this.checked);
    });

    // Handler for "select-all" checkbox in the footer
    $('#select-all-footerNeobradeno').on('click', function () {
        var rows = table.rows({ 'search': 'applied' }).nodes();
        $('input:checkbox', rows).prop('checked', this.checked);
        $('#select-allNeobradeno').prop('checked', this.checked);
    });

    // If any individual checkbox is unchecked, uncheck the "select-all" checkbox
    $('#exampleNeobradeno').on('change', '.row-checkbox', function () {
        if (!this.checked) {
            var selectAllChecked = $('#select-allNeobradeno').prop('checked');
            if (selectAllChecked) {
                $('#select-allNeobradeno').prop('checked', false);
                $('#select-all-footerNeobradeno').prop('checked', false);
            }
        }
    });
});
</script>




</div>
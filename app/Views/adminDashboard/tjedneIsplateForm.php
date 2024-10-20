<!-- AdminLTE Stylesheet -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/css/adminlte.min.css">

<div class="container">
    <div class="row">
		<div class="col-md-4">
            <h3>Isplata sa HR banke</h3>
            <!-- Total funds for Croatian Bank -->
            <div class="alert alert-info" id="croatianTotal">
                Total: 0 EUR
            </div>
			<form id="croatianBankForm">
                <div class="form-group">
                    <label for="model">Model:</label>
                    <input type="text" class="form-control" id="model" placeholder="Unesi model" required>
                </div>
                <div class="form-group">
                    <label for="pozivNaBroj">Poziv na broj:</label>
                    <input type="text" class="form-control" id="pozivNaBroj" placeholder="Unesi poziv na broj" required>
                </div>
                <button id="generateSepaFileButton" type="submit" class="btn btn-primary mt-2">Generiraj HR sepa datoteku</button>
            </form> 
        </div>
        <div class="col-md-4">
            <h3>Ukupno za isplatu</h3>
			<div class="alert alert-secondary" id="paymentDataTotal">
                Total: 0 EUR
            </div>
		</div>
        <div class="col-md-4">
            <h3>Isplata sa Revoluta</h3>
            <!-- Total funds for Revolut -->
            <div class="alert alert-success" id="revolutTotal">
                Total: 0 EUR
            </div>
                <!-- Available Revolut Funds Input -->
			<form>
                <div class="form-group">
                    <label for="revolutFunds">Dostupan iznos:</label>
                    <input type="number" class="form-control" id="revolutFunds" placeholder="Unesi iznos koji je dostupan za isplatu s Revoluta">
                </div>
                <!-- Button to trigger the allocation of funds -->
                <button type="button" id="distributeFundsButton" class="btn btn-success mt-2">Distribuiraj sredstva</button>
		</form>
		</div>
	</div>
<hr class="hr" />
    <div class="row pt-3">
        <!-- Left Column: Croatian Bank Payment Form -->
        <div class="col-md-4">
                <!-- Croatian Bank Drop Zone -->
               <div id="croatianDropZone" class="dropzone card card-outline card-primary">
                    <div class="card-header">
                        <h4 class="card-title">Povuci ovdje za isplatu sa Hr banke</h4>
                    </div>
                    <div class="card-body">
                        <!-- Drop area for dragged items -->
                    </div>
                </div>
        </div>

        <!-- Center Column: Draggable Payment Data -->
        <div class="col-md-4">
			<div id="paymentData" class="card card-outline card-info mt-3">
				<?php foreach ($obracun as $driver): ?>
					<div class="draggable card mb-2" id="driver-<?php echo $driver['vozac_id']; ?>" 
						draggable="true" 
						data-amount="<?php echo $driver['zaIsplatu']; ?>"
						data-tjedna-isplata="<?php echo $driver['tjedna_isplata']; ?>"
						data-strani-iban="<?php echo $driver['strani_IBAN']; ?>"
						data-hr-iban="<?php echo $driver['IBAN']; ?>"
						data-zasticeni-iban="<?php echo $driver['zasticeniIBAN']; ?>"
						data-ime="<?php echo $driver['ime']; ?>"
						data-prezime="<?php echo $driver['prezime']; ?>"
						data-vozac-id="<?php echo $driver['vozac_id']; ?>">
						<div class="card-body">
							<p><strong><?php echo $driver['ime'] . ' ' . $driver['prezime']; ?></strong></p>
							<p>Isplata: <?php echo $driver['tjedna_isplata'] .'  ' .$driver['zaIsplatu']; ?> EUR</p>
							<p>HR: <?php echo $driver['IBAN']; ?></p>
							<p>Zaštičeni: <?php echo $driver['zasticeniIBAN']; ?></p>
							<p>Revolut: <?php echo $driver['strani_IBAN']; ?></p>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>

        <!-- Right Column: Revolut Payment Form -->
        <div class="col-md-4">
            <form id="revolutForm">
                <!-- Revolut Drop Zone -->
                <button type="submit" class="btn btn-success mt-2">Generiraj Revolut datoteku</button>
                <div id="revolutDropZone" class="dropzone card card-outline card-success mt-3">
                    <div class="card-header">
                        <h4 class="card-title">Povuci ovdje za isplatu sa Revoluta</h4>
                    </div>
                    <div class="card-body">
                        <!-- Drop area for dragged items -->
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Custom Styles -->
<style>
    .dropzone {
        min-height: 200px;
        border: 2px dashed #ddd;
        padding: 20px;
        margin-top: 20px;
        background-color: #f8f9fa;
    }

    .dropzone.dragging-over {
        background-color: #e2e6ea;
        border-color: #007bff;
    }

    .draggable {
        cursor: move;
        border: 2px solid #17a2b8;
        padding: 10px;
        background-color: #ffffff;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .draggable.dragging {
        opacity: 0.7;
        border-color: #007bff;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    }

    .dropzone h4 {
        text-align: center;
        color: #6c757d;
    }
</style>




<script>
document.addEventListener('DOMContentLoaded', function () {
    const revolutFundsInput = document.getElementById('revolutFunds');
    const distributeFundsButton = document.getElementById('distributeFundsButton');
    const paymentDataContainer = document.getElementById('paymentData');
    const revolutDropZone = document.getElementById('revolutDropZone');
    const croatianDropZone = document.getElementById('croatianDropZone');
    const revolutTotalDisplay = document.getElementById('revolutTotal');
    const croatianTotalDisplay = document.getElementById('croatianTotal');
    const paymentDataTotalDisplay = document.getElementById('paymentDataTotal');

    // Add drag-and-drop functionality
    const draggables = document.querySelectorAll('.draggable');
    const dropZones = [revolutDropZone, croatianDropZone];

    draggables.forEach(draggable => {
        draggable.addEventListener('dragstart', () => {
            draggable.classList.add('dragging');
        });

        draggable.addEventListener('dragend', () => {
            draggable.classList.remove('dragging');
            updateTotals();  // Update totals after manual drag-and-drop
        });
    });

    dropZones.forEach(dropZone => {
        dropZone.addEventListener('dragover', (e) => {
            e.preventDefault();
        });

        dropZone.addEventListener('drop', (e) => {
            const draggedElement = document.querySelector('.dragging');
            dropZone.querySelector('.card-body').appendChild(draggedElement);
            updateTotals();  // Update totals after manual drop
        });
    });

    revolutFundsInput.addEventListener('keypress', function (e) {
        if (e.key === 'Enter' || e.keyCode === 13) {
            e.preventDefault();  // Prevent the form submission
            distributeFundsButton.click();  // Trigger the distribute funds button click
        }
    });
	// Event listener for "Distribute Funds" button
    distributeFundsButton.addEventListener('click', function () {
        let availableFunds = parseFloat(revolutFundsInput.value);
        if (isNaN(availableFunds) || availableFunds <= 0) {
            alert("Please enter a valid amount of available funds for Revolut.");
            return;  // Exit if the input is not a valid number
        }

        resetDropzones();  // Reset both dropzones before recalculating

        // Fetch all drivers and sort them by payout in descending order
        let drivers = Array.from(paymentDataContainer.querySelectorAll('.draggable')).map(driver => {
            return {
                element: driver,
                payout: parseFloat(driver.getAttribute('data-amount')),
                straniIBAN: driver.getAttribute('data-strani-iban'),  // Get the strani_IBAN attribute for IBAN check
                tjednaIsplata: driver.getAttribute('data-tjedna-isplata')  // Get weekly payout type (Revolut, Croatian)
            };
        });

        drivers.sort((a, b) => b.payout - a.payout);  // Sort drivers by payout descending

        let allocatedDrivers = [];
        let remainingFunds = availableFunds;

        // Step 1: Prioritize drivers whose tjedna_isplata == "Revolut" and have a valid LT IBAN
        drivers.forEach(driver => {
            if (driver.tjednaIsplata === "Revolut" && driver.straniIBAN.startsWith('LT') && remainingFunds >= driver.payout) {
                revolutDropZone.querySelector('.card-body').appendChild(driver.element);
                remainingFunds -= driver.payout;
                allocatedDrivers.push(driver);
            }
        });

        // Step 2: Allocate remaining drivers who have LT IBAN (but not tjedna_isplata == "Revolut")
        drivers.forEach(driver => {
            if (!allocatedDrivers.includes(driver) && driver.straniIBAN.startsWith('LT') && remainingFunds >= driver.payout) {
                revolutDropZone.querySelector('.card-body').appendChild(driver.element);
                remainingFunds -= driver.payout;
                allocatedDrivers.push(driver);
            }
        });

        // Step 3: Move any remaining drivers to Croatian bank
        drivers.forEach(driver => {
            if (!allocatedDrivers.includes(driver)) {
                croatianDropZone.querySelector('.card-body').appendChild(driver.element);
            }
        });

        // Update the total funds displayed for each column
        updateTotals();
    });

    // Reset both dropzones by moving all drivers back to the paymentData container
    function resetDropzones() {
        const revolutDrivers = revolutDropZone.querySelectorAll('.draggable');
        const croatianDrivers = croatianDropZone.querySelectorAll('.draggable');
        
        revolutDrivers.forEach(driver => paymentDataContainer.appendChild(driver));
        croatianDrivers.forEach(driver => paymentDataContainer.appendChild(driver));

        updateTotals();  // Update totals after resetting
    }

    // Function to calculate and update the total funds for each column
    function updateTotals() {
        let revolutTotal = 0;
        let croatianTotal = 0;
        let paymentDataTotal = 0;

        // Calculate Revolut total
        const revolutDrivers = revolutDropZone.querySelectorAll('.draggable');
        revolutDrivers.forEach(driver => {
            const amountToPay = parseFloat(driver.getAttribute('data-amount'));  // Use data-amount attribute
            revolutTotal += amountToPay;
        });

        // Calculate Croatian total
        const croatianDrivers = croatianDropZone.querySelectorAll('.draggable');
        croatianDrivers.forEach(driver => {
            const amountToPay = parseFloat(driver.getAttribute('data-amount'));  // Use data-amount attribute
            croatianTotal += amountToPay;
        });

        // Calculate Payment Data total (middle column)
        const paymentDataDrivers = paymentDataContainer.querySelectorAll('.draggable');
        paymentDataDrivers.forEach(driver => {
            const amountToPay = parseFloat(driver.getAttribute('data-amount'));
            paymentDataTotal += amountToPay;
        });

        // Update the display
        revolutTotalDisplay.innerHTML = `Total: ${revolutTotal.toFixed(2)} EUR`;
        croatianTotalDisplay.innerHTML = `Total: ${croatianTotal.toFixed(2)} EUR`;
        paymentDataTotalDisplay.innerHTML = `Total: ${paymentDataTotal.toFixed(2)} EUR`;
    }

    // Initial update of totals when the page loads
    updateTotals();
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const generateRevolutFileButton = document.querySelector('#revolutForm button');

    generateRevolutFileButton.addEventListener('click', function (e) {
        e.preventDefault();  // Prevent form submission

        // Get the drivers in the Revolut Drop Zone
        const revolutDrivers = Array.from(document.querySelectorAll('#revolutDropZone .draggable')).map(driver => {
            return {
                vozac_id: driver.getAttribute('id').split('-')[1], // Extract ID
                zaIsplatu: driver.getAttribute('data-amount'),
                ime: driver.querySelector('strong').innerText.split(' ')[0],
                prezime: driver.querySelector('strong').innerText.split(' ')[1],
                IBAN: driver.querySelector('p:nth-child(3)').innerText.split(': ')[1],
                strani_IBAN: driver.querySelector('p:nth-child(5)').innerText.split(': ')[1],
                tjedna_isplata: driver.getAttribute('data-tjedna-isplata')
            };
        });

		const currentDate = new Date();
		const formattedDate = currentDate.getFullYear() + '.' + 
							  String(currentDate.getMonth() + 1).padStart(2, '0') + '.' + 
							  String(currentDate.getDate()).padStart(2, '0');

		// Set the download file name dynamically
		const fileName = formattedDate + '_Revolut_Payment.csv';
        // Make the AJAX POST request to send data to the backend
        $.ajax({
            url: '<?php echo site_url('generirajRevolutCSV'); ?>',  // Use site_url for CodeIgniter URL
            type: 'POST',
            data: JSON.stringify({ drivers: revolutDrivers }),  // Send drivers as JSON
            contentType: 'application/json',
            success: function (response) {
                // Create a download link and trigger download
                const blob = new Blob([response], { type: 'text/csv' });
                const link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = fileName;
                link.click();
            },
            error: function (error) {
                console.error('Error generating CSV:', error);
                alert('Error generating CSV. Please try again.');
            }
        });
    });
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const generateSepaFileButton = document.querySelector('#croatianBankForm button');

    generateSepaFileButton.addEventListener('click', function (e) {
        e.preventDefault();  // Prevent form submission

        // Collect the drivers in the Croatian Bank Drop Zone
        const sepaDrivers = Array.from(document.querySelectorAll('#croatianDropZone .draggable')).map(driver => {
            const vozac_id = driver.getAttribute('data-vozac-id');
            const zaIsplatu = driver.getAttribute('data-amount');

            // Only collect necessary fields: vozac_id and zaIsplatu
            return {
                vozac_id: vozac_id,
                zaIsplatu: zaIsplatu
            };
        });

        console.log('Collected driver IDs and amounts:', sepaDrivers);

        // Make sure there are drivers in the Croatian Drop Zone
        if (sepaDrivers.length === 0) {
            console.warn('No drivers available in Croatian Drop Zone.');
            alert('No drivers available in Croatian Drop Zone.');
            return;  // Exit if no drivers to process
        }
const currentDate = new Date();
const formattedDate = currentDate.getFullYear() + '.' + 
                      String(currentDate.getMonth() + 1).padStart(2, '0') + '.' + 
                      String(currentDate.getDate()).padStart(2, '0');

// Set the download file name dynamically
const fileName = formattedDate + '_SEPA_Nalog.xml';
        // Make the AJAX POST request to send data to the backend
        $.ajax({
            url: '<?php echo site_url('generirajHRSepa'); ?>',  // Use site_url for CodeIgniter URL
            type: 'POST',
            data: JSON.stringify({ drivers: sepaDrivers }),  // Send only IDs and amounts as JSON
            contentType: 'application/json',
            success: function (response) {
                console.log('SEPA file generated successfully:', response);

                // Create a download link and trigger file download
                const blob = new Blob([response], { type: 'application/xml' });
                const link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = fileName;
                console.log('Download link created. Triggering download.');
                link.click();
            },
            error: function (error) {
                console.error('Error generating SEPA XML:', error);
                alert('Error generating SEPA XML. Please try again.');
            }
        });

        console.log('AJAX request sent to the server.');
    });
});
</script>





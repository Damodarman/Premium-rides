<!-- app/Views/VehicleManagement/vehicles/step2.php -->

<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>
<div class="container">
    <!-- Progress bar for steps -->
    <?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success">
        <?= session()->getFlashdata('success') ?>
    </div>
    <?php endif ?>
    <?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger">
        <?= session()->getFlashdata('error') ?>
    </div>
    <?php endif ?>

    <div class="progress" style="height: 30px;">
        <div class="progress-bar progress-bar-striped progress-bar-animated bg-info" role="progressbar" 
             style="width: 50%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100">
            Step 2 of 4: Ownership Details
        </div>
    </div>

    <div class="d-flex justify-content-between my-3">
        <div class="text-center">
            <span class="badge bg-success rounded-circle" style="width: 30px; height: 30px;">1</span>
            <p>Vehicle Details</p>
        </div>
        <div class="text-center">
            <span class="badge bg-info rounded-circle" style="width: 30px; height: 30px;">2</span>
            <p>Ownership</p>
        </div>
        <div class="text-center">
            <span class="badge bg-secondary rounded-circle" style="width: 30px; height: 30px;">3</span>
            <p>Vehicle Status</p>
        </div>
        <div class="text-center">
            <span class="badge bg-secondary rounded-circle" style="width: 30px; height: 30px;">4</span>
            <p>Equipment</p>
        </div>
    </div>

    <!-- Display validation errors -->
    <?php if (isset($validationErrors) && !empty($validationErrors)): ?>
        <div class="alert alert-danger">
            <ul>
                <?php foreach ($validationErrors as $error): ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <!-- Form for ownership details -->
    <form action="<?= site_url('vehicles/storeStep2/' . $vehicle_id) ?>" method="post">

        <!-- Ownership Type -->
        <div class="mb-3">
            <label for="ownership_type" class="form-label">Ownership Type</label>
            <select name="ownership_type" id="ownership_type" class="form-select" required onchange="handleOwnershipTypeChange()">
                <option value="" disabled selected>Select ownership type</option>
                <option value="company_owned" <?= isset($old['ownership_type']) && $old['ownership_type'] === 'company_owned' ? 'selected' : '' ?>>Company Owned</option>
                <option value="third_party_owned" <?= isset($old['ownership_type']) && $old['ownership_type'] === 'third_party_owned' ? 'selected' : '' ?>>Third Party Owned</option>
                <option value="driver_owned" <?= isset($old['ownership_type']) && $old['ownership_type'] === 'driver_owned' ? 'selected' : '' ?>>Driver Owned</option>
                <option value="third_party_via_company" <?= isset($old['ownership_type']) && $old['ownership_type'] === 'third_party_via_company' ? 'selected' : '' ?>>Third Party via Company</option>
            </select>
        </div>

        <!-- Driver selection for driver-owned vehicles -->
        <div class="mb-3" id="driver-selection" style="display: none;">
            <label for="driver_id" class="form-label">Select Driver</label>
            <select name="driver_id" id="driver_id" class="form-select select2-driver">
                <option value="">Select Driver</option>
                <?php foreach ($activeDrivers as $driver): ?>
                    <option value="<?= $driver['id'] ?>"><?= $driver['vozac']?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Existing Owner -->
        <div class="mb-3" id="existing_owner_field" style="display: none;">
            <label for="owner_id" class="form-label">Select Existing Owner</label>
            <select name="owner_id" id="owner_id" class="form-select" onchange="handleOwnerSelectionChange()">
                <option value="" selected>None</option>
                <?php foreach ($existingOwners as $owner): ?>
                    <option value="<?= $owner['owner_id'] ?>" <?= isset($old['owner_id']) && $old['owner_id'] == $owner['owner_id'] ? 'selected' : '' ?>>
                        <?= $owner['owner_name'] ?> - <?= $owner['oib'] ?>
                    </option>
                <?php endforeach; ?>
                <option value="not_on_list">Not on the list</option>
            </select>
        </div>

        <!-- New Owner Information -->
        <div id="new_owner_fields" style="display: none;">
            <h3>New Owner Details (If Applicable)</h3>
            <div class="mb-3">
                <label for="owner_name" class="form-label">Owner Name</label>
                <input type="text" name="owner_name" id="owner_name" class="form-control <?= isset($validationErrors['owner_name']) ? 'is-invalid' : '' ?>"
                       value="<?= isset($old['owner_name']) ? esc($old['owner_name']) : '' ?>">
                <?php if (isset($validationErrors['owner_name'])): ?>
                    <div class="invalid-feedback">
                        <?= $validationErrors['owner_name'] ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <label for="owner_oib" class="form-label">OIB</label>
                <input type="text" name="owner_oib" id="owner_oib" class="form-control <?= isset($validationErrors['owner_oib']) ? 'is-invalid' : '' ?>"
                       value="<?= isset($old['owner_oib']) ? esc($old['owner_oib']) : '' ?>">
                <?php if (isset($validationErrors['owner_oib'])): ?>
                    <div class="invalid-feedback">
                        <?= $validationErrors['owner_oib'] ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <label for="contact_phone" class="form-label">Contact Phone</label>
                <input type="text" name="contact_phone" id="contact_phone" class="form-control <?= isset($validationErrors['contact_phone']) ? 'is-invalid' : '' ?>"
                       value="<?= isset($old['contact_phone']) ? esc($old['contact_phone']) : '' ?>">
                <?php if (isset($validationErrors['contact_phone'])): ?>
                    <div class="invalid-feedback">
                        <?= $validationErrors['contact_phone'] ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <label for="contact_email" class="form-label">Contact Email</label>
                <input type="email" name="contact_email" id="contact_email" class="form-control <?= isset($validationErrors['contact_email']) ? 'is-invalid' : '' ?>"
                       value="<?= isset($old['contact_email']) ? esc($old['contact_email']) : '' ?>">
                <?php if (isset($validationErrors['contact_email'])): ?>
                    <div class="invalid-feedback">
                        <?= $validationErrors['contact_email'] ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <label for="address" class="form-label">Address</label>
                <textarea name="address" id="address" class="form-control <?= isset($validationErrors['address']) ? 'is-invalid' : '' ?>"><?= isset($old['address']) ? esc($old['address']) : '' ?></textarea>
                <?php if (isset($validationErrors['address'])): ?>
                    <div class="invalid-feedback">
                        <?= $validationErrors['address'] ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <!-- Rental details and weekly price for third-party via company and company-owned -->
        <div id="rental_fields" style="display: none;">
            <h3>Rental Details</h3>

            <div class="mb-3">
                <label for="rental_details" class="form-label">Rental Details</label>
                <textarea name="rental_details" id="rental_details" class="form-control <?= isset($validationErrors['rental_details']) ? 'is-invalid' : '' ?>"><?= isset($old['rental_details']) ? esc($old['rental_details']) : '' ?></textarea>
                <?php if (isset($validationErrors['rental_details'])): ?>
                    <div class="invalid-feedback">
                        <?= $validationErrors['rental_details'] ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <label for="weekly_price" class="form-label">Weekly Price</label>
                <input type="number" name="weekly_price" id="weekly_price" class="form-control <?= isset($validationErrors['weekly_price']) ? 'is-invalid' : '' ?>" 
                       value="<?= isset($old['weekly_price']) ? esc($old['weekly_price']) : '' ?>" min="0" step="0.01">
                <?php if (isset($validationErrors['weekly_price'])): ?>
                    <div class="invalid-feedback">
                        <?= $validationErrors['weekly_price'] ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Next Step</button>
    </form>
</div>

<!-- JavaScript to handle field display and Select2 -->
<script>
function handleOwnershipTypeChange() {
    const ownershipType = document.getElementById('ownership_type').value;
    const existingOwnerField = document.getElementById('existing_owner_field');
    const newOwnerFields = document.getElementById('new_owner_fields');
    const rentalFields = document.getElementById('rental_fields');
    const driverSelection = document.getElementById('driver-selection');

    // Show rental fields for company-owned or third-party via company
    if (ownershipType === 'third_party_via_company') {
        rentalFields.style.display = 'block';
        existingOwnerField.style.display = 'block'; // Show owner selection
        newOwnerFields.style.display = 'none';
        driverSelection.style.display = 'none';
    } 
	else if( ownershipType === 'company_owned'){
        rentalFields.style.display = 'block';
        existingOwnerField.style.display = 'none'; // Show owner selection
        newOwnerFields.style.display = 'none';
        driverSelection.style.display = 'none';
	}
		
		
    // Show driver selection for driver-owned and skip to equipment
    else if (ownershipType === 'driver_owned') {
        rentalFields.style.display = 'none';
        driverSelection.style.display = 'block';
        existingOwnerField.style.display = 'none';
        newOwnerFields.style.display = 'none';
    } 
    // Skip to equipment for third-party owned (no owner or vehicle status needed)
    else if (ownershipType === 'third_party_owned') {
        rentalFields.style.display = 'none';
        existingOwnerField.style.display = 'none';
        newOwnerFields.style.display = 'none';
        driverSelection.style.display = 'none';
    }
}


function handleOwnerSelectionChange() {
    const ownerId = document.getElementById('owner_id').value;
    const newOwnerFields = document.getElementById('new_owner_fields');

    if (ownerId === 'not_on_list' || ownerId === '') {
        newOwnerFields.style.display = 'block'; // Show new owner fields if "Not on the list" or no owner selected
    } else {
        newOwnerFields.style.display = 'none'; // Hide new owner fields if an existing owner is selected
    }
}

// Initialize the form based on the initial ownership type value
document.addEventListener('DOMContentLoaded', function () {
    handleOwnershipTypeChange();
    
    // Initialize Select2 for the driver selection
    $('.select2-driver').select2({
        placeholder: "Select a driver",
        allowClear: true
    });
});
</script>

<?= $this->endSection() ?>

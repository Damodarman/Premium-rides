<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>
<div class="container">
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
    <!-- Progress bar for steps -->
    <div class="progress" style="height: 30px;">
        <div class="progress-bar progress-bar-striped progress-bar-animated bg-info" role="progressbar"
             style="width: 75%;" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100">
            Step 3 of 4: Vehicle Status
        </div>
    </div>
    <!-- Step indicators -->
    <div class="d-flex justify-content-between my-3">
        <div class="text-center">
            <span class="badge bg-success rounded-circle" style="width: 30px; height: 30px;">1</span>
            <p>Vehicle Details</p>
        </div>
        <div class="text-center">
            <span class="badge bg-success rounded-circle" style="width: 30px; height: 30px;">2</span>
            <p>Ownership</p>
        </div>
        <div class="text-center">
            <span class="badge bg-info rounded-circle" style="width: 30px; height: 30px;">3</span>
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

    <!-- Form for vehicle status -->
    <form action="<?= site_url('vehicles/storeStep3/' . $vehicle_id) ?>" method="post">
        <div class="row">
            <!-- Registration and Insurance Expiry -->
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="registration_expiry" class="form-label">Registration Expiry</label>
                    <input type="text" name="registration_expiry" id="registration_expiry" class="form-control flatpickr" value="<?= isset($old['registration_expiry']) ? esc($old['registration_expiry']) : '' ?>" required>
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-3">
                    <label for="insurance_expiry" class="form-label">Insurance Expiry</label>
                    <input type="text" name="insurance_expiry" id="insurance_expiry" class="form-control flatpickr" value="<?= isset($old['insurance_expiry']) ? esc($old['insurance_expiry']) : '' ?>" required>
                </div>
            </div>
			<!-- Insurance Provider -->
			<div class="mb-3">
				<label for="insurance_provider" class="form-label">Insurance Provider</label>
				<input 
					type="text" name="insurance_provider" id="insurance_provider" class="form-control <?= isset($validationErrors['insurance_provider']) ? 'is-invalid' : '' ?>" value="<?= isset($old['insurance_provider']) ? esc($old['insurance_provider']) : '' ?>" placeholder="Enter the insurance provider name"
				/>
				<?php if (isset($validationErrors['insurance_provider'])): ?>
					<div class="invalid-feedback">
						<?= $validationErrors['insurance_provider'] ?>
					</div>
				<?php endif; ?>
			</div>

			<!-- Insurance Policy Number -->
			<div class="mb-3">
				<label for="insurance_policy_number" class="form-label">Insurance Policy Number</label>
				<input 
					type="text" name="insurance_policy_number" id="insurance_policy_number" class="form-control <?= isset($validationErrors['insurance_policy_number']) ? 'is-invalid' : '' ?>" value="<?= isset($old['insurance_policy_number']) ? esc($old['insurance_policy_number']) : '' ?>" placeholder="Enter the insurance policy number"
				/>
				<?php if (isset($validationErrors['insurance_policy_number'])): ?>
					<div class="invalid-feedback">
						<?= $validationErrors['insurance_policy_number'] ?>
					</div>
				<?php endif; ?>
			</div>

            <!-- Service Interval -->
            <div class="col-md-12">
                <div class="mb-3">
                    <label for="service_interval" class="form-label">Service Interval (in kilometers)</label>
                    <input type="number" name="service_interval" id="service_interval" class="form-control" value="<?= isset($old['service_interval']) ? esc($old['service_interval']) : '' ?>" required>
                </div>
            </div>
        </div>

        <!-- Divider -->
        <hr>

        <!-- Weekly Checks -->
        <div class="row">
            <!-- Damage Check with Range Slider -->
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="damage_check" class="form-label">Damage Check</label>
                    <!-- Bootstrap Toggle Switch -->
                    <input type="checkbox" id="damage_check" data-toggle="toggle" data-on="Damage" data-off="No Damage" data-onstyle="danger" data-offstyle="success" class="form-control-sm">
                </div>
            </div>

<?= $this->include('partials/carDamageModal') ?>
            <!-- Wipers Check with Range Slider -->
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="wipers_check" class="form-label">Wipers Check</label>
                    <input type="range" class="form-range custom-range-slider" name="wipers_check" id="wipers_check" min="1" max="5" value="3" oninput="updateSliderColor(this)">
                    <div class="d-flex justify-content-between">
                        <span class="text-danger">Very Bad</span>
                        <span class="text-success">Excellent</span>
                    </div>
                </div>
            </div>

            <!-- Tires Check with Range Slider -->
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="tyres_check" class="form-label">Tires Check</label>
                    <input type="range" class="form-range custom-range-slider" name="tyres_check" id="tyres_check" min="1" max="5" value="3" oninput="updateSliderColor(this)">
                    <div class="d-flex justify-content-between">
                        <span class="text-danger">Very Bad</span>
                        <span class="text-success">Excellent</span>
                    </div>
                </div>
            </div>

            <!-- Lights Check (Select Dropdown) -->
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="lights_check" class="form-label">Lights Check</label>
                    <select name="lights_check" id="lights_check" class="form-select" required>
                        <option value="" disabled selected>Select</option>
                        <option value="ok" <?= isset($old['lights_check']) && $old['lights_check'] === 'ok' ? 'selected' : '' ?>>OK</option>
                        <option value="not_ok" <?= isset($old['lights_check']) && $old['lights_check'] === 'not_ok' ? 'selected' : '' ?>>Not OK</option>
                    </select>
                </div>
            </div>
			
            <div class="col-md-12">
				<div class="mb-3">
					<label for="dashboard_warning_lights" class="form-label">Dashboard Warning Lights</label>
					<textarea 
						name="dashboard_warning_lights" 
						id="dashboard_warning_lights" 
						class="form-control <?= isset($validationErrors['dashboard_warning_lights']) ? 'is-invalid' : '' ?>" 
						placeholder="Describe any warning lights if present or leave empty."
					><?= isset($old['dashboard_warning_lights']) ? esc($old['dashboard_warning_lights']) : '' ?></textarea>

					<?php if (isset($validationErrors['dashboard_warning_lights'])): ?>
						<div class="invalid-feedback">
							<?= $validationErrors['dashboard_warning_lights'] ?>
						</div>
					<?php endif; ?>
				</div>
			</div>

        <!-- Divider -->
        <hr>

        <!-- Monthly Checks -->
        <div class="row">
            <!-- Brakes Check with Range Slider -->
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="brakes_check" class="form-label">Brakes Check</label>
                    <input type="range" class="form-range custom-range-slider" name="brakes_check" id="brakes_check" min="1" max="5" value="3" oninput="updateSliderColor(this)">
                    <div class="d-flex justify-content-between">
                        <span class="text-danger">Very Bad</span>
                        <span class="text-success">Excellent</span>
                    </div>
                </div>
            </div>

            <!-- Suspension Check with Range Slider -->
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="suspension_check" class="form-label">Suspension Check</label>
                    <input type="range" class="form-range custom-range-slider" name="suspension_check" id="suspension_check" min="1" max="5" value="3" oninput="updateSliderColor(this)">
                    <div class="d-flex justify-content-between">
                        <span class="text-danger">Very Bad</span>
                        <span class="text-success">Excellent</span>
                    </div>
                </div>
            </div>

            <!-- Oil Check (Select Dropdown) -->
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="oil_check" class="form-label">Oil Check</label>
                    <select name="oil_check" id="oil_check" class="form-select" required>
                        <option value="" disabled selected>Select</option>
                        <option value="ok" <?= isset($old['oil_check']) && $old['oil_check'] === 'ok' ? 'selected' : '' ?>>OK</option>
                        <option value="needs_refill" <?= isset($old['oil_check']) && $old['oil_check'] === 'needs_refill' ? 'selected' : '' ?>>Needs Refill</option>
                        <option value="needs_change" <?= isset($old['oil_check']) && $old['oil_check'] === 'needs_change' ? 'selected' : '' ?>>Needs Change</option>
                    </select>
                </div>
            </div>

            <!-- Antifreeze Check (Select Dropdown) -->
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="antifreeze_check" class="form-label">Antifreeze Check</label>
                    <select name="antifreeze_check" id="antifreeze_check" class="form-select" required>
                        <option value="" disabled selected>Select</option>
                        <option value="ok" <?= isset($old['antifreeze_check']) && $old['antifreeze_check'] === 'ok' ? 'selected' : '' ?>>OK</option>
                        <option value="needs_refill" <?= isset($old['antifreeze_check']) && $old['antifreeze_check'] === 'needs_refill' ? 'selected' : '' ?>>Needs Refill</option>
                        <option value="needs_change" <?= isset($old['antifreeze_check']) && $old['antifreeze_check'] === 'needs_change' ? 'selected' : '' ?>>Needs Change</option>
                    </select>
                </div>
            </div>

            <!-- AdBlue Check (Select Dropdown) -->
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="adblue_check" class="form-label">AdBlue Check</label>
                    <select name="adblue_check" id="adblue_check" class="form-select" required>
                        <option value="" disabled selected>Select</option>
                        <option value="ok" <?= isset($old['adblue_check']) && $old['adblue_check'] === 'ok' ? 'selected' : '' ?>>OK</option>
                        <option value="needs_refill" <?= isset($old['adblue_check']) && $old['adblue_check'] === 'needs_refill' ? 'selected' : '' ?>>Needs Refill</option>
                    </select>
                </div>
            </div>

            <!-- Engine Mounts Check with Range Slider -->
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="engine_mounts_check" class="form-label">Engine Mounts Check</label>
                    <input type="range" class="form-range custom-range-slider" name="engine_mounts_check" id="engine_mounts_check" min="1" max="5" value="3" oninput="updateSliderColor(this)">
                    <div class="d-flex justify-content-between">
                        <span class="text-danger">Very Bad</span>
                        <span class="text-success">Excellent</span>
                    </div>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Next Step</button>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Initialize Bootstrap toggle
        $('#damage_check').bootstrapToggle();

        // Trigger the modal when the damage switch is turned on
        $('#damage_check').change(function() {
            if ($(this).prop('checked')) {
                // Open the car damage modal
                $('#carDamageModal').modal('show');
            }
        });
    });
</script>
	
	
	<script>
document.addEventListener('DOMContentLoaded', function () {
    // Initialize Flatpickr for the registration and insurance expiry fields
    flatpickr('.flatpickr', {
        altInput: true,
        altFormat: "F j, Y", // Display in a user-friendly format
        dateFormat: "Y-m-d",  // Store in the format suitable for backend
        allowInput: true,     // Allow manual input if necessary
    });
});
</script>
<!-- JavaScript for Range Slider Color Feedback -->
<script>
function updateSliderColor(rangeInput) {
    const value = rangeInput.value;
    const colors = ['#d9534f', '#f0ad4e', '#f7e567', '#5bc0de', '#5cb85c']; // Colors from red to green (1 to 5)
    rangeInput.style.setProperty('--range-color', colors[value - 1]);
}

document.addEventListener('DOMContentLoaded', function () {
    const sliders = document.querySelectorAll('.custom-range-slider');
    sliders.forEach(slider => updateSliderColor(slider));
});
</script>

<!-- Custom CSS for Range Sliders -->
<style>
.custom-range-slider {
    --range-color: #ddd;
    background: transparent;
    outline: none;
    transition: background-color 0.3s ease;
}

.custom-range-slider::-webkit-slider-runnable-track {
    background-color: var(--range-color);
}

.custom-range-slider::-moz-range-track {
    background-color: var(--range-color);
}

.custom-range-slider::-ms-track {
    background-color: var(--range-color);
}
</style>

<?= $this->endSection() ?>

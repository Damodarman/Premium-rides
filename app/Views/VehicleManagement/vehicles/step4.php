<!-- app/Views/VehicleManagement/vehicles/step4.php -->

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
        <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar" 
             style="width: 100%;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">
            Step 4 of 4: Vehicle Equipment & Safety
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
            <span class="badge bg-success rounded-circle" style="width: 30px; height: 30px;">3</span>
            <p>Vehicle Status</p>
        </div>
        <div class="text-center">
            <span class="badge bg-success rounded-circle" style="width: 30px; height: 30px;">4</span>
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

    <!-- Form starts -->
    <form action="<?= site_url('vehicles/storeStep4/' . $vehicle_id) ?>" method="post">

        <!-- Fire Extinguisher Validity -->
        <div class="mb-3">
            <label for="fire_extinguisher_validity" class="form-label">Fire Extinguisher Validity</label>
            <input type="date" name="fire_extinguisher_validity" class="form-control <?= isset($validationErrors['fire_extinguisher_validity']) ? 'is-invalid' : '' ?>" 
                   value="<?= isset($old['fire_extinguisher_validity']) ? esc($old['fire_extinguisher_validity']) : '' ?>" required>
            <?php if (isset($validationErrors['fire_extinguisher_validity'])): ?>
                <div class="invalid-feedback">
                    <?= $validationErrors['fire_extinguisher_validity'] ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- First Aid Kit Status -->
        <div class="mb-3">
            <label for="first_aid_kit_status" class="form-label">First Aid Kit Status</label>
            <select name="first_aid_kit_status" class="form-select <?= isset($validationErrors['first_aid_kit_status']) ? 'is-invalid' : '' ?>" required>
                <option value="" disabled selected>Select First Aid Kit Status</option>
                <option value="1" <?= isset($old['first_aid_kit_status']) && $old['first_aid_kit_status'] === '1' ? 'selected' : '' ?>>Present</option>
                <option value="0" <?= isset($old['first_aid_kit_status']) && $old['first_aid_kit_status'] === '0' ? 'selected' : '' ?>>Not Present</option>
            </select>
            <?php if (isset($validationErrors['first_aid_kit_status'])): ?>
                <div class="invalid-feedback">
                    <?= $validationErrors['first_aid_kit_status'] ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Yellow Paper Validity -->
        <div class="mb-3">
            <label for="yellow_paper_validity" class="form-label">Yellow Paper Validity</label>
            <input type="date" name="yellow_paper_validity" class="form-control <?= isset($validationErrors['yellow_paper_validity']) ? 'is-invalid' : '' ?>" 
                   value="<?= isset($old['yellow_paper_validity']) ? esc($old['yellow_paper_validity']) : '' ?>" required>
            <?php if (isset($validationErrors['yellow_paper_validity'])): ?>
                <div class="invalid-feedback">
                    <?= $validationErrors['yellow_paper_validity'] ?>
                </div>
            <?php endif; ?>
        </div>

        <button type="submit" class="btn btn-primary">Next Step</button>
    </form>
</div>
<?= $this->endSection() ?>

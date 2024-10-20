<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>
<div class="container">
    <!-- Progress bar for steps -->
    <div class="progress" style="height: 30px;">
        <div class="progress-bar progress-bar-striped progress-bar-animated bg-info" role="progressbar" 
             style="width: 25%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
            Step 1 of 4: Vehicle Details
        </div>
    </div>

    <!-- Step indicators -->
    <div class="d-flex justify-content-between my-3">
        <div class="text-center">
            <span class="badge bg-info rounded-circle" style="width: 30px; height: 30px;">1</span>
            <p>Vehicle Details</p>
        </div>
        <div class="text-center">
            <span class="badge bg-secondary rounded-circle" style="width: 30px; height: 30px;">2</span>
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

    <!-- Display flash messages (success or error) -->
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger">
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success">
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

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
    <form action="<?= site_url('vehicles/storeStep1') ?>" method="post">
        <!-- Make -->
        <div class="mb-3">
            <label for="make" class="form-label">Make</label>
            <input type="text" name="make" class="form-control <?= isset($validationErrors['make']) ? 'is-invalid' : '' ?>" 
                   value="<?= isset($old['make']) ? esc($old['make']) : '' ?>" required>
            <?php if (isset($validationErrors['make'])): ?>
                <div class="invalid-feedback">
                    <?= $validationErrors['make'] ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Model -->
        <div class="mb-3">
            <label for="model" class="form-label">Model</label>
            <input type="text" name="model" class="form-control <?= isset($validationErrors['model']) ? 'is-invalid' : '' ?>" 
                   value="<?= isset($old['model']) ? esc($old['model']) : '' ?>" required>
            <?php if (isset($validationErrors['model'])): ?>
                <div class="invalid-feedback">
                    <?= $validationErrors['model'] ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- License Plate -->
        <div class="mb-3">
            <label for="license_plate" class="form-label">License Plate</label>
            <input type="text" name="license_plate" class="form-control <?= isset($validationErrors['license_plate']) ? 'is-invalid' : '' ?>" 
                   value="<?= isset($old['license_plate']) ? esc($old['license_plate']) : '' ?>" required>
            <?php if (isset($validationErrors['license_plate'])): ?>
                <div class="invalid-feedback">
                    <?= $validationErrors['license_plate'] ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- VIN -->
        <div class="mb-3">
            <label for="vin" class="form-label">VIN</label>
            <input type="text" name="vin" class="form-control <?= isset($validationErrors['vin']) ? 'is-invalid' : '' ?>" 
                   value="<?= isset($old['vin']) ? esc($old['vin']) : '' ?>" required>
            <?php if (isset($validationErrors['vin'])): ?>
                <div class="invalid-feedback">
                    <?= $validationErrors['vin'] ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Year -->
        <div class="mb-3">
            <label for="year" class="form-label">Year</label>
            <input type="number" name="year" class="form-control <?= isset($validationErrors['year']) ? 'is-invalid' : '' ?>" 
                   value="<?= isset($old['year']) ? esc($old['year']) : '' ?>" required>
            <?php if (isset($validationErrors['year'])): ?>
                <div class="invalid-feedback">
                    <?= $validationErrors['year'] ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- EKO Norm -->
        <div class="mb-3">
            <label for="eko_norm" class="form-label">EKO Norm</label>
            <input type="text" name="eko_norm" class="form-control <?= isset($validationErrors['eko_norm']) ? 'is-invalid' : '' ?>" 
                   value="<?= isset($old['eko_norm']) ? esc($old['eko_norm']) : '' ?>" required>
            <?php if (isset($validationErrors['eko_norm'])): ?>
                <div class="invalid-feedback">
                    <?= $validationErrors['eko_norm'] ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Kilometers -->
        <div class="mb-3">
            <label for="kilometers" class="form-label">Kilometers</label>
            <input type="number" name="kilometers" class="form-control <?= isset($validationErrors['kilometers']) ? 'is-invalid' : '' ?>" 
                   value="<?= isset($old['kilometers']) ? esc($old['kilometers']) : '' ?>" required>
            <?php if (isset($validationErrors['kilometers'])): ?>
                <div class="invalid-feedback">
                    <?= $validationErrors['kilometers'] ?>
                </div>
            <?php endif; ?>
        </div>

        <button type="submit" class="btn btn-primary">Next Step</button>
    </form>
</div>
<?= $this->endSection() ?>

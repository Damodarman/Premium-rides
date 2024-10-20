<!-- app/Views/VehicleManagement/vehicles/create.php -->

<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>
<div class="container">

    <!-- Display error message if it exists -->
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger">
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <form action="<?= site_url('vehicles/store') ?>" method="post">
        <div class="mb-3">
            <label for="make" class="form-label">Make</label>
            <input type="text" name="make" class="form-control" value="<?= old('make') ?>" required>
        </div>
        <div class="mb-3">
            <label for="model" class="form-label">Model</label>
            <input type="text" name="model" class="form-control" value="<?= old('model') ?>" required>
        </div>
        <div class="mb-3">
            <label for="license_plate" class="form-label">License Plate</label>
            <input type="text" name="license_plate" class="form-control" value="<?= old('license_plate') ?>" required>
        </div>
        <div class="mb-3">
            <label for="vin" class="form-label">VIN</label>
            <input type="text" name="vin" class="form-control" value="<?= old('vin') ?>" required>
        </div>
        <div class="mb-3">
            <label for="year" class="form-label">Year</label>
            <input type="number" name="year" class="form-control" value="<?= old('year') ?>" required>
        </div>
        <div class="mb-3">
            <label for="eko_norm" class="form-label">EKO Norm</label>
            <input type="text" name="eko_norm" class="form-control" value="<?= old('eko_norm') ?>" required>
        </div>
        <div class="mb-3">
            <label for="kilometers" class="form-label">Kilometers</label>
            <input type="number" name="kilometers" class="form-control" value="<?= old('kilometers') ?>" required>
        </div>
        <button type="submit" class="btn btn-success">Create</button>
    </form>
</div>
<?= $this->endSection() ?>

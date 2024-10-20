<!-- app/Views/VehicleManagement/dashboard.php -->
<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>
<div class="container">
    <div class="row">
        <!-- Operating Vehicles -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3><?= $operatingVehiclesCount ?></h3>
                    <p>Operating Vehicles</p>
                </div>
                <div class="icon">
                    <i class="fas fa-car"></i>
                </div>
                <a href="<?= site_url('vehicles') ?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <!-- Non-working Vehicles -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3><?= $nonWorkingVehiclesCount ?></h3>
                    <p>Non-working Vehicles</p>
                </div>
                <div class="icon">
                    <i class="fas fa-car-crash"></i>
                </div>
                <a href="<?= site_url('vehicles/not-working') ?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <!-- Active Drivers -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3><?= $activeDriversCount ?></h3>
                    <p>Active Drivers</p>
                </div>
                <div class="icon">
                    <i class="fas fa-user-check"></i>
                </div>
                <a href="<?= site_url('driver/active') ?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <!-- Inactive Drivers -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3><?= $inactiveDriversCount ?></h3>
                    <p>Inactive Drivers</p>
                </div>
                <div class="icon">
                    <i class="fas fa-user-times"></i>
                </div>
                <a href="<?= site_url('driver/inactive') ?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <!-- Add more boxes as needed -->
    </div>
</div>

<?= $this->endSection() ?>

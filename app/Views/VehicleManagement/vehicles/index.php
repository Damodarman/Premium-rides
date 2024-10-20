<!-- app/Views/VehicleManagement/vehicles/index.php -->

<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>
<div class="container">
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success">
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif ?>
    <h1>Vehicles</h1>
    <a href="<?= site_url('vehicles/step1') ?>" class="btn btn-primary">Add New Vehicle</a>

    <!-- Vehicles Table -->
    <table id="vehiclesTable" class="table table-bordered mt-4">
        <thead>
            <tr>
                <th>ID</th>
                <th>Make</th>
                <th>Model</th>
                <th>License Plate</th>
                <th>VIN</th>
                <th>Year</th>
                <th>Kilometers</th>
                <th>Status</th> <!-- New column for status -->
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($vehicles as $vehicle): ?>
                <tr>
                    <td><?= $vehicle['vehicle_id'] ?></td>
                    <td><?= $vehicle['make'] ?></td>
                    <td><?= $vehicle['model'] ?></td>
                    <td><?= $vehicle['license_plate'] ?></td>
                    <td><?= $vehicle['vin'] ?></td>
                    <td><?= $vehicle['year'] ?></td>
                    <td><?= $vehicle['kilometers'] ?></td>
                    <td>
                        <?php if ($vehicle['completion_status']): ?>
                            <button class="btn btn-success btn-sm" disabled>Completed</button>
                        <?php else: ?>
                            <?php 
                            $nextStep = $vehicle['completion_step'] + 1; 
                            $vehicleId = $vehicle['vehicle_id']; 
                            ?>
                            <a href="<?= site_url('vehicles/step' . $nextStep . '/' . $vehicleId) ?>" class="btn btn-warning btn-sm">Incomplete</a>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="<?= site_url('vehicles/edit/' . $vehicle['vehicle_id']) ?>" class="btn btn-sm btn-warning">Edit</a>
                        <a href="<?= site_url('vehicles/delete/' . $vehicle['vehicle_id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Pagination Links -->
    <div class="d-flex justify-content-center">
        <?= $pager->links('default', 'custom_pager') ?>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#vehiclesTable').DataTable({
            paging: false, // Disable DataTables pagination
            ordering: true, // Enable sorting
            info: false, // Disable the information display
            dom: 'Bfrtip', // Show buttons for exporting
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ]
        });
    });
</script>

<?= $this->endSection() ?>

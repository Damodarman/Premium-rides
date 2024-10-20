<!-- app/Views/appReports/index.php -->

<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>
<div class="container ms-1">
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success">
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif ?>
    
    <div class="row">
        <form action="<?= site_url('taximetarReport/deleteTaximetarReport') ?>" method="post">
            <div class="form-group">
                <label for="week">Delete report for week:</label>
                <select name="week" id="week" class="form-control">
                    <option>Please select week to delete</option>
                    <?php foreach ($weekData as $week): ?>
                        <option value="<?= $week['week'] ?>">
                            Week <?= $week['week'] ?> (<?= $week['start_date'] ?> - <?= $week['end_date'] ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" class="btn btn-danger">Delete Data</button>
        </form>
    </div>
    
    <h1>Reports</h1>

    <!-- Drivers Table -->
    <table id="taximetarReportsTable" class="table table-sm table-bordered mt-4">
        <thead>
            <tr>
                <th>ID</th>
                <th>Br</th>
                <th>Driver Name</th>
                <th>Email</th>
                <th>Phone Number</th>
                <th>Total Revenue</th>
                <th>Fleet</th>
                <th>Week</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody class="text-nowrap">
            <?php foreach ($reports as $report): ?>
                <tr>
                    <td><?= $report['id'] ?></td>
                    <td><?= $report['Br'] ?></td>
                    <td>
                        <?php if (strlen($report['Ime_vozaca']) > 20): ?>
                            <span title="<?= $report['Ime_vozaca'] ?>"><?= substr($report['Ime_vozaca'], 0, 17) ?>...</span>
                        <?php else: ?>
                            <?= $report['Ime_vozaca'] ?>
                        <?php endif; ?>
                    </td>
                    <td><?= $report['Email_vozaca'] ?></td>
                    <td><?= $report['Tel_broj'] ?></td>
                    <td><?= $report['Ukupni_promet'] ?></td>
                    <td><?= $report['fleet'] ?></td>
                    <td><?= $report['week'] ?></td>
                    <td><?= $report['created_at'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Pagination Links -->
</div>

<?= $this->endSection() ?>

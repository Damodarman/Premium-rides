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
        <form action="<?= site_url('myPosReport/deleteMyPosReport') ?>" method="post">
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

    <!-- Transactions Table -->
    <table id="myPosReportsTable" class="table table-sm table-bordered mt-4">
        <thead>
            <tr>
                <th>ID</th>
                <th>Terminal Name</th>
                <th>Date Initiated</th>
                <th>Date Settled</th>
                <th>Type</th>
                <th>Transaction Reference</th>
                <th>Reference Number</th>
                <th>Description</th>
                <th>Payment From Card</th>
                <th>Amount</th>
                <th>Currency</th>
                <th>Report for Week</th>
                <th>Fleet</th>
            </tr>
        </thead>
        <tbody class="text-nowrap">
            <?php foreach ($reports as $report): ?>
                <tr>
                    <td><?= $report['id'] ?></td>
                    <td><?= $report['Terminal_name'] ?></td>
                    <td><?= $report['Date_initiated'] ?></td>
                    <td><?= $report['Date_settled'] ?></td>
                    <td><?= $report['Type'] ?></td>
                    <td><?= $report['Transaction_reference'] ?></td>
                    <td><?= $report['Reference_number'] ?></td>
                    <td><?= $report['Description'] ?></td>
                    <td><?= $report['Payment_from_card'] ?></td>
                    <td><?= $report['Amount'] ?></td>
                    <td><?= $report['Currency'] ?></td>
                    <td><?= $report['report_for_week'] ?></td>
                    <td><?= $report['fleet'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Pagination Links -->
</div>

<?= $this->endSection() ?>

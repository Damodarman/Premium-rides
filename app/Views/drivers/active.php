<!-- app/Views/appReports/index.php -->

<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>
<div class="container ms-1">
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success">
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif ?>
    
    
    <h1>Drivers </h1>

    <!-- Drivers Table -->
    <table id="driverReportsTable" class="table table-sm table-bordered mt-4">
        <thead>
            <tr>
                <th>ID</th>
                <th>Driver Name</th>
                <th>Email</th>
                <th>Phone Number</th>
                <th>City</th>
                <th>Country</th>
                <th>Working Hours</th>
                <th>Commission Type</th>
                <th>Commission Amount</th>
                <th>Discount on Commission</th>
                <th>Postcode</th>
                <th>Working Status</th>
                <th>Referral Reward</th>
            </tr>
        </thead>
        <tbody class="text-nowrap">
            <?php foreach ($drivers as $driver): ?>
                <tr>
                    <td><?= $driver['id'] ?></td>
                    <td><?= $driver['vozac'] ?></td>
                    <td><?= $driver['email'] ?></td>
                    <td><?= $driver['mobitel'] ?></td>
                    <td><?= $driver['grad'] ?></td>
                    <td><?= $driver['drzava'] ?></td>
                    <td><?= $driver['broj_sati'] ?></td>
                    <td><?= $driver['vrsta_provizije'] ?></td>
                    <td><?= $driver['iznos_provizije'] ?></td>
                    <td><?= $driver['popust_na_proviziju'] ?></td>
                    <td><?= $driver['postanskiBroj'] ?></td>
                    <td><?= $driver['aktivan'] == '1' ? 'Active' : 'Inactive' ?></td>
                    <td><?= $driver['referal_reward'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Pagination Links -->
</div>

<?= $this->endSection() ?>

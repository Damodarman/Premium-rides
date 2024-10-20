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
		<form action="<?= site_url('uberReport/deleteUberReport') ?>" method="post">
			<div class="form-group">
				<label for="week">Delete report for week:</label>
				<select name="week" id="week" class="form-control">
					<option>Please select week to delet</option>
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
    <table id="uberReportsTable" class=" table table-sm table-bordered mt-4">
        <thead>
            <tr>
                <th>ID</th>
                <th>Driver UUID</th>
                <th>Driver</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Total Earnings</th>
                <th>Total Net Earnings</th>
                <th>Refunds & Costs</th>
                <th>Payments</th>
                <th>Transferred to Bank Account</th>
                <th>Cash Payment</th>
                <th>Refunds - Airport Fees</th>
                <th>Tips</th>
                <th>Lost Item Refunds</th>
                <th>Promotions</th>
                <th>Report for Week</th>
                <th>Refunds - Toll Fees</th>
            </tr>

		</thead>
        <tbody class="text-nowrap">
            <?php foreach ($reports as $report): ?>
                <tr>
                    <td><?= $report['id'] ?></td>
<td>
    <?php if (strlen($report['UUID_vozaca']) > 13): ?>
        <span title="<?= $report['UUID_vozaca'] ?>"><?= substr($report['UUID_vozaca'], 0, 10) ?>...</span>
    <?php else: ?>
        <?= $report['UUID_vozaca'] ?>
    <?php endif; ?>
</td>

<td>
    <?php if (strlen($report['Vozac']) > 20): ?>
        <span title="<?= $report['Vozac'] ?>"><?= substr($report['Vozac'], 0, 17) ?>...</span>
    <?php else: ?>
        <?= $report['Vozac'] ?>
    <?php endif; ?>
</td>

<td>
    <?php if (strlen($report['Vozacevo_ime']) > 13): ?>
        <span title="<?= $report['Vozacevo_ime'] ?>"><?= substr($report['Vozacevo_ime'], 0, 10) ?>...</span>
    <?php else: ?>
        <?= $report['Vozacevo_ime'] ?>
    <?php endif; ?>
</td>

<td>
    <?php if (strlen($report['Vozacevo_prezime']) > 13): ?>
        <span title="<?= $report['Vozacevo_prezime'] ?>"><?= substr($report['Vozacevo_prezime'], 0, 10) ?>...</span>
    <?php else: ?>
        <?= $report['Vozacevo_prezime'] ?>
    <?php endif; ?>
</td>
                    <td><?= $report['Ukupna_zarada'] ?></td>
                    <td><?= $report['Ukupna_zarada_Neto_cijena'] ?></td>
                    <td><?= $report['Povrati_i_troskovi'] ?></td>
                    <td><?= $report['Isplate'] ?></td>
                    <td><?= $report['Isplate_Preneseno_na_bankovni_racun'] ?></td>
                    <td><?= $report['Isplate_Naplaceni_iznos_u_gotovini'] ?></td>
                    <td><?= $report['Povrati_i_troskovi_Povrati_Pristojba_za_zracnu_luku'] ?></td>
                    <td><?= $report['Ukupna_zarada_Napojnica'] ?></td>
                    <td><?= $report['Ukupna_zarada_Ostala_zarada_Povrat_izgubljenih_predmeta'] ?></td>
                    <td><?= $report['Ukupna_zarada_Promocije'] ?></td>
                    <td><?= $report['report_for_week'] ?></td>
                    <td><?= $report['Povrati_i_troskovi_Povrati_Cestarina'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Pagination Links -->
</div>

<?= $this->endSection() ?>

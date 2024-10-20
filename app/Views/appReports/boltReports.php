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
		<form action="<?= site_url('boltReport/deleteBoltReport') ?>" method="post">
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
<table id="boltReportsTable" class="table table-sm table-bordered mt-4">
    <thead>
        <tr>
            <th>ID</th>
            <th>Driver</th>
            <th>Phone Number</th>
            <th>Period</th>
            <th>Gross Amount</th>
            <th>Cancellation Fee</th>
            <th>Reservation Payment Fee</th>
            <th>Reservation Deduction Fee</th>
            <th>Toll Fee</th>
            <th>Bolt Fee</th>
            <th>Cash Rides Collected Cash</th>
            <th>Cash Rides Discount from Bolt</th>
            <th>Bonus</th>
            <th>Compensation</th>
            <th>Refunds</th>
            <th>Tip</th>
            <th>Weekly Account Balance</th>
            <th>Hours on Network</th>
            <th>Utilization</th>
            <th>Report for Week</th>
        </tr>
    </thead>
    <tbody class="text-nowrap">
        <?php foreach ($reports as $report): ?>
            <tr>
                <td><?= $report['id'] ?></td>
                <td>
                    <?php if (strlen($report['Vozac']) > 20): ?>
                        <span title="<?= $report['Vozac'] ?>"><?= substr($report['Vozac'], 0, 17) ?>...</span>
                    <?php else: ?>
                        <?= $report['Vozac'] ?>
                    <?php endif; ?>
                </td>
                <td><?= $report['Telefonski_broj_vozaca'] ?></td>
                <td><?= $report['Period'] ?></td>
                <td><?= $report['Bruto_iznos'] ?></td>
                <td><?= $report['Otkazna_naknada'] ?></td>
                <td><?= $report['Naknada_za_rezervaciju_placanje'] ?></td>
                <td><?= $report['Naknada_za_rezervaciju_odbitak'] ?></td>
                <td><?= $report['Naknada_za_cestarinu'] ?></td>
                <td><?= $report['Bolt_naknada'] ?></td>
                <td><?= $report['Voznje_placene_gotovinom_prikupljena_gotovina'] ?></td>
                <td><?= $report['Popusti_na_vožnje_na_gotovinu_od_Bolt'] ?></td>
                <td><?= $report['Bonus'] ?></td>
                <td><?= $report['Nadoknade'] ?></td>
                <td><?= $report['Povrati_novca'] ?></td>
                <td><?= $report['Napojnica'] ?></td>
                <td><?= $report['Tjedno_stanje_racuna'] ?></td>
                <td><?= $report['Sati_na_mrezi'] ?></td>
                <td><?= $report['Utilization'] ?></td>
                <td><?= $report['report_for_week'] ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>


</div>

<?= $this->endSection() ?>

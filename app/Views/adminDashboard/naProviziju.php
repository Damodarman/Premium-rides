
<?php
use CodeIgniter\I18n\Time;  // Import the Time class

$now = Time::now(); // Get current date and time (returns a DateTime object)
//echo $cMHAN .'</br>';
//echo $cMHON .'</br>';
//echo $cWHAN .'</br>';
//echo $cWHON .'</br>';

?>

<!--FIRST ROW-->
<div class="container">
	<div class="row pt-3 alert alert-info">
				<div  class="col-6 pt-2">
						Ovi podaci su kreirani <?= $cacheCreationTime ?> za <?= $driversCount ?> vozača. 
				</div>
				<div  class="col-6">
				<form method="post" action="<?= site_url('naProviziju');?>"> 
					<input type="hidden" name="refreshCache" value="1">
					<?= csrf_field(); ?> 
					<div class="d-grid gap-2 col-6 mx-auto">
					 <button type="submit" class="btn btn-primary" onclick="return confirm('Are you sure you want to refresh the data?');">Obnovi podatke</button>
					</div>
				</form>
				</div>
	</div>
		<table class="table table-dark table-borderless table-sm table-hover table-borderless text-center">
			<thead>
				<tr>
					<th scope="col">#</th>
					<th scope="col">Vozač</th>
					<th scope="col">Prijava</th>
					<th scope="col">Sati prošli tjedan aktivno</th>
					<th scope="col">Sati ovaj mjesec aktivno</th>
					<th scope="col">Sati prošli mjesec aktivno</th>
				</tr>
			</thead>
			<tbody>
				<?php 

				
				
				
				foreach($activity as $driver): ?>
				<?php
					$mHAN = $driver['satiMonth'];
					$wHAN = $driver['satiWeek'] ;
					$mHON = $mHAN * 1.3;
					$wHON = $wHAN * 1.3;

				// Get day, month, etc.
				$today = (int) $now->format('d');
				$currentMonth = (int) $now->format('m');
				$daysInMonth = (int) $now->format('t'); // 't' gives total days in the month
				$dayOfWeek = (int) $now->format('w'); 
				// Calculate week percentage (assuming weeks start on Sunday)
				if ($dayOfWeek == 0) {
					$weekPercent = 100; // Sunday is considered the end of the week
				} else {
					$weekPercent = round(($dayOfWeek / 7) * 100, 2);
				}

				// Calculate month percentage
				$monthPercent = round(($today / $daysInMonth) * 100, 2);
				$cMHAN = round(($monthPercent / 100) * $mHAN);
				$cMHON = round(($monthPercent / 100) * $mHON);

				$cWHAN = round(($weekPercent / 100) * $wHAN);
				$cWHON = round(($weekPercent / 100) * $wHON);
				?>
				<tr>
					<th class="border-bottom border-light" scope="row" id="driver_<?= $driver['id'] ?>"><a class="text-decoration-none " href="<?php echo site_url('drivers/'). '/' .$driver['id']?>"><?php echo $driver['id'] ?></a></th>
					<td class="border-bottom border-light"><?= $driver['vozac'] ?></td>
					<td class="border-bottom border-light"><?php echo $driver['prijava'] ?></td>
					<td <?php if($driver['ubLastWeekActiv'] > $wHAN){echo ' class="text-danger border-bottom border-light"';}elseif($driver['ubLastWeekActiv'] < $wHAN){echo ' class="text-success border-bottom border-light"';} ?>><?= $driver['ubLastWeekActiv'] .' od ' .$wHAN  ?></td>
					<td <?php if($driver['ubThisMonthActiv'] > $cMHAN){echo ' class="text-danger border-bottom border-light"';}elseif($driver['ubThisMonthActiv'] < $cMHAN){echo ' class="text-success border-bottom border-light"';} ?>><?= $driver['ubThisMonthActiv'] .' od ' .$cMHAN   ?></td>
					<td <?php if($driver['ubLastMonthActiv'] > $mHAN){echo ' class="text-danger border-bottom border-light"';}elseif($driver['ubLastMonthActiv'] < $mHAN){echo ' class="text-success border-bottom border-light"';} ?>><?= $driver['ubLastMonthActiv'] .' od ' .$mHAN  ?></td>
				</tr>
				<?php endforeach ?>
			</tbody>
			<tfoot>
				<tr>
					<th scope="col">#</th>
					<th scope="col">Vozač</th>
					<th scope="col">Prijava</th>
					<th scope="col">Sati prošli tjedan aktivno</th>
					<th scope="col">Sati ovaj mjesec aktivno</th>
					<th scope="col">Sati prošli mjesec aktivno</th>
				</tr>
			</tfoot>
		</table>
	</div>
</div>




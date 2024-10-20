
<?php
	$mHAN = 200;
	$wHAN = 50;
	$mHON = $mHAN * 1.3;
	$wHON = $wHAN * 1.3;
use CodeIgniter\I18n\Time;  // Import the Time class

$now = Time::now(); // Get current date and time (returns a DateTime object)

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
//echo $cMHAN .'</br>';
//echo $cMHON .'</br>';
//echo $cWHAN .'</br>';
//echo $cWHON .'</br>';

?>

<div class="container pt-5">
<!--FIRST ROW-->
	<div class="row">
		<table class="table table-dark table-borderless table-sm table-hover table-borderless text-center">
			<thead>
				<tr>
					<th scope="col">#</th>
					<th scope="col">Vozač</th>
					<th scope="col">Sati ovaj tjedan online</th>
					<th scope="col">Sati ovaj tjedan aktivno</th>
					<th scope="col">Sati prošli tjedan online</th>
					<th scope="col">Sati prošli tjedan aktivno</th>
					<th scope="col">Sati ovaj mjesec online</th>
					<th scope="col">Sati ovaj mjesec aktivno</th>
					<th scope="col">Sati prošli mjesec online</th>
					<th scope="col">Sati prošli mjesec aktivno</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($activity as $driver): ?>
				<tr>
					<th scope="row" id="driver_<?= $driver['id'] ?>"><a class="text-decoration-none" href="<?php echo site_url('drivers/'). '/' .$driver['id']?>"><?php echo $driver['id'] ?></a></th>
					<td><?= $driver['vozac'] ?></td>
					<td <?php if($driver['ubThisWeekOnlin'] > $cWHON){echo ' class="text-success border border-success"';}elseif($driver['ubThisWeekOnlin'] < $cWHON){echo ' class="text-danger border border-danger"';} ?>><?= $driver['ubThisWeekOnlin'] ?></td>
					<td <?php if($driver['ubThisWeekActiv'] > $cWHAN){echo ' class="text-success border border-success"';}elseif($driver['ubThisWeekActiv'] < $cWHAN){echo ' class="text-danger border border-danger"';} ?>><?= $driver['ubThisWeekActiv'] ?></td>
					<td <?php if($driver['ubLastWeekOnlin'] > $wHON){echo ' class="text-success border border-success"';}elseif($driver['ubLastWeekOnlin'] < $wHON){echo ' class="text-danger border border-danger"';} ?>><?= $driver['ubLastWeekOnlin'] ?></td>
					<td <?php if($driver['ubLastWeekActiv'] > $wHAN){echo ' class="text-success border border-success"';}elseif($driver['ubLastWeekActiv'] < $wHAN){echo ' class="text-danger border border-danger"';} ?>><?= $driver['ubLastWeekActiv'] ?></td>
					<td <?php if($driver['ubThisMonthOnlin'] > $cMHON){echo ' class="text-success border border-success"';}elseif($driver['ubThisMonthOnlin'] < $cMHON){echo ' class="text-danger border border-danger"';} ?>><?= $driver['ubThisMonthOnlin'] ?></td>
					<td <?php if($driver['ubThisMonthActiv'] > $cMHAN){echo ' class="text-success border border-success"';}elseif($driver['ubThisMonthActiv'] < $cMHAN){echo ' class="text-danger border border-danger"';} ?>><?= $driver['ubThisMonthActiv'] ?></td>
					<td <?php if($driver['ubLastMonthOnlin'] > $mHON){echo ' class="text-success border border-success"';}elseif($driver['ubLastMonthOnlin'] < $mHON){echo ' class="text-danger border border-danger"';} ?>><?= $driver['ubLastMonthOnlin'] ?></td>
					<td <?php if($driver['ubLastMonthActiv'] > $mHAN){echo ' class="text-success border border-success"';}elseif($driver['ubLastMonthActiv'] < $mHAN){echo ' class="text-danger border border-danger"';} ?>><?= $driver['ubLastMonthActiv'] ?></td>
				</tr>
				<?php endforeach ?>
			</tbody>
			<tfoot>
				<tr>
					<th scope="col">#</th>
					<th scope="col">Vozač</th>
					<th scope="col">Sati ovaj tjedan online</th>
					<th scope="col">Sati ovaj tjedan aktivno</th>
					<th scope="col">Sati prošli tjedan online</th>
					<th scope="col">Sati prošli tjedan aktivno</th>
					<th scope="col">Sati ovaj mjesec online</th>
					<th scope="col">Sati ovaj mjesec aktivno</th>
					<th scope="col">Sati prošli mjesec online</th>
					<th scope="col">Sati prošli mjesec aktivno</th>
				</tr>
			</tfoot>
		</table>
	</div>
</div>




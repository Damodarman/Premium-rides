<div class="container">
    <h2>Tablica dugova</h2>

    <table class="table" id="dugovaTable">
        <thead class="thead-dark">
            <tr>
                <th>Driver ID</th>
                <th>Driver Name</th>
                <?php foreach ($last5Weeks as $weekIdentifier): ?>
                    <th class="text-center">Week <?= $weekIdentifier ?></th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
			<?php 
			$ukupno = 0;
			$zbroj = 0;
							$ukupnoVozac = array();
							$zbrojVozac= array();
			$driverAverage = array();
			?>
            <?php foreach ($drivers as $driver): ?>
                <?php if (isset($driver['vozac_id'])): ?>
			<?php $driverID = $driver['vozac']; 
				$driverID = str_replace(": ", "-", $driverID);
				$driverID = str_replace("č", "c", $driverID);
				$driverID = str_replace("ć", "c", $driverID);
				$driverID = str_replace("š", "s", $driverID);
				$driverID = str_replace("ž", "z", $driverID);
				$driverID = str_replace("đ", "d", $driverID);
				$driverID = str_replace(" ", "-", $driverID);
				$driverID = str_replace(":", "-", $driverID);
			?>
                    <tr id="<?=$driverID ?>" style="background-color:">
                        <td><?= $driver['vozac_id'] ?></td>
                        <td><?= $driver['vozac'] ?></td>
							<?php 
							$ukupnoVozac[$driverID] = 0;
							$zbrojVozac[$driverID] = 0;
						?>

                        <?php foreach ($last5Weeks as $weekIdentifier): ?>
                            <td class="text-center <?php if($driver['week_' . $weekIdentifier] == 0): ?> text-success font-weight-bold fs-5 <?php endif ?>"><?= $driver['week_' . $weekIdentifier] ?></td>
							<?php $ukupno += $driver['week_' . $weekIdentifier]; ?>
							<?php 
								//if($driver['week_' . $weekIdentifier] != 0){
								$zbroj += 1;
								//}?>
							<?php $ukupnoVozac[$driverID] += $driver['week_' . $weekIdentifier]; ?>
							<?php 
//								if($driver['week_' . $weekIdentifier] != 0){
									$zbrojVozac[$driverID] += 1;
//								}
							
							?>
                        <?php endforeach; ?>
						<?php 
							$driverAverage[$driverID] = $ukupnoVozac[$driverID] / $zbrojVozac[$driverID]
						?>
                    </tr>
                <?php endif; ?>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>


<?php

$average = $ukupno / $zbroj;
/*
echo '<pre>';
echo $average;
print_r($driverAverage);
*/

?>

<script>
    console.log("Script is running!");

    var $driverAverage = <?php echo json_encode($driverAverage); ?>;
    var $average = <?php echo $average; ?>;

    console.log("$driverAverage:", $driverAverage);

function calculateColor(value) {
    // Convert the value to a percentage between -100 and 100
    var percentage = (value - $average) / $average * 100;

    // Adjust the hue based on the percentage
    var hue;
    if (percentage > 0) {
        hue = 70 - (percentage * 1.2); // Green to yellow
    } else {
        hue = -percentage * 1.2; // Yellow to red
    }

    // Ensure the hue is within the valid range
    hue = Math.max(0, Math.min(70, hue));

    // Calculate the saturation and lightness
    var saturation = 77; // Fixed saturation
    var lightness = 40; // Fixed lightness

    // Convert HSL to RGB
    var color = 'hsl(' + hue + ', ' + saturation + '%, ' + lightness + '%)';

    return color;
}
	
	
	
	Object.keys($driverAverage).forEach(function (id) {
        var row = document.getElementById(id);

        if (row) {
            var averageValue = $driverAverage[id];
            var color = calculateColor(averageValue);
			console.log("Before setting color for Row ID:", id);
			row.style.setProperty('background-color', color);
			console.log("After setting color for Row ID:", id);
            console.log("Row ID:", id, "Color:", color);
        } else {
            console.log("Row with ID", id, "not found.");
        }
    });
</script>


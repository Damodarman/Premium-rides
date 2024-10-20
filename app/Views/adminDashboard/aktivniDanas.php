<div class="container">
<div class="row">
<table class="table table-striped">
	
	<?php
	echo "<thead>";
echo "<tr>";
echo "<th>Ime i prezime</th>";
echo "<th>Datum rođenja</th>";
echo "<th>OIB</th>";
echo "<th>Broj sati</th>";
echo "<th>Radno mjesto</th>";
echo "<th>Početak prijave</th>";
echo "</tr>";
echo "</thead>";
	
	
	foreach ($activeDrivers as $driver) {
  echo "<tr>";
  echo "<td>" . $driver["ime"]." ".$driver["prezime"] . "</td>";
  echo "<td>" . $driver["dob"] . "</td>";
  echo "<td>" . $driver["oib"] . "</td>";
  echo "<td>" . $driver["broj_sati"] . "</td>";
  echo "<td>" . $driver["radno_mjesto"] . "</td>";
  echo "<td>" . $driver["pocetak_prijave"] . "</td>";
  echo "</tr>";
} 
	?>
	</table>

</div></div>
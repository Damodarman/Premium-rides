<div class="container">
    <div class="row">
        <?php if (session()->has('dugPlacen')){ ?>
            <div class="alert <?=session()->getFlashdata('alert-class') ?>">
                <?=session()->getFlashdata('dugPlacen') ?>
            </div>
        <?php } ?>
		<a href="<?php echo base_url('/index.php/dugovi/tablicaDugova')?>" class="btn btn-sm btn-warning">Pogledaj tablicu dugova</a>
		</br>
        <input type="text" id="searchInput" placeholder="Pretraži po iznosu...">
        <table class="table table-dark table-striped">
            <thead>
                <tr>
                    <th scope="col">Vozac</th>
                    <th scope="col">Iznos</th>
                    <th scope="col">Neto Plaća</th>
                    <th scope="col">Plaćeno ?</th>
                    <th scope="col">Uskrata na Plaći</th>
                </tr>
				 <tr>
                    <th scope="col">Total:</th>
                    <th scope="col" id="totalIznos"></th>
                    <th scope="col"></th>
                    <th scope="col"></th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $totalIznos = 0; // Initialize the total iznos variable
		   		$totalNetoPlace = 0;
                foreach($dugovi as $dug): 
                    $totalIznos += $dug['iznos']; // Add each iznos to the total
                    $totalNetoPlace += $dug['netoPlaca']; // Add each iznos to the total
                ?>
                <tr>
                    <td><a href="<?php echo base_url('/index.php/drivers/'). '/' .$dug['vozac_id']?>"><?php echo $dug['vozac'] ?></a> </td>
					<?php if($role == 'admin'): ?>
                    <td><a href="<?php echo base_url('/index.php/dug/'). '/' .$dug['id']?>" class=""><?php echo $dug['iznos'] ?></a></td>
					<?php else: ?>
                    <td> <?php echo $dug['iznos'] ?></td>
					<?php endif ?>
					<?php 
						$razlika = $dug['iznos'] + $dug['netoPlaca'];
						if($razlika > 0): ?>
                     <td class="text-warning"> <?php echo $dug['netoPlaca'] ?></td>
					<?php else: ?>
                     <td class="text-danger"> <?php echo $dug['netoPlaca'] ?></td>
					<?php endif ?>
                   <td> <a href="<?php echo base_url('/index.php/dugPlacen/'). '/' .$dug['id']?>" class="btn btn-sm btn-success">Plaćeno</a></td>
                    <td><a href="<?php echo base_url('/index.php/dugovi/kreirajUskratu/'). '/' .$dug['id']?>"> Kreiraj uskratu</a> </td>
              </tr>
                <?php endforeach ?>
            </tbody>
            <tfoot>
                <tr>
                    <th>Total:</th>
                    <th><?php echo $totalIznos; ?></th> <!-- Display the total iznos here -->
                    <th><?php echo $totalNetoPlace; ?></th> <!-- Leave this column empty in the footer -->
                    <th></th> <!-- Leave this column empty in the footer -->
                    <th></th> <!-- Leave this column empty in the footer -->
                </tr>
            </tfoot>
        </table>
    </div>
</div>







<script>
// PHP variable containing the totalIznos value
var totalIznosValue = <?php echo $totalIznos; ?>;

// Update the content of the HTML element with id "totalIznos"
document.getElementById('totalIznos').textContent = totalIznosValue;
</script>

<script>
    $(document).ready(function () {
        // Function to filter rows based on the search input
        $("#searchInput").on("input", function () {
            var searchText = $(this).val().toLowerCase().replace(".", ",");
            $("table tbody tr").each(function () {
                var iznosText = $(this).find("td:eq(1)").text().toLowerCase().replace(".", ",");
                if (iznosText.indexOf(searchText) === -1) {
                    $(this).hide();
                } else {
                    $(this).show();
                }
            });
        });
    });
</script>
<div class="container">
<div class="row">
	<div class="col-4 bg-dark"></div>
	<div class="col-4 bg-dark">
		<h2 class="text-center text-white bg-danger rounded-pill mt-3 mb-3"> Gotovi obračuni</h2>
	</div>
	<div class="col-4 bg-dark"></div>

</div>
<div class="row bg-dark">
	<table class="table table-dark table-striped table-sm">	
		 <thead>
			<tr>
			  <th class="text-center" scope="col">Tjedan</th>
			  <th class="text-center" scope="col">Neto</th>
			  <th class="text-center" scope="col">Gotovina</th>
			  <th class="text-center" scope="col">Razlika</th>
			  <th class="text-center" scope="col">Provizija</th>
			  <th class="text-center" scope="col">Fiskalizacija</th>
			  <th class="text-center" scope="col">Naplačeni troškovi</th>
			  <th class="text-center" scope="col">Za Isplatu</th>
			  <th class="text-center" scope="col">Zarada Firme</th>
			  <th class="text-center" scope="col">Obriši obračun</th>
			</tr>
		  </thead>
		<tbody>
			
			<?php 
			if(isset($gotoviObracuni)){
			foreach($gotoviObracuni as $obracun): ?>
			<tr>
			  <th class="text-center" scope="row"><a class="nav-link" href="<?php echo base_url('/index.php/obracun/'.$obracun['week'])?>">Obračun za <?php echo $obracun['week'] ?></a></th>
			  <td class="text-center"><?php echo $obracun['ukupnoNetoSvi']; ?></td>
			  <td class="text-center"><?php echo $obracun['ukupnoGotovinaSvi']; ?></td>
			  <td class="text-center"><?php echo $obracun['ukupnoRazlikaSvi']; ?></td>
			  <td class="text-center"><?php echo $obracun['provizija']; ?></td>
			  <td class="text-center"><?php echo $obracun['firmaFiskalizacija']; ?></td>
			  <td class="text-center"><?php echo $obracun['naplaceniTroskovi']; ?></td>
			  <td class="text-center"><?php echo $obracun['zaIsplatu']; ?></td>
			  <td class="text-center"><?php echo $obracun['zaradaFirme']; ?></td>
			  <td class="text-center">
				  <div class="dropend text-danger">
				  	<a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false"><i class="bi bi-trash" style="font-size: 1.5rem; color: red;"></i></a>
					<ul class="dropdown-menu bg-dark" aria-labelledby="dropdownMenuLink">
						<li><a class="dropdown-item bg-dark text-danger" href="<?php echo base_url('/index.php/obrDel/'). '/' .$obracun['week']?>">Obriši obračun</a></li>
					</ul>
				  </div>
				</td>
			</tr>
			<?php endforeach ; }?>
			
		</tbody>
	</table>
	<?php if (session()->has('obracunDelete')){ ?>
		<div class="alert bg-dark text-center <?=session()->getFlashdata('alert-class') ?>">
			<h2 class="text-center text-white bg-danger mt-3 mb-3"><?=session()->getFlashdata('obracunDelete') ?></h2>
		</div>
	<?php } ?>
</div>
<div class="row bg-dark">
	<div class="col-4 bg-dark"></div>
	<div class="col-4 bg-dark">
		<h2 class="text-center text-white bg-danger rounded-pill mt-3 mb-3"> Spremno za obračunati</h2>
	</div>
	<div class="col-4 bg-dark"></div>
</div>
<div class="row bg-dark text-center">
	<div class="list-group bg-dark text-center mb-3">
		<?php if($dostupnoZaObracun != 0): ?>
			<?php foreach($dostupnoZaObracun as $dZO) : ?>
			  <a href="<?php echo base_url('/index.php/obracunaj/'). '/' .$dZO['week']?>" class="list-group-item list-group-item-action bg-dark text-white border-top border-danger ms-2 me-2"> Klikni me i napravi obračun za <?php echo $dZO['week'] ; ?> tjedan.</a>
			<?php endforeach ?>
		<?php else: ?>
	  <a href="#" class="list-group-item list-group-item-action bg-dark text-white border-top border-danger ms-2 me-2"> Obračuni po svim dostupnim reportima su napravljeni !!</a>
		<?php endif ?>
	</div>	
</div>
</div>


<script>
    document.addEventListener("DOMContentLoaded", function() {
        const dropdownToggles = document.querySelectorAll('.dropdown-toggle');

        dropdownToggles.forEach(function(dropdownToggle) {
            const dropdownMenu = dropdownToggle.nextElementSibling;

            dropdownToggle.addEventListener('click', function(event) {
                event.preventDefault();
                event.stopPropagation();

                if (dropdownMenu.style.display === 'block') {
                    dropdownMenu.style.display = 'none';
                } else {
                    closeAllDropdowns(); // Close all other dropdowns before opening this one
                    dropdownMenu.style.display = 'block';
                }
            });
        });

        // Close all dropdowns when clicking outside
        document.addEventListener('click', function(event) {
            if (!event.target.classList.contains('dropdown-toggle')) {
                closeAllDropdowns();
            }
        });

        function closeAllDropdowns() {
            const allDropdownMenus = document.querySelectorAll('.dropdown-menu');

            allDropdownMenus.forEach(function(dropdownMenu) {
                dropdownMenu.style.display = 'none';
            });
        }
    });
</script>



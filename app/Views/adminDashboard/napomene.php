<div class="container">
	<div class="row">
		  <table id="example" class="table table-striped table-dark table-sm" style="width:100%">
            <thead>
                <tr>
                    <th>Vrijeme i datum</th>
                    <th>Napomenu dodao</th>
                    <th>Vozač</th>
                    <th>Napomena</th>
                </tr>
            </thead>
            <tbody>
				<?php foreach($napomene as $napomena):  ?>
                <tr>
					<td><?php echo $napomena['timestamp'] ?></td>
                    <td><?php echo $napomena['user'] ?></td>

					<td><a class="text-decoration-none" href="<?php echo base_url('/index.php/drivers/'). '/' .$napomena['driver_id']?>"><?php echo $napomena['driver_name'] ?></a></td>
                    <td><?php echo $napomena['napomena'] ?> </td>
					<?php endforeach ?>
				</tr>
			  </tbody>
		</table>
	</div>
</div>
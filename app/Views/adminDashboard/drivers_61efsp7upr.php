<div class="container">
        <table id="example" class="table table-striped" style="width:100%">
            <thead>
                <tr>
					<?php foreach($drivers[0] as $key => $value):?>
                    <th><?php echo $key  ?></th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
				<?php foreach($drivers as $driver):  ?>
                <tr>
                    <td><?php echo $driver['driver'] ?></td>
                    <td><?php echo $driver['email'] ?></td>
                    <td><?php echo $driver['mobitel'] ?></td>
                    <td><?php echo $driver['dob'] ?></td>
                    <td><?php echo $driver['uber'] ?></td>
                    <td><?php echo $driver['bolt'] ?></td>
                    <td><?php echo $driver['taximetar'] ?></td>
                    <td><?php echo $driver['myPos'] ?></td>
                    <td><?php echo $driver['refered_by'] ?></td>
                    <td><?php echo $driver['referal_reward'] ?></td>
                    <td><?php echo $driver['driver'] ?></td>
                    <td><?php echo $driver['driver'] ?></td>
                    <td><?php echo $driver['driver'] ?></td>
                    <td><?php echo $driver['driver'] ?></td>
                    <td><?php echo $driver['driver'] ?></td>
                    <td><?php echo $driver['driver'] ?></td>
                </tr>
                <tr>
                    <td>Donna Snider</td>
                    <td>Customer Support</td>
                    <td>New York</td>
                    <td>27</td>
                    <td>2011-01-25</td>
                    <td>$112,000</td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
					<?php foreach($drivers[0] as $key => $value):?>
                    <th><?php echo $key  ?></th>
                    <?php endforeach; ?>
                </tr>
            </tfoot>
        </table>

    </div>
</body>

<script>
$(document).ready(function () {
    $('#example').DataTable();
});
</script>
<?php
echo "<mm:dwdrfml documentRoot=" . __FILE__ .">";$included_files = get_included_files();foreach ($included_files as $filename) { echo "<mm:IncludeFile path=" . $filename . " />"; } echo "</mm:dwdrfml>";
?>
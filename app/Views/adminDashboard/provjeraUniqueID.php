<?php

//print_r($taximetar);
//print_r($taximetarCount);
//print_r($myPos);
//print_r($myPosCount);
//print_r($bolt);
//print_r($boltCount);
//print_r($uber);
//print_r($uberCount);

?>


    <div class="container mt-4">

        <h2>Taximetar Data</h2>
        <table class="table table-bordered table-striped datatable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Vozac</th>
                    <th>Ispravan</th>
                    <th>Aktivan</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($taximetar as $key => $data): ?>
                <tr>
                    <td><?= $key + 1; ?></td>
                    <td><?= $data['Vozac']; ?></td>
                    <td><?= $data['ispravan']; ?></td>
                    <td><?= $data['aktivan']; ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h4>Summary</h4>
        <table class="table table-bordered table-striped datatable">
            <tr>
                <th>Ispravni</th>
                <th>Neispravni</th>
                <th>Total</th>
            </tr>
            <tr>
                <td><?= $taximetarCount['taximetarcountIspravni']; ?></td>
                <td><?= $taximetarCount['taximetarcountNeispravni']; ?></td>
                <td><?= $taximetarCount['taximetarcount']; ?></td>
            </tr>
        </table>

        <h2>MyPOS Data</h2>
        <table class="table table-bordered table-striped datatable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Vozac</th>
                    <th>Ispravan</th>
                    <th>Aktivan</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($myPos as $key => $data): ?>
                <tr>
                    <td><?= $key + 1; ?></td>
                    <td><?= $data['Vozac']; ?></td>
                    <td><?= $data['ispravan']; ?></td>
                    <td><?= $data['aktivan']; ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h4>Summary</h4>
        <table class="table table-bordered table-striped datatable">
            <tr>
                <th>Ispravni</th>
                <th>Neispravni</th>
                <th>Total</th>
            </tr>
            <tr>
                <td><?= $myPosCount['myPoscountIspravni']; ?></td>
                <td><?= $myPosCount['myPoscountNeispravni']; ?></td>
                <td><?= $myPosCount['myPoscount']; ?></td>
            </tr>
        </table>

        <h2>Bolt Data</h2>
        <table class="table table-bordered table-striped datatable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Vozac</th>
                    <th>Ispravan</th>
                    <th>Aktivan</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($bolt as $key => $data): ?>
                <tr>
                    <td><?= $key + 1; ?></td>
                    <td><?= $data['Vozac']; ?></td>
                    <td><?= $data['ispravan']; ?></td>
                    <td><?= $data['aktivan']; ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h4>Summary</h4>
        <table class="table table-bordered table-striped datatable">
            <tr>
                <th>Ispravni</th>
                <th>Neispravni</th>
                <th>Total</th>
            </tr>
            <tr>
                <td><?= $boltCount['boltcountIspravni']; ?></td>
                <td><?= $boltCount['boltcountNeispravni']; ?></td>
                <td><?= $boltCount['boltcount']; ?></td>
            </tr>
        </table>

        <h2>Uber Data</h2>
        <table class="table table-bordered table-striped datatable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Vozac</th>
                    <th>Ispravan</th>
                    <th>Aktivan</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($uber as $key => $data): ?>
                <tr>
                    <td><?= $key + 1; ?></td>
                    <td><?= $data['Vozac']; ?></td>
                    <td><?= $data['ispravan']; ?></td>
                    <td><?= $data['aktivan']; ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h4>Summary</h4>
        <table class="table table-bordered table-striped datatable">
            <tr>
                <th>Ispravni</th>
                <th>Neispravni</th>
                <th>Total</th>
            </tr>
            <tr>
                <td><?= $uberCount['ubercountIspravni']; ?></td>
                <td><?= $uberCount['ubercountNeispravni']; ?></td>
                <td><?= $uberCount['ubercount']; ?></td>
            </tr>
        </table>

    </div>


 <script>
        $(document).ready(function() {
            // Apply DataTables to all tables with the class 'datatable'
            $('.datatable').DataTable({
				paging: false,
				ordering: true,
				searching: true,
				info: false
			});
        });
    </script>
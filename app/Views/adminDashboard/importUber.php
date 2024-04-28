<!--
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Import csv report's</title>
	<meta name="description" content="">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet">
	<style>
	  .container {
		max-width: 500px;
	  }
	</style>
</head>
<body>
-->
	<div class="row mb-3">
		<div class="col-md-4 mt-5">
			<div class="card">
				<div class="card-header text-center">
					<strong>Upload CSV File Uber</strong>
					<p>Nije potrebno nikakvo konvertiranje. Dovoljno je samo odabrati "Payments Driver" datoteku koju generirate na Uberu na Engleskom jeziku</p>				
				</div>
				<div class="card-body">
				<div class="mt-2">
					<?php if (session()->has('message_uber')){ ?>
						<div class="alert <?=session()->getFlashdata('alert-class') ?>">
							<?=session()->getFlashdata('message_uber') ?>
						</div>
					<?php } ?>
					<?php $validation = \Config\Services::validation(); ?>
				</div>	
					<form action="<?php echo base_url('index.php/AdminController/uberReportImport');?>" method="post" enctype="multipart/form-data">
						<div class="form-group mb-3">
							<div class="mb-3">
								<input type="file" name="file" class="form-control" id="file">
							</div>					   
						</div>
						<div class="d-grid">
							<input type="submit" name="submit" value="Upload" class="btn btn-dark" />
						</div>
					</form>
				</div>
			</div>
		</div>




		<div class="col-md-4 mt-5">
			<div class="card">
				<div class="card-header text-center">
					<strong>Upload CSV File Bolt</strong>
					<p>Nije potrebno nikakvo konvertiranje. Dovoljno je samo odabrati "Weekly Report" datoteku koju skidate sa Bolta na Engleskom jeziku</p>				
				</div>
				<div class="card-body">
				<div class="mt-2">
					<?php if (session()->has('message_bolt')){ ?>
						<div class="alert <?=session()->getFlashdata('alert-class') ?>">
							<?=session()->getFlashdata('message_bolt') ?>
						</div>
					<?php } ?>
					<?php $validation = \Config\Services::validation(); ?>
				</div>	
					<form action="<?php echo base_url('index.php/AdminController/boltReportImport');?>" method="post" enctype="multipart/form-data">
						<div class="form-group mb-3">
							<div class="mb-3">
								<input type="file" name="file" class="form-control" id="file">
							</div>					   
						</div>
						<div class="d-grid">
							<input type="submit" name="submit" value="Upload" class="btn btn-dark" />
						</div>
					</form>
				</div>
			</div>
		</div>
		<div class="col-md-4 mt-5">
			<div class="card">
				<div class="card-header text-center">
					<strong>Upload CSV File MyPos</strong>
					<p>Prije uploada potrebno je konvertirati file u csv format, to možete na <a href="https://cloudconvert.com/xls-to-csv" target="_blank">ovoj stranici</a> .</p>				</div>
				<div class="card-body">
				<div class="mt-2">
					<?php if (session()->has('message_myPos')){ ?>
						<div class="alert <?=session()->getFlashdata('alert-class') ?>">
							<?=session()->getFlashdata('message_myPos') ?>
						</div>
					<?php } ?>
					<?php $validation = \Config\Services::validation(); ?>
				</div>	
					<form action="<?php echo base_url('index.php/AdminController/myPosReportImport');?>" method="post" enctype="multipart/form-data">
						<div class="form-group mb-3">
							<div class="mb-3">
								<input type="file" name="file" class="form-control" id="file">
							</div>					   
						</div>
						<div class="d-grid">
							<input type="submit" name="submit" value="Upload" class="btn btn-dark" />
						</div>
					</form>
				</div>
			</div>
		</div>	
	</div>
	<h1 class="text-danger text-center ">Ne koristiti, ovo je još u razvojnoj fazi</h1>

<div class="row border border-danger">
		<div class="col-md-4 mt-5">
			<div class="card">
				<div class="card-header text-center">
					<strong>Upload Excel File Taximetar</strong>
					<p>Prije uploada potrebno je konvertirati file u csv format, to možete na <a href="https://cloudconvert.com/xls-to-csv" target="_blank">ovoj stranici</a> .</p>
				</div>
				<div class="card-body">
				<div class="mt-2">
					<?php if (session()->has('messageTaximetar')){ ?>
						<div class="alert <?=session()->getFlashdata('alert-class') ?>">
							<?=session()->getFlashdata('messageTaximetar') ?>
						</div>
					<?php } ?>
					<?php $validation = \Config\Services::validation(); ?>
				</div>	
					<form action="<?php echo base_url('index.php/ImportController/taximetarImport');?>" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
						<div class="form-group mb-3">
							<div class="mb-3">
								<input type="file" name="file" class="form-control" id="file" required>
								<div class="invalid-feedback">Niste odabrali datoteku.</div>
							</div>
						</div>
						<div class="d-grid">
							<input type="submit" name="submit" value="Upload" class="btn btn-dark">
						</div>
					</form>
				</div>
			</div>
		</div>	
		<div class="col-md-4 mt-5">
			<div class="card">
				<div class="card-header text-center">
					<strong>Upload Excel File Bolt</strong>
					<p>Prije uploada nisu potrebne nikakve druge radnje .</p>
				</div>
				<div class="card-body">
				<div class="mt-2">
					<?php if (session()->has('messageBolt')){ ?>
						<div class="alert <?=session()->getFlashdata('alert-class') ?>">
							<?=session()->getFlashdata('messageBolt') ?>
						</div>
					<?php } ?>
					<?php $validation = \Config\Services::validation(); ?>
				</div>	
					<form action="<?php echo base_url('index.php/ImportController/boltImport');?>" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
						<div class="form-group mb-3">
							<div class="mb-3">
								<input type="file" name="file" class="form-control" id="file" required>
								<div class="invalid-feedback">Niste odabrali datoteku.</div>
							</div>
						</div>
						<div class="d-grid">
							<input type="submit" name="submit" value="Upload" class="btn btn-dark">
						</div>
					</form>
				</div>
			</div>
		</div>	
</div>

<script>
    // Bootstrap form validation script
    (function () {
        'use strict';
        window.addEventListener('load', function () {
            var forms = document.getElementsByClassName('needs-validation');
            var validation = Array.prototype.filter.call(forms, function (form) {
                form.addEventListener('submit', function (event) {
                    if (form.checkValidity() === false) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        }, false);
    })();
</script>

</body>
</html>
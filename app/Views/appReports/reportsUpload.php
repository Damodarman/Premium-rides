<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>
<div class="container ms-1">
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success">
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif ?>

	<div class="row">
		<div class="col-md-3 mt-5">
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
					<form action="<?php echo site_url('AdminController/uberReportImport');?>" method="post" enctype="multipart/form-data">
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




		<div class="col-md-3 mt-5">
			<div class="card">
				<div class="card-header text-center">
					<strong>Upload CSV File Bolt</strong>
					<p>Prije uploada nisu potrebne nikakve druge radnje .</br><br>
<br>
</p>
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
					<form action="<?php echo site_url('ImportController/boltImport');?>" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
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
		<div class="col-md-3 mt-5">
			<div class="card">
				<div class="card-header text-center">
					<strong>Upload CSV File MyPos</strong>
					<p>Prije uploada potrebno je konvertirati file u csv format, to možete na <a href="https://cloudconvert.com/xls-to-csv" target="_blank">ovoj stranici</a> .<br><br>

</p>				</div>
				<div class="card-body">
				<div class="mt-2">
					<?php if (session()->has('message_myPos')){ ?>
						<div class="alert <?=session()->getFlashdata('alert-class') ?>">
							<?=session()->getFlashdata('message_myPos') ?>
						</div>
					<?php } ?>
					<?php $validation = \Config\Services::validation(); ?>
				</div>	
					<form action="<?php echo site_url('AdminController/myPosReportImport');?>" method="post" enctype="multipart/form-data">
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
	

		<div class="col-md-3 mt-5">
			<div class="card">
				<div class="card-header text-center">
					<strong>Upload Excel File Taximetar</strong>
					<p>Prije uploada potrebno je konvertirati file u csv format, to možete na <a href="https://cloudconvert.com/xls-to-csv" target="_blank">ovoj stranici</a> .<br><br>

</p>
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
					<form action="<?php echo site_url('ImportController/taximetarImport');?>" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
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
	<div class="row ">


			<div class="col-md-3 mt-5">
				<div class="card">
					<div class="card-header text-center">
						<strong>Upload CSV Files Uber Driver Activity</strong>
						<p>Nije potrebno nikakvo konvertiranje. Dovoljno je samo odabrati "Driver Activity" datoteke koje generirate na Uberu na Engleskom jeziku</p>
					</div>
					<div class="card-body">
						<div class="mt-2">
							<?php if (session()->has('msgActivityUber')) { ?>
								<div class="alert <?= session()->getFlashdata('alert-class') ?>">
									<?= session()->getFlashdata('msgActivityUber') ?>
								</div>
							<?php } ?>
						</div>
						<form action="<?php echo site_url('ImportController/uberActivityReportImport'); ?>" method="post" enctype="multipart/form-data">
							<div class="form-group mb-3">
								<div class="mb-3">
									<input type="file" name="files[]" class="form-control" id="files" multiple> 
								</div>
							</div>
							<div class="d-grid">
								<input type="submit" name="submit" value="Upload" class="btn btn-dark" />
							</div>
						</form>
					</div>
				</div>
			</div>

		
    <div class="col-md-3 mt-5">
        <div class="card">
            <div class="card-header text-center">
                <strong>Upload CSV Files Bolt Driver Activity</strong>
                <p>Upload your Bolt activity CSV files as-is.</p> 
            </div>
            <div class="card-body">
 						<div class="mt-2">
							<?php if (session()->has('msgActivityBolt')) { ?>
								<div class="alert <?= session()->getFlashdata('alert-class') ?>">
									<?= session()->getFlashdata('msgActivityBolt') ?>
								</div>
							<?php } ?>
						</div>
               <form action="<?php echo site_url('ImportController/boltActivityImport'); ?>" method="post" enctype="multipart/form-data">
                    <div class="form-group mb-3">
                        <div class="mb-3">
                            <input type="file" name="files[]" class="form-control" id="files" multiple>
							
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
</div>



<?= $this->endSection() ?>

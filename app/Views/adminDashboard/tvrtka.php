<div class="container">
<div class="row">
		<a class="btn btn-outline-info" href="<?php echo site_url('admin/flota')?>" role="button">Postavke flote</a>
	</div>

	<?php if (session()->has('msgtvrtka')){ ?>
		<div class="alert <?=session()->getFlashdata('alert-class') ?>">
			<?=session()->getFlashdata('msgtvrtka') ?>
		</div>
	<?php } ?>
    <h2>Tvrtke</h2>

    <!-- Button to add new row -->
    <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#addModal">Add New Tvrtka</button>

    <!-- Bootstrap Table -->
    <table class="table table-bordered table-striped table-sm">
        <thead>
            <tr>
                <th>ID</th>
                <th>Naziv</th>
                <th>Adresa</th>
                <th>Poštanski Broj</th>
                <th>Grad</th>
                <th>Država</th>
                <th>OIB</th>
                <th>Direktor</th>
                <th>Početak Tvrtke</th>
                <th>Fleet</th>
                <th>OIB Direktora</th>
                <th>MBS</th>
                <th>IBAN</th>
                <th>BIC</th>
                <th>Potpis i Pečat</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($tvrtke as $tvrtka): ?>
            <tr>
                <td><?= $tvrtka['id'] ?></td>
                <td><?= $tvrtka['naziv'] ?></td>
                <td><?= $tvrtka['adresa'] ?></td>
                <td><?= $tvrtka['postanskiBroj'] ?></td>
                <td><?= $tvrtka['grad'] ?></td>
                <td><?= $tvrtka['država'] ?></td>
                <td><?= $tvrtka['OIB'] ?></td>
                <td><?= $tvrtka['direktor'] ?></td>
                <td><?= $tvrtka['pocetak_tvrtke'] ?></td>
                <td><?= $tvrtka['fleet'] ?></td>
                <td><?= $tvrtka['oib_direktora'] ?></td>
                <td><?= $tvrtka['MBS'] ?></td>
                <td><?= $tvrtka['IBAN'] ?></td>
                <td><?= $tvrtka['BIC'] ?></td>
                <td>
                        <a href="#" 
                           tabindex="0" 
                           class="btn btn-link"
                           data-bs-toggle="popover" 
                           data-bs-trigger="hover focus"
						   data-bs-placement="left"
                           data-bs-content='<img src="<?= base_url('uploads/' . $tvrtka['potpis_pecat']) ?>" style="max-width:200px; max-height:200px;" />'
                           data-bs-html="true">Link</a>
				</td>
                <td>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editModal<?= $tvrtka['id'] ?>">Edit</button>
                </td>
            </tr>

            <!-- Edit Modal -->
            <div class="modal fade" id="editModal<?= $tvrtka['id'] ?>" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="<?= site_url('editTvrtka/'.$tvrtka['id']) ?>" method="post" enctype="multipart/form-data">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editModalLabel">Edit Tvrtka</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group mb-2">
                                    <label>Naziv</label>
                                    <input type="text" class="form-control" name="naziv" value="<?= $tvrtka['naziv'] ?>" required>
                                </div>
                                <div class="form-group mb-2">
                                    <label>Adresa</label>
                                    <input type="text" class="form-control" name="adresa" value="<?= $tvrtka['adresa'] ?>" required>
                                </div>
                                <div class="form-group mb-2">
                                    <label>Poštanski Broj</label>
                                    <input type="number" class="form-control" name="postanskiBroj" value="<?= $tvrtka['postanskiBroj'] ?>" required>
                                </div>
                                <div class="form-group mb-2">
                                    <label>Grad</label>
                                    <input type="text" class="form-control" name="grad" value="<?= $tvrtka['grad'] ?>" required>
                                </div>
                                <div class="form-group mb-2">
                                    <label>Država</label>
                                    <input type="text" class="form-control" name="država" value="<?= $tvrtka['država'] ?>" required>
                                </div>
                                <div class="form-group mb-2">
                                    <label>OIB</label>
                                    <input type="number" class="form-control" name="OIB" value="<?= $tvrtka['OIB'] ?>" required>
                                </div>
                                <div class="form-group mb-2">
                                    <label>Direktor</label>
                                    <input type="text" class="form-control" name="direktor" value="<?= $tvrtka['direktor'] ?>" required>
                                </div>
                                <div class="form-group mb-2">
                                    <label>Početak Tvrtke</label>
                                    <input type="date" class="form-control" name="pocetak_tvrtke" value="<?= $tvrtka['pocetak_tvrtke'] ?>" required>
                                </div>
                                <div class="form-group mb-2">
                                    <label>OIB Direktora</label>
                                    <input type="text" class="form-control" name="oib_direktora" value="<?= $tvrtka['oib_direktora'] ?>">
                                </div>
                                <div class="form-group mb-2">
                                    <label>MBS</label>
                                    <input type="text" class="form-control" name="MBS" value="<?= $tvrtka['MBS'] ?>">
                                </div>
                                <div class="form-group mb-2">
                                    <label>IBAN</label>
                                    <input type="text" class="form-control" name="IBAN" value="<?= $tvrtka['IBAN'] ?>">
                                </div>
                                <div class="form-group mb-2">
                                    <label>BIC</label>
                                    <input type="text" class="form-control" name="BIC" value="<?= $tvrtka['BIC'] ?>">
                                </div>
								<div class="form-group mb-2">
									<label>Potpis i Pečat</label>
									<input type="file" class="form-control" id="imageInput" name="potpis_pecat" accept="image/*" >
								</div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Save changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?= site_url('TvrtkaController/addTvrtka') ?>" method="post" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel">Add New Tvrtka</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group mb-2">
                        <label>Naziv</label>
                        <input type="text" class="form-control" name="naziv" required>
                    </div>
                    <div class="form-group mb-2">
                        <label>Adresa</label>
                        <input type="text" class="form-control" name="adresa" required>
                    </div>
                    <div class="form-group mb-2">
                        <label>Poštanski Broj</label>
                        <input type="number" class="form-control" name="postanskiBroj" required>
                    </div>
                    <div class="form-group mb-2">
                        <label>Grad</label>
                        <input type="text" class="form-control" name="grad" required>
                    </div>
                    <div class="form-group mb-2">
                        <label>Država</label>
                        <input type="text" class="form-control" name="država" required>
                    </div>
                    <div class="form-group mb-2">
                        <label>OIB</label>
                        <input type="number" class="form-control" name="OIB" required>
                    </div>
                    <div class="form-group mb-2">
                        <label>Direktor</label>
                        <input type="text" class="form-control" name="direktor" required>
                    </div>
                    <div class="form-group mb-2">
                        <label>Početak Tvrtke</label>
                        <input type="date" class="form-control" name="pocetak_tvrtke" required>
                    </div>
                    <div class="form-group mb-2">
                        <label>OIB Direktora</label>
                        <input type="text" class="form-control" name="oib_direktora" required>
                    </div>
                    <div class="form-group mb-2">
                        <label>MBS</label>
                        <input type="text" class="form-control" name="MBS" required>
                    </div>
                    <div class="form-group mb-2">
                        <label>IBAN</label>
                        <input type="text" class="form-control" name="IBAN" required>
                    </div>
                    <div class="form-group mb-2">
                        <label>BIC</label>
                        <input type="text" class="form-control" name="BIC" required>
                    </div>
                    <div class="form-group mb-2">
                        <label>Potpis i Pečat</label>
                        <input type="file" class="form-control" name="potpis_pecat" >
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Add Tvrtka</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Image Preview Tooltip -->



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
<script>
    var cropper;
    var imageInput = document.getElementById('imageInput');
    var imagePreview = document.getElementById('imagePreview');
    var cropButton = document.getElementById('cropButton');
    var submitCroppedImage = document.getElementById('submitCroppedImage');
    var form = document.getElementById('addForm');

    // When the user selects an image
    imageInput.addEventListener('change', function (e) {
        var file = e.target.files[0];
        if (file) {
            var reader = new FileReader();
            reader.onload = function (event) {
                imagePreview.src = event.target.result;
                imagePreview.style.display = 'block'; // Show the image

                // Initialize Cropper after the image is loaded
                if (cropper) {
                    cropper.destroy(); // Destroy previous instance if it exists
                }

                cropper = new Cropper(imagePreview, {
                    aspectRatio: 1, // Adjust as needed
                    viewMode: 2,
                    scalable: true,
                    zoomable: true
                });

                cropButton.style.display = 'block'; // Show the crop button
            };
            reader.readAsDataURL(file);
        }
    });

    // Handle the crop action
    cropButton.addEventListener('click', function () {
        var croppedCanvas = cropper.getCroppedCanvas({
            width: 300, // Set desired width for the cropped image
            height: 300 // Set desired height for the cropped image
        });

        // Convert the cropped image to a blob and set up form submission
        croppedCanvas.toBlob(function (blob) {
            var formData = new FormData(form);

            // Replace the original file input with the cropped image blob
            formData.set('potpis_pecat', blob, 'cropped_image.png');

            // Disable form submit to allow cropping
            submitCroppedImage.disabled = false;

            // Add the cropped image and submit the form via AJAX
            submitCroppedImage.addEventListener('click', function () {
                var request = new XMLHttpRequest();
                request.open('POST', '<?= site_url('addNewTvrtka') ?>', true);
                request.send(formData);

                request.onload = function () {
                    if (request.status === 200) {
                        alert('Image uploaded successfully!');
                        location.reload();
                    } else {
                        alert('Error uploading the image. Please try again.');
                    }
                };
            });
        });
    });
</script>

<script>
    // Enable Bootstrap popover
    var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
    var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl)
    })
</script>
<script>
    // JavaScript to show image preview on hover
    function showTooltip(event, imageUrl) {
        const img = document.getElementById('imagePreview');
        img.src = imageUrl;
        img.style.display = 'block';
        img.style.top = (event.clientY + 20) + 'px';
        img.style.left = (event.clientX + 20) + 'px';
        img.width = 100;
    }

    function hideTooltip() {
        document.getElementById('imagePreview').style.display = 'none';
    }
</script>

<!-- Vanilla Datepicker JS -->
<script src='https://cdn.jsdelivr.net/npm/vanillajs-datepicker@1.1.4/dist/js/datepicker-full.min.js'></script>

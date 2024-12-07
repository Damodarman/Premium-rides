<div class="container mt-5">
    <h2>Upload Potpis i Pečat for <?= $tvrtka['naziv'] ?></h2>

    <form action="<?= site_url('tvrtka/uploadPecat/'.$tvrtka['id']) ?>" method="post" enctype="multipart/form-data">
        <div class="form-group mb-2">
            <label for="potpisPecat">Select Image:</label>
            <input type="file" class="form-control" id="imageInput" name="potpis_pecat" accept="image/*" required>
        </div>

        <!-- Image preview and cropping area -->
        <div class="form-group mb-2">
            <img id="imagePreview" style="max-width: 100%; display: none;" />
        </div>

        <!-- Crop button -->
        <div class="form-group mb-2">
            <button type="button" class="btn btn-primary" id="cropButton" style="display: none;">Crop Image</button>
        </div>

        <div class="form-group mb-2">
            <button type="submit" class="btn btn-success" id="submitCroppedImage" style="display:none;">Upload Cropped Image</button>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
<script>
    var cropper;
    var imageInput = document.getElementById('imageInput');
    var imagePreview = document.getElementById('imagePreview');
    var cropButton = document.getElementById('cropButton');
    var submitCroppedImage = document.getElementById('submitCroppedImage');

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

        // Convert the cropped image to a blob
        croppedCanvas.toBlob(function (blob) {
            var formData = new FormData();
            formData.set('potpis_pecat', blob, 'cropped_image.png');

            // Show the submit button
            submitCroppedImage.style.display = 'block';
        });
    });
</script>
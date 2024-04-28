<div class="container">
	<?php if (session()->has('msgtvrtka')){ ?>
		<div class="alert <?=session()->getFlashdata('alert-class') ?>">
			<?=session()->getFlashdata('msgtvrtka') ?>
		</div>
	<?php } ?>
	<?php echo form_open('index.php/TvrtkaController/addTvrtka', ['class' => 'form']); ?>
    <div class="row">
        <?php foreach ($columns as $column) { ?>
            <?php if ($column !== 'fleet') { ?>
                <div class="col-md-6">
                    <?php 
                        $inputClass = 'form-control';
                        if (isset($validation) && $validation->hasError($column)) {
                            $inputClass .= ' is-invalid';
                        }
                    ?>
                    <?php if ($column === 'pocetak_tvrtke') { ?>
						
							  <label for="datepicker2" class="form-label">Početak rada tvrtke</label>
							<div class="input-group mb-4">
							  <i class="bi bi-calendar-date input-group-text"></i>
							  <input type="text" name="pocetak_tvrtke" id="datepicker2" class="datepicker_input form-control">
							</div>
						 
					<?php } else if ($column !== 'pocetak_tvrtke') { ?>
                        <?php echo form_label($column, $column, ['class' => 'form-label']); ?>
                        <?php echo form_input($column, isset($formData[$column]) ? $formData[$column] : '', ['class' => $inputClass]); ?>
                        <?php if (isset($validation) && $validation->hasError($column)) { ?>
                            <div class="invalid-feedback"><?php echo $validation->getError($column); ?></div>
                        <?php } ?>
                    <?php } ?>
                </div>
            <?php } else { ?>
                <?php echo form_hidden($column, $fleet); ?>
            <?php } ?>
        <?php } ?>
    </div>
    <?php echo form_submit('submit', 'Submit', ['class' => 'btn btn-primary']); ?>
    <?php echo form_close(); ?>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>

<!-- Vanilla Datepicker JS -->
<script src='https://cdn.jsdelivr.net/npm/vanillajs-datepicker@1.1.4/dist/js/datepicker-full.min.js'></script>
<script>
/* Bootstrap 5 JS included */
/* vanillajs-datepicker 1.1.4 JS included */

const getDatePickerTitle = elem => {
  // From the label or the aria-label
  const label = elem.nextElementSibling;
  let titleText = '';
  if (label && label.tagName === 'LABEL') {
    titleText = label.textContent;
  } else {
    titleText = elem.getAttribute('aria-label') || '';
  }
  return titleText;
}

const elems = document.querySelectorAll('.datepicker_input');
for (const elem of elems) {
  const datepicker = new Datepicker(elem, {
    'format': 'yyyy-mm-dd', // UK format
    title: getDatePickerTitle(elem)
  });
}      
</script>

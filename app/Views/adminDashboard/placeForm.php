<div class="container mt-5">
        <h2>Odaberi tjedne</h2>
        <form action="<?php echo site_url('ObracunController/getPlaceData');?>" method="post">
            <div class="mb-3">
                <label for="multiSelect" class="form-label">Odaberi</label>
                <select id="multiSelect" name="weeks" class="form-select" multiple>
					<?php foreach($weeks as $week): ?>
                    <option value="<?=$week['week'] ?>"><?=$week['week'] ?></option>
					
					<?php endforeach ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
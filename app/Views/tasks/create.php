<div class="col-10">
<!-- Here goes main content -->
    <h3>Kreiraj zadatak</h3>
    <form action="<?= site_url('tasks/store') ?>" method="post" class="needs-validation" novalidate>
        <!-- Title Field -->
        <div class="mb-3">
            <label for="title" class="form-label">Naslov</label>
            <input type="text" name="title" id="title" class="form-control <?= session('validation') && session('validation')->hasError('title') ? 'is-invalid' : '' ?>" value="<?= old('title') ?>" required>
            <?php if (session('validation') && session('validation')->hasError('title')): ?>
                <div class="invalid-feedback">
                    <?= session('validation')->getError('title') ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Description Field -->
        <div class="mb-3">
            <label for="description" class="form-label">Zadatak</label>
            <textarea name="description" id="description" class="form-control <?= session('validation') && session('validation')->hasError('description') ? 'is-invalid' : '' ?>" rows="4" required><?= old('description') ?></textarea>
            <?php if (session('validation') && session('validation')->hasError('description')): ?>
                <div class="invalid-feedback">
                    <?= session('validation')->getError('description') ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Priority Field -->
        <div class="mb-3">
            <label for="priority_id" class="form-label">Prioritet</label>
            <select name="priority_id" id="priority_id" class="form-select <?= session('validation') && session('validation')->hasError('priority_id') ? 'is-invalid' : '' ?>" required>
                <option value="" disabled <?= old('priority_id') ? '' : 'selected' ?>>Odaberi prioritet</option>
                <?php foreach ($priorities as $priority): ?>
                    <option value="<?= $priority['id'] ?>" <?= old('priority_id') == $priority['id'] ? 'selected' : '' ?>><?= esc($priority['name']) ?></option>
                <?php endforeach; ?>
            </select>
            <?php if (session('validation') && session('validation')->hasError('priority_id')): ?>
                <div class="invalid-feedback">
                    <?= session('validation')->getError('priority_id') ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Task Type Field -->
        <input type="hidden" value="general" name="task_type" id="task_type">

        <!-- Assigned Users Field -->
        <div class="mb-3">
            <label for="users" class="form-label">Kome dodjeliti zadatak</label>
            <select name="assigned_users[]" id="users" class="form-select <?= session('validation') && session('validation')->hasError('assigned_users') ? 'is-invalid' : '' ?>" multiple>
                <?php foreach ($users as $user): ?>
                    <option value="<?= $user['id'] ?>" <?= in_array($user['id'], old('assigned_users') ?? []) ? 'selected' : '' ?>><?= esc($user['name']) ?></option>
                <?php endforeach; ?>
            </select>
            <?php if (session('validation') && session('validation')->hasError('assigned_users')): ?>
                <div class="invalid-feedback">
                    <?= session('validation')->getError('assigned_users') ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary">Kreiraj zadatak</button>
    </form>
	
	<!-- HERE IS THE END OF THE FILE. NO CODE AFTER THIS -->
</div>
	</div>
</div>
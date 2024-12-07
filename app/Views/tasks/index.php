<div class="col-10">
    <!-- Main Content -->
    <h3 class="mb-4">Zadaci</h3>
    <a href="<?= site_url('tasks/create') ?>" class="btn btn-primary mb-4">Kreiraj novi zadatak</a>
	<?php if (session()->getFlashdata('success')): ?>
		<div class="alert alert-success alert-dismissible fade show" role="alert">
			<?= session()->getFlashdata('success') ?>
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
	<?php endif; ?>

	<?php if (session()->getFlashdata('error')): ?>
		<div class="alert alert-danger alert-dismissible fade show" role="alert">
			<?= session()->getFlashdata('error') ?>
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
	<?php endif; ?>
	
	<!-- Pending Tasks -->
    <h4 class="mt-4">Nepokrenuti zadaci</h4>
    <div class="table-responsive">
        <table class="table table-hover table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Kreirao</th>
                    <th>Naslov</th>
                    <th>Zadatak</th>
                    <th>Vrsta</th>
                    <th>Status</th>
                    <th>Izvršitelj</th>
                    <th>Prioritet</th>
                    <th>Rok</th>
                    <th>Opcije</th>
                </tr>
            </thead>
            <tbody>
                <?php $count = 0; ?>
                <?php foreach ($tasks['pendingTasks'] as $task): ?>
                    <?php 
                        $count++;
                        $dueTime = new \DateTime($task['due_time']);
                        $due_time_formatted = $dueTime->format('d.m.Y H:i');
 						$shortTitle = mb_strimwidth(esc($task['title']), 0, 15, '...');
						$shortDescription = mb_strimwidth(esc($task['description']), 0, 50, '...');                    ?>
                
						<tr data-entity-id="<?= $task['related_entity_id'] ?>">
							<td><?= esc($count) ?></td>
							<td><?= esc($task['created_by_name']) ?></td>
							<td><?= $shortTitle ?></td>
							<td><?= $shortDescription ?></td>
							<td class="<?= $task['task_type'] === 'vozac_related' ? 'driver-related' : '' ?>" data-entity-id="<?= $task['related_entity_id'] ?>">
								<?= $task['task_type'] === 'vozac_related' ? '<span class="loading-text">Loading...</span>' : ucfirst(esc($task['task_type'])) ?>
							</td>
							<td>
								<span class="badge bg-<?= $task['status'] === 'completed' ? 'success' : 'warning' ?>">
									<?= ucfirst(esc($task['status'])) ?>
								</span>
							</td>
							<td><?= esc($task['assigned_user_names']) ?></td>
							<td><span class="fs-6 badge <?= esc($task['priority_class']) ?>"><?= esc($task['priority_name']) ?></span></td>
							<td>
								<?= esc($due_time_formatted) ?> 
								<span class="countdown badge" data-due="<?= $task['due_time'] ?>" data-bs-toggle="tooltip" title="Preostalo vrijeme za završiti zadatak"></span>
							</td>
							<td>
								<?php if($task['status'] === 'pending'): ?>
								<a href="<?= site_url('tasks/start/' . $task['id']) ?>" class="btn btn-info btn-sm" data-bs-toggle="tooltip" title="Započni ovaj zadatak">
									Započni
								</a>
								<?php else: ?>
								<a href="<?= site_url('tasks/show/' . $task['id']) ?>" class="btn btn-info btn-sm" data-bs-toggle="tooltip" title="Otvori zadatak za detalje">
									Detalji
								</a>
								<?php endif ?>
								<a href="<?= site_url('tasks/edit/' . $task['id']) ?>" class="btn btn-warning btn-sm" data-bs-toggle="tooltip" title="Izmjeni">
									Izmjeni
								</a>
							</td>
						</tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- In-Progress Tasks -->
    <h4 class="mt-4">Zadaci u tijeku</h4>
    <div class="table-responsive">
        <table class="table table-hover table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Kreirao</th>
                    <th>Naslov</th>
                    <th>Zadatak</th>
                    <th>Vrsta</th>
                    <th>Status</th>
                    <th>Izvršitelj</th>
                    <th>Prioritet</th>
                    <th>Rok</th>
                    <th>Opcije</th>
                </tr>
            </thead>
            <tbody>
                <?php $count = 0; ?>
                <?php foreach ($tasks['in_progress'] as $task): ?>
                    <?php 
                        $count++;
                        $dueTime = new \DateTime($task['due_time']);
                        $due_time_formatted = $dueTime->format('d.m.Y H:i');
						$shortTitle = mb_strimwidth(esc($task['title']), 0, 15, '...');
						$shortDescription = mb_strimwidth(esc($task['description']), 0, 50, '...');                    ?>
						<tr data-entity-id="<?= $task['related_entity_id'] ?>">
							<td><?= esc($count) ?></td>
							<td><?= esc($task['created_by_name']) ?></td>
							<td><?= $shortTitle ?></td>
							<td><?= $shortDescription ?></td>
							<td class="<?= $task['task_type'] === 'vozac_related' ? 'driver-related' : '' ?>" data-entity-id="<?= $task['related_entity_id'] ?>">
								<?= $task['task_type'] === 'vozac_related' ? '<span class="loading-text">Loading...</span>' : ucfirst(esc($task['task_type'])) ?>
							</td>
							<td>
								<span class="badge bg-<?= $task['status'] === 'completed' ? 'success' : 'warning' ?>">
									<?= ucfirst(esc($task['status'])) ?>
								</span>
							</td>
							<td><?= esc($task['assigned_user_names']) ?></td>
							<td><span class="fs-6 badge <?= esc($task['priority_class']) ?>"><?= esc($task['priority_name']) ?></span></td>
							<td>
								<?= esc($due_time_formatted) ?> 
								<span class="countdown badge" data-due="<?= $task['due_time'] ?>" data-bs-toggle="tooltip" title="Preostalo vrijeme za završiti zadatak"></span>
							</td>
							<td>
								<?php if($task['status'] === 'pending'): ?>
								<a href="<?= site_url('tasks/start/' . $task['id']) ?>" class="btn btn-info btn-sm" data-bs-toggle="tooltip" title="Započni ovaj zadatak">
									Započni
								</a>
								<?php else: ?>
								<a href="<?= site_url('tasks/show/' . $task['id']) ?>" class="btn btn-info btn-sm" data-bs-toggle="tooltip" title="Otvori zadatak za detalje">
									Detalji
								</a>
								<?php endif ?>
								<a href="<?= site_url('tasks/edit/' . $task['id']) ?>" class="btn btn-warning btn-sm" data-bs-toggle="tooltip" title="Izmjeni">
									Izmjeni
								</a>
							</td>
						</tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Completed Tasks -->
    <h4 class="mt-4">Završeni zadaci</h4>
    <div class="table-responsive">
        <table class="table table-hover table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Naslov</th>
                    <th>Zadatak</th>
                    <th>Vrsta</th>
                    <th>Status</th>
                    <th>Rok</th>
                    <th>Opcije</th>
                </tr>
            </thead>
            <tbody>
                <?php $count = 0; ?>
                <?php foreach ($tasks['completed'] as $task): ?>
                    <?php 
                        $count++;
                        $dueTime = new \DateTime($task['due_time']);
                        $due_time_formatted = $dueTime->format('d.m.Y H:i');
 						$shortTitle = mb_strimwidth(esc($task['title']), 0, 15, '...');
						$shortDescription = mb_strimwidth(esc($task['description']), 0, 50, '...');                    ?>
                
                    <tr>
                        <td><?= esc($count) ?></td>
                        <td><?= $shortTitle ?></td>
                        <td><?= $shortDescription ?></td>
                        <td><?= ucfirst(esc($task['task_type'])) ?></td>
                        <td>
                            <span class="badge bg-success"><?= ucfirst(esc($task['status'])) ?></span>
                        </td>
                        <td><?= esc($due_time_formatted) ?></td>
                        <td>
                            <a href="<?= site_url('tasks/show/' . $task['id']) ?>" class="btn btn-info btn-sm" data-bs-toggle="tooltip" title="Detalji">
                                Detalji
                            </a>
                            <a href="<?= site_url('tasks/edit/' . $task['id']) ?>" class="btn btn-warning btn-sm" data-bs-toggle="tooltip" title="Izmjeni">
                                Izmjeni
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
	
	<!-- HERE IS THE END OF THE FILE. NO CODE AFTER THIS -->
</div>
	</div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const driverCells = document.querySelectorAll('.driver-related');

    // Function to fetch driver name by ID
    const fetchDriverName = async (entityId) => {
        try {
            const response = await fetch('<?= site_url('api/getDriverNameById') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest', // Explicitly mark as AJAX request
                },
                body: JSON.stringify({ driverId: entityId }),
            });

            if (!response.ok) {
                console.error(`Failed to fetch driver name: ${response.status}`);
                return null; // Exit on failed response
            }

            const result = await response.json();

            // Debugging: Log the response
            console.log('API Response:', result);

            if (result && result.driverName) {
                return result.driverName; // Return the driver's name
            } else {
                console.error('Driver name not found in response');
            }
        } catch (error) {
            console.error('Error fetching driver name:', error);
        }

        return null; // Return null if an error occurs
    };

    // Iterate through all driver-related cells and fetch names
    driverCells.forEach(async (cell) => {
        const entityId = cell.getAttribute('data-entity-id');
        if (entityId) {
            const driverName = await fetchDriverName(entityId);

            // Debugging: Log the result of fetchDriverName
            console.log(`Fetched driver name for entity ${entityId}:`, driverName);

            if (driverName) {
                cell.innerHTML = `Vezano za vozača: ${driverName}`;
            } else {
                cell.innerHTML = 'Greška prilikom dohvaćanja vozača'; // Error message in Croatian
            }
        }
    });
});


</script>

<script>
    // Function to calculate and update the countdown
    function updateCountdown() {
        const countdownElements = document.querySelectorAll('.countdown');

        countdownElements.forEach(element => {
            const dueTime = new Date(element.getAttribute('data-due')); // Get the due time
            const now = new Date(); // Current time

            const diff = dueTime - now; // Difference in milliseconds

            // Remove existing color classes
            element.classList.remove('bg-success', 'bg-warning', 'bg-danger', 'bg-secondary');

            if (diff > 0) {
                // Calculate hours, minutes, and seconds
                const hours = Math.floor(diff / (1000 * 60 * 60));
                const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));

                // Update the countdown text
                element.textContent = `${hours}h ${minutes}m`;

                // Add colors based on time left
                if (hours > 24) {
                    element.classList.add('bg-success'); // More than 24 hours: Green
                } else if (hours <= 24 && hours > 1) {
                    element.classList.add('bg-warning'); // Between 1 and 24 hours: Yellow
                } else if (hours <= 1) {
                    element.classList.add('bg-danger'); // Less than 1 hour: Red
                }
            } else {
                // If the task is overdue
                element.textContent = 'Isteklo'; // "Expired" in Croatian
                element.classList.add('bg-danger'); // Overdue: Grey
            }
        });
    }

    // Update countdown every second
    setInterval(updateCountdown, 1000);

    // Initial call to display the countdown immediately
    updateCountdown();
</script>

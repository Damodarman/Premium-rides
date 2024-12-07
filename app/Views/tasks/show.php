<div class="col-10">
<!-- Here goes main content -->
   <h3 class="mb-4">Detalji Zadatka</h3>
	
<?php
// Convert the comma-separated string of IDs into an array
$assignedUserIds = !empty($task['assigned_user_ids']) ? explode(',', $task['assigned_user_ids']) : [];
$userId = session()->get('id');
$sender_name = session()->get('name');


?>
    <!-- Task Information -->
	<div class="row me-5" style="height: 800px">
		<div class="col-8">
    <div class="card mb-4 p-0 " style="height: 800px">
        <div class="card-header">
			<div class="row">
				<div class="col-4">            
					<h5><?= esc($task['title']) ?></h5>
				</div>
				<div class="col-4">
					<h5><strong>Kreirao:</strong> <?= esc($task['created_by_name']) ?></h5>
				</div>
				<div class="col-4">
                <strong>Status:</strong> 
                <span class="badge bg-<?= $task['status'] === 'completed' ? 'success' : 'warning' ?>">
                    <?= ucfirst(esc($task['status'])) ?>
                </span>
				</div>
			</div>
        </div>
        <div class="card-body">
            <p class="mb-2"><strong>Opis:</strong> <?= esc($task['description']) ?></p>
            <p class="mb-2">
                <strong>Prioritet:</strong> 
                <span class="badge <?= esc($task['priority_class']) ?>">
                    <?= esc($task['priority_name']) ?>
                </span>
            </p>
            <p class="mb-2"><strong>Dodijeljeni korisnici:</strong> <?= esc($task['assigned_user_names']) ?: 'Nema dodijeljenih korisnika' ?></p>
            <p class="mb-2"><strong>Rok:</strong> <?= (new DateTime($task['due_time']))->format('d.m.Y H:i') ?></p>
			<?php if($driver): ?>
			<div class="row border border-info">
				<h5>Ovaj zadatak je vezan za vozača <?= $driver['vozac']?></h5>

				<a href="<?php echo site_url('drivers/'). '/' . $driver['id'] ?>">Otvori stranicu vozača za detaljne informacije</a>
				<hr>
				<strong> Broj telefona: </strong><p><?= $driver['mobitel']?></p>
				<hr>
				<strong>email: </strong><p><?= $driver['email']?></p>
			
			</div>
			<?php endif ?>
			<div class="row position relative">
				<div class="col position-absolute bottom-0  mb-3">
					<div class="d-flex align-items-center gap-2">
						<?php if ($task['status'] !== 'completed' && $task['status'] !== 'pending'): ?>
							<a href="<?= site_url('tasks/markAsCompleted/' . $task['id']) ?>" 
							   class="btn btn-success btn-sm" 
							   data-bs-toggle="tooltip" 
							   title="Završi zadatak">
								Završi zadatak
							</a>
						<?php endif; ?>
						<?php if ($task['status'] == 'pending'): ?>
							<a href="<?= site_url('tasks/start/' . $task['id']) ?>" 
							   class="btn btn-info btn-sm" 
							   data-bs-toggle="tooltip" 
							   title="Započni zadatak">
								Započni zadatak
							</a>
						<?php endif; ?>
						<div class="dropdown">
							<a href="javascript:void(0);" 
							   class="btn btn-info btn-sm dropdown-toggle" 
							   data-bs-toggle="dropdown" 
							   aria-expanded="false" 
							   title="Dodaj druge korisnike da ti pomognu">
								Zatraži pomoć
							</a>
							<div class="dropdown-menu p-3" style="min-width: 500px;">
								<form action="<?= site_url('tasks/requestHelp/'. $task['id']) ?>" method="post">
									<input type="hidden" name="task_id" value="<?= esc($task['id']) ?>">
									<div class="mb-3">
										<label for="users" class="form-label">Odaberi korisnike</label>
										<select name="assigned_users[]" id="users" class="form-select" multiple required>
											<?php foreach ($allUsers as $user): ?>
												<?php 
													$isCreator = $task['created_by'] === $user['id'];
													$isAssigned = in_array($user['id'], $assignedUserIds); // Check if user is assigned
												?>
												<option value="<?= $user['id'] ?>" <?= ($isCreator || $isAssigned) ? 'disabled' : '' ?>>
													<?= esc($user['name']) ?>
												</option>
											<?php endforeach; ?>
										</select>
									</div>
									<div class="mb-3">
										<label for="comment" class="form-label">Dodaj komentar</label>
										<textarea class="form-control" id="comment" name="comment" rows="3"></textarea>
									</div>
									<button type="submit" class="btn btn-success btn-sm">Dodaj korisnike</button>
								</form>
							</div>
						</div>
					</div>
				</div>

			</div>
        </div>
    </div>
</div>
<div class="col-4">
    <div class="card mb-4 p-0 border border-info" style="height: 800px;">
        <!-- Chat Header -->
        <div class="card-header bg-info text-white text-center">
            <h5>Razgovor za zadatak: <?= esc($task['title']) ?></h5>
        </div>

        <!-- Chat Body -->
        <div class="card-body d-flex flex-column" style="overflow-y: auto; height: 600px;" id="chat-container">
            <!-- Example of Chat Messages -->
            <?php if (!isset($chatMsgs)): ?>
                 <div class="d-flex flex-row justify-content-start mb-4">
                    <div>
                        <p class="mb-1"><strong>Sistem</strong></p>
                        <p class="small p-3 me-5 mb-1 text-white rounded-3 bg-primary">Zadatak je kreiran i nema još poruka...</p>
                        <p class="small me-3 mb-3 rounded-3 text-muted d-flex justify-content-start"><?= (new DateTime($task['created_at']))->format('d.m.Y H:i') ?></p>
                    </div>
                </div>
            <?php else: ?>
                <?php foreach ($chatMsgs as $msg): ?>
					<?php
					if($msg['senderId'] === $userId): ?>
                 <div class="d-flex flex-row justify-content-end mb-4">
                    <div>
                        <p class="mb-1"><strong><?= esc($msg['sender_name']) ?></strong></p>
                        <p class="small p-3 me-5 mb-1 text-white rounded-3 bg-success"><?= esc($msg['content']) ?></p>
                        <p class="small me-3 mb-3 rounded-3 text-muted d-flex justify-content-end"><?= (new DateTime($msg['created_at']))->format('d.m.Y H:i') ?></p>
                    </div>
                </div>
					<?php else : ?>
						 <div class="d-flex flex-row justify-content-start mb-4">
							<div>
                        		<p class="mb-1"><strong><?= esc($msg['sender_name']) ?></strong></p>
								<p class="small p-3 me-5 mb-1 text-white rounded-3 bg-light"><?= esc($msg['content']) ?></p>
								<p class="small me-3 mb-3 rounded-3 text-muted d-flex justify-content-start"><?= (new DateTime($task['created_at']))->format('d.m.Y H:i') ?></p>
							</div>
						</div>
					<?php endif ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Chat Input -->
        <div class="card-footer bg-light">
            <form action="<?= site_url('tasks/sendMessage') ?>" method="post" id="chat-form">
                <input type="hidden" name="task_id" value="<?= esc($task['id']) ?>">
                <input type="hidden" name="sender_id" value="<?= esc($userId) ?>">
                <input type="hidden" name="sender_name" value="<?= esc($sender_name) ?>">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Unesite poruku..." name="message" required>
                    <button class="btn btn-primary" type="submit">Pošalji</button>
                </div>
            </form>
        </div>
    </div>
</div>

		
		
</div>

<div class="container my-4">
    <h3 class="text-center">Task Timeline</h3>
    <div class="row ">
        <!-- Timeline Line -->

        <?php
        $isAbove = true; // To alternate text and timestamps
        foreach ($history as $entry) {
        ?>
        <!-- Timeline Event -->
        <div class="col text-center">
            <?php if ($isAbove): ?>
                <!-- Upper Row -->
			<div class="row position-relative" style="height: 150px">
				<div class="col">
					<div>
						<div class="p-2 bg-light border rounded">
							<p class="fw-bold mb-1"><?= esc($entry['details']) ?></p>
						</div>
					</div>
				</div>
			</div>
                <!-- Circle Badge -->

			<div class="row position-relative border-top border-4 border-primary"  style="height: 150px">
				 <span class="position-absolute top-0 start-50 translate-middle p-2 bg-primary rounded-circle" style="width: 20px; height: 20px;"></span>
				<div class="col position-absolute bottom-0 start-50 translate-middle-x">
					<div>
						<div class="p-2 bg-light border rounded">
							<p class="fw-bold mb-1"><?= esc($entry['changed_by_name']) ?></p>
							<small class="text-muted"><?= (new DateTime($entry['created_at']))->format('d.m.Y H:i') ?></small>
						</div>
					</div>
				</div>
			</div>
            <?php else: ?>
                <!-- Lower Row -->
                <!-- Circle Badge -->
			<div class="row position-relative"  style="height: 150px">
				<div class="col">
					<div >
						<div class="p-2 bg-light border rounded">
							<p class="fw-bold mb-1"><?= esc($entry['changed_by_name']) ?></p>
							<small class="text-muted"><?= (new DateTime($entry['created_at']))->format('d.m.Y H:i') ?></small>
						</div>
					</div>
				</div>
			</div>
			<div class="row position-relative border-top border-4 border-primary"  style="height: 150px">
				 <span class="position-absolute top-0 start-50 translate-middle p-2 bg-primary rounded-circle" style="width: 20px; height: 20px;"></span>				<div class="col position-absolute bottom-0 start-50 translate-middle-x">
					<div>
						<div class="p-2 bg-light border rounded">
							<p class="fw-bold mb-1"><?= esc($entry['details']) ?></p>
						</div>
					</div>
				</div>
			</div>
			<?php endif; ?>
        </div>
        <?php $isAbove = !$isAbove; // Toggle placement ?>
        <?php } ?>
    </div>
</div>

    <!-- Task History Section -->
    <?php if (!empty($history)): ?>
<div class="accordion" id="taskHistoryAccordion">
    <div class="accordion-item">
        <h2 class="accordion-header" id="heading-history">
            <button class="accordion-button collapsed" type="button" 
                    data-bs-toggle="collapse" 
                    data-bs-target="#collapse-history" 
                    aria-expanded="false" 
                    aria-controls="collapse-history">
                Povijest Zadatka
            </button>
        </h2>
        <div id="collapse-history" class="accordion-collapse collapse" aria-labelledby="heading-history" 
             data-bs-parent="#taskHistoryAccordion">
            <div class="accordion-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Akcija</th>
                                <th>Detalji</th>
                                <th>Komentar</th>
                                <th>Promijenio</th>
                                <th>Datum Promjene</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($history as $index => $entry): ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td><?= esc($entry['status']) ?></td>
                                    <td><?= esc($entry['details']) ?></td>
                                    <td><?= esc($entry['comment']) ?></td>
                                    <td><?= esc($entry['changed_by_name']) ?></td>
                                    <td><?= (new DateTime($entry['created_at']))->format('d.m.Y H:i') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
    <?php else: ?>
        <p class="text-muted">Nema povijesti za ovaj zadatak.</p>
    <?php endif; ?>

    <!-- Back to Tasks Button -->
    <a href="<?= site_url('tasks') ?>" class="btn btn-secondary mt-3">Nazad na zadatke</a>
	<!-- HERE IS THE END OF THE FILE. NO CODE AFTER THIS -->
</div>
	</div>
</div>



<script>
document.addEventListener('DOMContentLoaded', function () {
    const chatContainer = document.getElementById('chat-container');
    const chatForm = document.getElementById('chat-form');
    const newMessagesIndicator = document.createElement('div'); // Indicator for new messages
    const taskId = <?= json_encode($task['id']) ?>;
    let lastMessageId = null; // Track the ID of the last message
    let isFirstLoad = true; // Track if it's the first load

    // Create new message indicator
    newMessagesIndicator.classList.add('new-messages-indicator', 'text-center', 'mb-2', 'cursor-pointer');
    newMessagesIndicator.style.display = 'none'; // Initially hidden
    newMessagesIndicator.innerHTML = '<hr class="my-2"><span class="badge bg-primary text-white">Nove poruke</span><hr class="my-2">';
    newMessagesIndicator.addEventListener('click', () => {
        newMessagesIndicator.style.display = 'none'; // Hide the indicator when clicked
        chatContainer.scrollTop = chatContainer.scrollHeight; // Scroll to the bottom
    });

    chatContainer.parentNode.insertBefore(newMessagesIndicator, chatContainer); // Add the indicator above the chat container

    // Fetch only new messages
    const fetchChatMessages = async () => {
        try {
            const url = lastMessageId 
                ? `<?= site_url('tasks/getChatMessages') ?>/${taskId}?lastMessageId=${lastMessageId}`
                : `<?= site_url('tasks/getChatMessages') ?>/${taskId}`;
            const response = await fetch(url);
            if (response.ok) {
                const messages = await response.json();
                appendMessages(messages);
            } else {
                console.error('Failed to fetch messages');
            }
        } catch (error) {
            console.error('Error fetching messages:', error);
        }
    };

    const formatDate = (dateString) => {
        const date = new Date(dateString);
        const day = String(date.getDate()).padStart(2, '0');
        const month = String(date.getMonth() + 1).padStart(2, '0'); // Month is zero-indexed
        const year = date.getFullYear();
        const hours = String(date.getHours()).padStart(2, '0');
        const minutes = String(date.getMinutes()).padStart(2, '0');
        return `${day}.${month}.${year} ${hours}:${minutes}`;
    };

    // Append new messages to the chat container
    const appendMessages = (messages) => {
        if (!messages.length) return;

        let lastSenderId = null; // Track last sender
        messages.forEach((msg) => {
            const isSentByCurrentUser = msg.sender_id === <?= json_encode($userId) ?>;
            const alignmentClass = isSentByCurrentUser ? 'justify-content-end' : 'justify-content-start';
            const bgColorClass = isSentByCurrentUser ? 'bg-success-subtle' : 'bg-light';
            const textAlignClass = isSentByCurrentUser ? 'justify-content-end' : 'justify-content-start';

            const displaySenderName = lastSenderId !== msg.sender_id;
            lastSenderId = msg.sender_id;

            chatContainer.innerHTML += `
                <div class="d-flex flex-row ${alignmentClass} mb-1">
                    <div>
                        ${displaySenderName ? `<p class="mt-3">${msg.sender_name}</p>` : ''}
                        <p class="small p-3 me-5 mb-1 ${bgColorClass} rounded-3">${msg.content}</p>
                        <p class="small me-3 mb-1 rounded-3 text-muted d-flex ${textAlignClass}">${formatDate(msg.created_at)}</p>
                    </div>
                </div>
            `;
        });

        lastMessageId = messages[messages.length - 1].id; // Update the last message ID

        // Auto-scroll on first load
        if (isFirstLoad) {
            chatContainer.scrollTop = chatContainer.scrollHeight;
            isFirstLoad = false;
        } else {
            newMessagesIndicator.style.display = 'block'; // Show new message indicator for subsequent messages
        }
    };

    // Submit new message via AJAX
    chatForm.addEventListener('submit', async (event) => {
        event.preventDefault();

        const formData = new FormData(chatForm);
        try {
            const response = await fetch(chatForm.action, {
                method: 'POST',
                body: formData,
            });
            if (response.ok) {
                chatForm.reset();
                fetchChatMessages(); // Fetch new messages after sending one
            } else {
                console.error('Failed to send message');
            }
        } catch (error) {
            console.error('Error sending message:', error);
        }
    });

    // Periodically fetch new messages every 20 seconds
    setInterval(fetchChatMessages, 20000);

    // Initial fetch of messages
    fetchChatMessages();
});
</script>



<div class="admin__users">
	<div class="section_status <?= $disabled ?>">
		<div class="current_status"><?= $status ?></div>
		Users content

		<div class="tabs">
			<!-- these are the tabs -->
			<div class="tab active"><div class="tab-box"><div class="tab-title">Create</div></div></div>
			<div class="tab"><div class="tab-box"><div class="tab-title">Edit</div></div></div>
		</div>
		<!-- these are the content of the tabs -->
		<!-- "Create" tab -->
		<div class="tab-content users-create active">
			<div class="subcontent create">
				<h2>Create new user</h2>
				<?= form_open('users/create', $form_attr_1) ?>
				<div class="form-line">
					<label for="user-name">Name: </label>
					<input type="text" name="user-name" placeholder="User name here" required />
				</div>
				<div class="form-line">
					<label for="user-lastname">Lastname: </label>
					<input type="text" name="user-lastname" placeholder="User lastname here" required />
				</div>
				<div class="form-line">
					<label for="user-nickname">Nickname: </label>
					<input type="text" name="user-nickname" />
				</div>
				<div class="form-line">
					<input type="submit" value="Create User">
				</div>
				</form>
				<div class="create-user-server-response"></div>
			</div>
			<hr/>
			<h2>List of users</h2>
			<div class="existing-users listing">
				<?php
					if (isset($users) && !empty($users)){
				?>
				<div class="users-wrapper head">
					<div class="user-index">&#35;</div>
					<div class="user-name">Name</div>
					<div class="user-lastname">Lastname</div>
					<div class="user-nickname">Nickname</div>
				</div>
				<?php
						$i = 1;
						foreach($users as $eachUserKey => $eachUser){
				?>
				<div class="users-wrapper item" data-list-conciliator="<?= $eachUserKey ?>">
					<div class="user-index"><?= $i ?></div>
					<div class="user-name"><?= $eachUser['name'] ?></div>
					<div class="user-lastname"><?= $eachUser['lastname'] ?></div>
					<div class="user-nickname"><?= $eachUser['nickname'] ?></div>
				</div>
				<?php
							$i++;
						}
					}else{
				?>
				There are no users saved
				<?php
					}
				?>
			</div>
		</div>
		<!-- "Edit" tab -->
		<div class="tab-content users-edit">
			<div class="subcontent edit">
				<h2>Edit user</h2>
				<p>To edit a user, select one by clicking on it</p>

				<?= form_open('users/edit', $form_attr_2) ?>
				<div class="form-line">
					<label for="user-name">Name: </label>
					<input type="text" name="user-name" placeholder="User name here" required />
				</div>
				<div class="form-line">
					<label for="user-lastname">Lastname: </label>
					<input type="text" name="user-lastname" placeholder="User lastname here" required />
				</div>
				<div class="form-line">
					<label for="user-nickname">Nickname: </label>
					<input type="text" name="user-nickname" />
				</div>
				<div class="form-line">
					<input type="submit" value="Edit User">
				</div>
				</form>

				<hr/>

				<h2>Existing users: <span class="current-existing-users">0</span> </h2>
				<div class="existing-users edit">
					<?php
						if (isset($users) && !empty($users)){
					?>
						<div class="users-wrapper head">
							<div class="user-index">&#35;</div>
							<div class="user-name">Name</div>
							<div class="user-lastname">Lastname</div>
							<div class="user-nickname">Nickname</div>
							<div class="user-actions">Actions</div>
						</div>
					<?php
							$j = 1;
							foreach ($users as $eachUserKey => $eachUser){
					?>
						<div id="user-<?= $eachUserKey ?>" class="users-wrapper item">
							<div class="user-index"><?= $j ?></div>
							<div class="user-name"><?= $eachUser['name'] ?></div>
							<div class="user-lastname"><?= $eachUser['lastname'] ?></div>
							<div class="user-nickname"><?= $eachUser['nickname'] ?></div>
							<div class="user-actions">
								<button class="user-delete" type="button" value="<?= $eachUserKey ?>">Delete</button>
							</div>
						</div>
					<?php
								$j++;
							}
						}else{
					?>
							There are no users saved
					<?php
						}
					?>
				</div>
			</div>
		</div>

	</div>
</div>

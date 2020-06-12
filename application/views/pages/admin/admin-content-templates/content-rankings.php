<div class="admin__rankings">
	<div class="section_status <?= $disabled ?>">
		<div class="current_status"><?= $status ?></div>
		Rankings content

		<div class="tabs">
			<!-- these are the tabs -->
			<div class="tab active"><div class="tab-box"><div class="tab-title">Create</div></div></div>
			<div class="tab"><div class="tab-box"><div class="tab-title">Edit</div></div></div>
		</div>
		<!-- these are the content of the tabs -->
		<!-- "Create" tab -->
		<div class="tab-content ranking-create active">
			<div class="subcontent create">
				<h2>Create new ranking</h2>
				<?= form_open('ranking/create', $form_attr_1) ?>
					<div class="form-line">
						<label for="ranking-title">Ranking name: </label>
						<input type="text" name="ranking-title" placeholder="Ranking name here" required />
					</div>
					<div class="form-line">
						<label for="ranking-tops">Ranking tops: </label>
						<select name="ranking-tops">
							<option value="2">2</option>
							<option value="3">3</option>
							<option value="4">4</option>
							<option value="5">5</option>
							<option value="6">6</option>
							<option value="7">7</option>
							<option value="8">8</option>
							<option value="9">9</option>
							<option value="10">10</option>
						</select>
					</div>
					<div class="form-line">
						<label for="ranking-start">Ranking start date: </label>
						<input type="date" name="ranking-start" required />
					</div>
					<div class="form-line">
						<label for="ranking-end">Ranking end date: </label>
						<input type="date" name="ranking-end" />
					</div>
					<div class="form-line">
						<input type="submit" value="Create Ranking">
					</div>
				</form>
				<div class="ranking-server-response"></div>
			</div>
		</div>
		<!-- "Edit" tab -->
		<div class="tab-content ranking-edit">
			<div class="subcontent edit">
				<h2>Edit ranking</h2>
				<p>To edit a ranking, select one by clicking on it</p>

				<?= form_open('ranking/edit', $form_attr_2) ?>
				<div class="form-line">
					<label for="ranking-title">Ranking name: </label>
					<input type="text" class="ranking-title" name="ranking-title" placeholder="Ranking name here" required />
				</div>
				<div class="form-line">
					<label for="ranking-start">Ranking start date: </label>
					<input type="date" class="ranking-start" name="ranking-start" required />
				</div>
				<div class="form-line">
					<label for="ranking-end">Ranking end date: </label>
					<input type="date" class="ranking-end" name="ranking-end" />
				</div>
				<div class="form-line">
					<label for="ranking-tops">Ranking tops: </label>
					<select class="ranking-tops" name="ranking-tops">
						<option value="2">2</option>
						<option value="3">3</option>
						<option value="4">4</option>
						<option value="5">5</option>
						<option value="6">6</option>
						<option value="7">7</option>
						<option value="8">8</option>
						<option value="9">9</option>
						<option value="10">10</option>
					</select>
				</div>
				<div class="form-line">
					<label for="ranking-players">Players:</label>
					<div class="ranking-players">
						<!-- Players list here -->
					</div>
				</div>
				<div class="form-line">
					<input type="submit" value="Edit Ranking">
				</div>
				</form>

				<hr/>

				<div class="existing-ranks">
				<?php
				if (isset($rankings) && !empty($rankings)){
				?>
					<div class="ranking-wrapper head">
						<div class="rank-title">Title</div>
						<div class="rank-tops">Tops</div>
						<div class="rank-start">Start Date</div>
						<div class="rank-end">End Date</div>
						<div class="rank-author">Author</div>
						<div class="rank-status">Status</div>
						<div class="rank-actions">Actions</div>
					</div>
				<?php
					foreach ($rankings as $eachRankKey => $eachRank){
						?>
						<div class="ranking-wrapper item <?= ($eachRank['is_enabled'])?"active":"inactive" ?>" data-rank-id="<?= $eachRankKey ?>">
							<div class="rank-title item"><?= $eachRank['title'] ?></div>
							<div class="rank-tops item"><?= $eachRank['tops'] ?></div>
							<div class="rank-start item"><?= $eachRank['start_date'] ?></div>
							<div class="rank-end item"><?= (empty($eachRank['end_date']))?"End Date not defined":$eachRank['end_date'] ?></div>
							<div class="rank-author item"><?= $eachRank['author'] ?></div>
							<div class="rank-status item"><?= ($eachRank['is_enabled'])?"Active":"Inactive" ?></div>
							<div class="rank-actions item">
								<button id="rank-<?= $eachRankKey ?>-stat" type="button" class="status-modifier <?= ($eachRank['is_enabled'])?"inactive":"active" ?>" future-rank-stat="<?= ($eachRank['is_enabled'])?0:1 ?>"><?= ($eachRank['is_enabled'])?"Deactivate":"Activate" ?></button>
								<button class="rank-delete" value="<?= $eachRankKey ?>">Delete</button>
							</div>
						</div>
				<?php
					}
				}else{
					echo "<div class='ranking-wrapper'>There are no rankings yet</div>";
				}
				?>
				</div>
			</div>
		</div>

	</div>
</div>

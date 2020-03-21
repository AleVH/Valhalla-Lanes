<div class="admin__front-menu">
	<div class="section_status <?= $disabled ?>">
		<div class="current_status"><?= $status ?></div>
		Front Menu
		<div class="front-menu__content">
			<ul>
				<?php
				foreach ($frontmenu as $eachmenu){
					?>
					<li>
						<div id="menu-section-<?= $eachmenu['id'] ?>">
							<label for="<?= $eachmenu['name'] ?>"> <?= $eachmenu['name'] ?></label>
							<input type="checkbox" name="<?= $eachmenu['name'] ?>" value="<?= $eachmenu['id'] ?>" <?= ($eachmenu['is_enabled'])?'checked':'' ?>>
							<span class="menu-section_status"></span>
						</div>
					</li>
					<?php
				}
				?>
			</ul>
		</div>
	</div>
</div>

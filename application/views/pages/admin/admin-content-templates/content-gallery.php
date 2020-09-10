<div class="admin__gallery">
	<div class="section_status <?= $disabled ?>">
		<div class="current_status"><?= $status ?></div>
		Gallery content
		<div class="gallery__display-files">
			<h3>Display in Gallery</h3>
			<?php

			// To show images that are protected, we need to ask to a controller specially done for that end, to display the image, so we set up a route
			// that will call a controller with the image name, this controller will check the image exists and the output it with the right headers
			if(isset($gallery_images) && !empty($gallery_images)) {
				?>
				<div class="gallery_images__wrapper">
					<?php
					foreach ($gallery_images as $image_id => $each_active_image) {
						if($each_active_image['in_gallery']){
						?>
						<div class="uploaded_image" data-image-id="<?= $image_id ?>">
							<div class="gallery_details">
								<div class="gallery_order">#<?= ($each_active_image['image_order'])?$each_active_image['image_order']:'' ?></div>
								<div class="gallery_show">Deactivate</div>
							</div>
							<img src="/image/<?= $each_active_image['filename'] ?>">
							<div class="image_filename"><?= $each_active_image['filename'] ?></div>
						</div>
						<?php
						}
					}
					?>
				</div>
				<?php
			}else {
				/// this is not gonna be shown ever, so when the gallery is empty i need to find other way to say it's empty
				?>
				<p>
					There are currently no images visible in Valhalla Lanes' Gallery.
				</p>
				<?php
			}
			?>
		</div>

		<hr/>

		<div class="gallery__existing-files">
			<h3>Existing Files</h3>
			<?php
			// To show images that are protected, we need to ask to a controller specially done for that end, to display the image, so we set up a route
			// that will call a controller with the image name, this controller will check the image exists and the output it with the right headers
			if(isset($gallery_images) && !empty($gallery_images)) {
				?>
				<div class="uploaded_images__wrapper">
					<?php
					foreach ($gallery_images as $imageID => $eachimage) {
						if(!$eachimage['in_gallery']){
						?>
						<div class="uploaded_image">
							<div class="image_filename"><?= $eachimage['filename'] ?></div>
							<img src="/image/<?= $eachimage['filename'] ?>">
							<div class="gallery_action">Activate</div>
						</div>
						<?php
						}
					}
					?>
				</div>
				<?php
			}else {
				?>
				<p>
					There are currently no images upload and/or published.
				</p>
				<?php
			}
			?>
		</div>
	</div>
</div>

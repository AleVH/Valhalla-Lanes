<div class="admin__upload">
	<div class="section_status <?= $disabled ?>">
		<div class="current_status"><?= $status ?></div>
		Upload content
		<?= $error ?>

		<strong><?php if(isset($totalFiles)) echo "Successfully uploaded ".count($totalFiles)." files"; ?></strong>

		<?= form_open_multipart('upload/doupload', $form_attr) ?>

		<input class="admin__button" type='file' name='files[]' multiple> <br/><br/>
		<p>
			<small>To upload multiple files, press Ctrl key while selecting</small>
		</p>
		<input class="admin__button" type='submit' value='Upload' name='upload' />
		</form>

		<hr>

		<div class="upload__existing-files">
			<h3>Existing Files</h3>
			<?php
			// To show images that are protected, we need to ask to a controller specially done for that end, to display the image, so we set up a route
			// that will call a controller with the image name, this controller will check the image exists and the output it with the right headers
			if(isset($existing) && !empty($existing)) {
				?>
				<div class="uploaded_images__wrapper">
				<?php
				foreach ($existing as $image_id => $eachimage) {
					?>
					<div class="uploaded_image_card">
						<div class="uploaded_image">
							<div class="image_actions" data-image-id="<?= $image_id ?>">
								<div class="actions_button rename">Rename</div>
								<div class="actions_button delete">Delete</div>
								<div class="actions_button publish <?= ($eachimage['is_enabled'])?"published":"unpublished" ?>">Publish</div>
							</div>
							<img src="/image/<?= $eachimage['filename'] ?>">
							<div class="image_name"><?= $eachimage['filename'] ?></div>
						</div>
						<div class="uploaded_image_action">
							<div class="image_filename">
								<h4>Current filename:</h4>
								<span><?= $eachimage['filename'] ?></span>
							</div>
							<hr/>
							<div class="image_filename_edit">
								<h4>New filename:</h4>
								<form class="image_form_filename_edit">
									<input type="hidden" class="new_filename_image_id" name="new_filename_image_id" value="<?= $image_id ?>">
									<input type="text" class="image_new_filename" name="image_new_filename" placeholder="Type in the new filename" required>
									<div class="form_filename_edit__buttons">
										<input type="submit" class="filename_edit_submit" value="Save">
										<input type="button" class="filename_edit_cancel" value="Cancel">
									</div>
								</form>
							</div>
							<div class="error-msgs"></div>
						</div>
					</div>
					<?php
				}
				?>
				</div>
					<?php
			}else {
				?>
				<p>
					There are currently no files in the upload folder.
				</p>
				<?php
			}
			?>
		</div>
	</div>
</div>

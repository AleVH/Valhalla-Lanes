<div class="admin__upload">
	<div class="section_status <?= $disabled ?>">
		<div class="current_status"><?= $status ?></div>
		Upload content 2
		<?= $error ?>

		<strong><?php if(isset($totalFiles)) echo "Successfully uploaded ".count($totalFiles)." files"; ?></strong>

		<!--	<form method='post' action='/image-upload/post' enctype='multipart/form-data'>-->
		<?= form_open_multipart('upload/doupload', $form_attr) ?>

		<input type='file' name='files[]' multiple> <br/><br/>
		<!--		<input type="button" class="add_more" value="Add more files" />-->
		<p>
			<small>To upload multiple files, press Ctrl key while selecting</small>
		</p>
		<input type='submit' value='Upload' name='upload' />
		</form>

		<hr>
		<div>
			<h3>Existing Files:</h3>
		</div>
	</div>
</div>

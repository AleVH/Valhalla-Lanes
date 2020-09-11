<div class="admin__upload">
	Upload content
	Msg: <?= $error ?>

	<?= form_open_multipart('upload/doupload', $form_attr) ?>

	<input type="file" name="userfile[]" size="20" />
	<button class="admin__upload add_more">Add More Files</button>
	<br /><br />

	<input type="submit" value="Upload" id="upload_files"/>

	</form>

	<hr>
	<div>
		<h3>Existing Files:</h3>
	</div>
</div>

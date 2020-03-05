<div class="admin__upload">
		Upload content 2
		<?= $error ?>

	<strong><?php if(isset($totalFiles)) echo "Successfully uploaded ".count($totalFiles)." files"; ?></strong>

<!--	<form method='post' action='/image-upload/post' enctype='multipart/form-data'>-->
	<?= form_open_multipart('index.php/upload/doupload', $form_attr) ?>

		<input type='file' name='files[]' multiple> <br/><br/>
		<input type="button" class="add_more" value="Add more files" />
		<input type='submit' value='Upload' name='upload' />
	</form>
</div>

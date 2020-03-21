<div class="gallery__wrapper">
	<h2>Gallery</h2>
	<div class="gallery__image_wrapper">
	<?php foreach($gallery as $image){ ?>
		<div class="image_wrapper">
			<img class="gallery__img" src="<?= base_url() ?>assets/media/gallery/<?= $image ?>" />
		</div>
	<?php } ?>
	</div>
</div>

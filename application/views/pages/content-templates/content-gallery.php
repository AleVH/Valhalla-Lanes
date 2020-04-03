<div class="gallery__wrapper">
	<h2>Gallery</h2>
	<div class="gallery__image_wrapper">
	<?php foreach($gallery as $image){ ?>
		<div class="image_wrapper">
			<a href="<?= base_url() ?>assets/media/gallery/<?= $image ?>" target="_blank">
				<img class="gallery__img" src="<?= base_url() ?>assets/media/gallery/<?= $image ?>" />
			</a>
		</div>
	<?php } ?>
	</div>
</div>

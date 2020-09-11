<div class="gallery__wrapper">
	<h2>Gallery</h2>
	<div class="gallery__image_wrapper">
	<?php
	if(isset($gallery) && !empty($gallery)){
		foreach($gallery as $image){ ?>
			<div class="image_wrapper">
				<a href="/image/<?= $image ?>" target="_blank">
					<img class="gallery__img" src="/image/<?= $image ?>" />
				</a>
			</div>

	<?php	}
	}else{
	?>
		<p>There are currently no images to display.</p>
	<?php
	}
	?>

	</div>
</div>

<div class="gallery_wrapper">

	<?php foreach($gallery as $image){ ?>
		<div class="image_wrapper">
			<img class="gallery_img" src="<?= base_url() ?>assets/media/gallery/<?= $image ?>" />
		</div>
	<?php } ?>

</div>
<div class="sections-section">
    <ul class="navigation">
        <li><a href="home">Home</a></li>
        <li><a href="booking">Book Now</a></li>
        <li><a href="">Videos</a></li>
    </ul>
</div>

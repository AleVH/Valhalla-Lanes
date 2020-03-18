<div class="head">
	<?php if($marquee){ ?>
	<div class="head__marquee">
		<p>Welcome to Valhalla-Lanes 2.0!</p>
	</div>
	<?php } ?>
	<div class="head__title">
		<?php if(isset($title) && ($title != 'Home' || $title != 'Title')) {
//			echo $title;
		}?>
	</div>
	<div class="head__logo">
		<div class="left-text">
			<p>Unleash your inner viking at Valhalla Lanes</p>
		</div>
		<div class="logo-center">
			<img class="logo" src="<?= base_url() ?>assets/media/main_no_background.png">
		</div>
		<div class="right-text">
			<p>Come in and join us, throw axes not tantrums.</p>
		</div>
	</div>
</div>

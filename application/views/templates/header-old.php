<header class="head">
	<div class="head__marquee"><?php if(isset($marquee)) echo $marquee; ?></div>
	<?php if($title !== 'Home'){ ?>
		<h1 class="head__title"><?= $title ?></h1>
	<?php } ?>
</header>

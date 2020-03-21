<div class="menu">
	<div class="menu__mask"><span>Menu</span></div>
	<div class="menu__unmask"><!--Menu 2-->
		<nav class="menu__navigation">
			<div class="menu__item" data-link="home"><span class="menu__section-name">Home</span></div>
			<?php
			foreach($menu as $itemname => $itemstatus){
				if($itemstatus){
			?>
					<div class="menu__item" data-link="<?= $itemname ?>"><span class="menu__section-name"><?= $itemname ?></span></div>
			<?php
				}
			}
			?>
		</nav>
	</div>
</div>

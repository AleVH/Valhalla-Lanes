<div class="menu">
	<div class="menu__mask"><span>Menu</span></div>
	<div class="menu__unmask"><!--Menu 2-->
		<nav class="menu__navigation">
			<?php
			foreach($menu as $itemname => $itemstatus){
				if($itemstatus){
			?>
			<div><span><?= $itemname ?></span></div>
			<?php
				}
			}
			?>
<!--			<div><span>item 2</span></div>-->
<!--			<div><span>item 3</span></div>-->
<!--			<div><span>item 4</span></div>-->
		</nav>
	</div>
</div>

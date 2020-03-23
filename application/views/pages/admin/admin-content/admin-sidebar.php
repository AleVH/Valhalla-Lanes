<div class="admin__sidebar">
	<?php
	sort($sidebar);
	foreach ($sidebar as $item){
		?>
		<div class="sidebar__item <?= str_replace('_', '',$item['name']) ?> <?= ($item['name']==='metrics')?'selected':'' ?>" tabindex="1"><?= ucfirst(str_replace('_', ' ',$item['name'])) ?></div>
	<?php
	}
	?>
</div>

<div class="home__wrapper">

	<?php
	if(isset($news) && !empty($news)){
	?>
	<h2>News</h2>
	<?php
	foreach($news as $eachNewsArticle){
	?>
	<div class="news-article__wrapper">
		<div class="news-article__header">
			<div class="news-article__title"><?= $eachNewsArticle['title'] ?></div>
			<div class="news-article__date">(<?= date('d-m-Y', strtotime($eachNewsArticle['created'])) ?>)</div>
		</div>
		<div class="news-article__text"><?= $eachNewsArticle['text'] ?></div>
	</div>
	<?php
		}
	}else{
	?>
	<h2>Home</h2>
	<div class="news-article__wrapper">
		<div class="news-article__date"></div>
		<div class="news-article__title"><h4>Welcome to Valhalla Lanes 2.0!</h4></div>
		<div class="new-article__text"><p>Check this section for news!</p></div>
	</div>
	<?php } ?>
</div>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Valhalla Lanes Structure/Skeleton</title>
	<link rel="stylesheet" href="<?= base_url() ?>assets/css/test.css" rel="stylesheet" type="text/css">
</head>
<body>
	<div class="head">
		<div class="head__marquee">
			<p>Marquee</p>
		</div>
		<div class="head__title">
			<?php if(isset($title) && ($title !== 'Home' || $title !== 'Title')) {
				echo $title;
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
	<div class="menu">
		<div class="menu__mask"><span>Menu</span></div>
		<div class="menu__unmask"><!--Menu 2-->
			<nav class="menu__navigation">
				<div><span>item 1</span></div>
				<div><span>item 2</span></div>
				<div><span>item 3</span></div>
				<div><span>item 4</span></div>
			</nav>
		</div>
	</div>
	<div class="body">
		<div class="body__content">Content</div>
		<div class="body__sidebar">Sidebar
			<div class="calendar">Days with available slots
				<div class="month">
					<ul>
<!--						<li class="prev">&#10094;</li>-->
<!--						<li class="next">&#10095;</li>-->
						<li><?php echo $calendar['month_name']; ?></li>
					</ul>
				</div>
				<ul class="weekdays">
					<li>Mo</li>
					<li>Tu</li>
					<li>We</li>
					<li>Th</li>
					<li>Fr</li>
					<li>Sa</li>
					<li>Su</li>
				</ul>

				<ul class="days">
					<?php
					$start = "";
					$end = "";
					switch ($calendar['month_first_day']){
						case 'Monday':
							$start = "";
							break;
						case 'Tuesday':
							$start = "<li>&nbsp;</li>";
							break;
						case 'Wednesday':
							$start = "<li>&nbsp;</li><li>&nbsp;</li>";
							break;
						case 'Thursday':
							$start = "<li>&nbsp;</li><li>&nbsp;</li><li>&nbsp;</li>";
							break;
						case 'Friday':
							$start = "<li>&nbsp;</li><li>&nbsp;</li><li>&nbsp;</li><li>&nbsp;</li>";
							break;
						case 'Saturday':
							$start = "<li>&nbsp;</li><li>&nbsp;</li><li>&nbsp;</li><li>&nbsp;</li><li>&nbsp;</li>";
							break;
						case 'Sunday':
							$start = "<li>&nbsp;</li><li>&nbsp;</li><li>&nbsp;</li><li>&nbsp;</li><li>&nbsp;</li><li>&nbsp;</li>";
							break;
					}
					switch ($calendar['month_last_day']){
						case 'Monday':
							$end = "<li>&nbsp;</li><li>&nbsp;</li><li>&nbsp;</li><li>&nbsp;</li><li>&nbsp;</li><li>&nbsp;</li>";
							break;
						case 'Tuesday':
							$end = "<li>&nbsp;</li><li>&nbsp;</li><li>&nbsp;</li><li>&nbsp;</li><li>&nbsp;</li>";
							break;
						case 'Wednesday':
							$end = "<li>&nbsp;</li><li>&nbsp;</li><li>&nbsp;</li><li>&nbsp;</li>";
							break;
						case 'Thursday':
							$end = "<li>&nbsp;</li><li>&nbsp;</li><li>&nbsp;</li>";
							break;
						case 'Friday':
							$end = "<li>&nbsp;</li><li>&nbsp;</li>";
							break;
						case 'Saturday':
							$end = "<li>&nbsp;</li>";
							break;
						case 'Sunday':
							$start = "";
							break;
					}

					for($i = 1; $i <= $calendar['month_length']; $i++){
						if($i === intval($calendar['today'])){
							$start .= "<li><span class='active'>".$i."</span></li>";
						}else{
							$start .= "<li>".$i."</li>";
						}
					}
					echo $start.$end;
					?>
<!--					<li>1</li>-->
<!--					<li>2</li>-->
<!--					<li>3</li>-->
<!--					<li>4</li>-->
<!--					<li>5</li>-->
<!--					<li>6</li>-->
<!--					<li>7</li>-->
<!--					<li>8</li>-->
<!--					<li>9</li>-->
<!--					<li><span class="active">10</span></li>-->
<!--					<li>11</li>-->
				</ul>
			</div>
			<div>Facebook
				<div id="fb-root"></div>
				<script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v5.0&appId=286481651377824&autoLogAppEvents=1"></script>
				<div class="fb-page" data-href="https://www.facebook.com/Valhalla-Lanes-442622456468022/" data-tabs="timeline" data-width="" data-height="" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true"><blockquote cite="https://www.facebook.com/Valhalla-Lanes-442622456468022/" class="fb-xfbml-parse-ignore"><a href="https://www.facebook.com/Valhalla-Lanes-442622456468022/">Valhalla Lanes</a></blockquote></div>
			</div>
			<div>
				Instagram
<!--				<div id="pixlee_container"></div><script type="text/javascript">window.PixleeAsyncInit = function() {Pixlee.init({apiKey:'e9Rst8Q7xhIlCCs3h_is'});Pixlee.addSimpleWidget({widgetId:'24420'});};</script><script src="//instafeed.assets.pixlee.com/assets/pixlee_widget_1_0_0.js"></script>-->
			</div>
		</div>
	</div>
	<div class="footer">
		<script src="<?php echo base_url(); ?>assets/js/jquery-3.4.1.min.js"></script>
		<script src="<?php echo base_url(); ?>assets/js/scripts.js"></script>
		Footer
		&copy; <?= date('Y') ?> Valhalla Lanes All Rights Reserved.
	</div>
</body>
</html>

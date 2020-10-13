<div class="admin__promotions">
	<div class="section_status <?= $disabled ?>">
		<div class="current_status"><?= $status ?></div>
		Promotions content
		<div class="tabs">
			<!-- these are the tabs -->
			<div class="tab active"><div class="tab-box"><div class="tab-title">Existing</div></div></div>
			<div class="tab"><div class="tab-box"><div class="tab-title">Create</div></div></div>
		</div>
		<!-- these are the content of the tabs -->

		<!-- Existing Tab -->

		<!-- Calendar with existing promotions and a list of them -->
		<div class="tab-content promotions-existing active">
			<div class="promotions__calendar">
				<div>Upcoming Promotions</div>
				<div class="calendar">
					<!--			Days with available slots-->
					<div class="month">
						<ul>
							<li><?php echo $calendar['month_name']; ?></li>
						</ul>
					</div>
					<ul class="weekdays">
						<li>Mo</li><li>Tu</li><li>We</li><li>Th</li><li>Fr</li><li>Sa</li><li>Su</li>
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
					</ul>
				</div>
			</div>

			<hr/>

			<div class="promotions__existing">
				<h4>Existing Promotions</h4>
				<div class="promotions__existing-reference">
					<div class="color-ref-label">Reference: </div>
					<div class="color-ref">
						<div class="color-sample disabled-news"></div>
						<div class="color-meaning">Inactive</div>
					</div>
					<div class="color-ref">
						<div class="color-sample enabled-news"></div>
						<div class="color-meaning">Active</div>
					</div>
				</div>
				<div class="promotions__existing-all"><pre><?= var_dump($promotions) ?></pre>
					<?php
					if(isset($promotions) && !empty($promotions)){
						foreach ($promotions as $promoIndex => $eachPromo) {
							?>
							<div id="news-<?= $promoIndex ?>" class="news__wrapper <?= ($eachPromo['is_enabled']) ? 'enabled-news' : 'disabled-news' ?>">
								<div class="news__header">
									<div class="news__header-firstline">
										<div class="news__date"><?= $eachPromo['created'] ?></div>
										<div class="news__title"><?= $eachPromo['title'] ?></div>
										<div class="news__visibility"><span class="news__update-status"></span><input id="news-chbx-<?= $promoIndex ?>" type="checkbox" name="news__checkbox" value="<?= $promoIndex ?>" <?= ($eachPromo['is_enabled'])?'checked':'' ?>></div>
									</div>
									<div class="news__header-secondline">
										<div class="news__author"><?= $eachPromo['author'] ?></div>
									</div>
								</div>
								<div class="news__text">
									<p><?= $eachPromo['message'] ?></p>
								</div>
							</div>
							<?php
						}
					}else{
						?>
						<p>There are currently no promotions.</p>
						<?php
					}
					?>
				</div>
			</div>

		</div>

		<!-- Create Tab -->

		<!-- Form to create promotions -->
		<div class="tab-content promotions-create">
			<div>This is to create a promotion</div>

			<div class="promotions__create">

				<div class="promotions__create-container">

					<div class="promotions__form">
						<?= form_open('promotions/create', $form_attr) ?>

						<div class="promotions__create-form-details">
							<label>
								Title: <input type="text" name="promo-title" class="promo-title" maxlength="30" required title="3 characters minimum and 30 maximum"><span class="promo-title-counter"></span>
							</label>
							<label>
								Message: <input type="text" name="promo-text" class="promo-text" maxlength="100" minlength="10" required title="10 characters minimum and 100 maximum"><span class="promo-text-counter"></span>
							</label>
							<label>
								Start Date: <input type="date" name="promo-start" class="promo-start">
							</label>
							<label>
								End Date: <input type="date" name="promo-end" class="promo-end">
							</label>
							<label>
								Format
								<div class="promotions__create-format-details">
									<label>
										Color: <input type="color" name="promo-format_color" class="promo-format_color" value="#FF0000">
									</label>
									<label>
										Font Size: <input type="range" min="14" max="50" name="promo-format_font-size" class="promo-format_font-size" value="30">
									</label>
									<label>
										Speed: <input type="range" min="3" max="30" name="promo-format_speed" class="promo-format_speed" value="20">
									</label>
								</div>
							</label>
						</div>
						<input type="submit" value="Save Promo">
						</form>
					</div>

					<div class="promotions__promo-preview">
						<div class="preview-example">
							<p class="example">Example text</p>
						</div>
					</div>

				</div>

				<hr />

				<div>
					<h4>Promotions</h4>
					<div class="promotions__list">

						<?php
						if(isset($promotions) && !empty($promotions)){
						?>
								List of all promos with delete button and edit button (so when you click on it, puts it in the form and you can edit the details)
						<?php
						}else{
						?>
							<p>There are currently no promotions.</p>
						<?php
						}
						?>
					</div>
				</div>
<!--				form to edit promotions-->
<!--				<div>current promo</div>-->
<!--				<div>part with font size, color, speed, etc</div>-->
<!--				<div>run promo from a date to another date</div>-->
<!--				<div> when the promo goes off, show default or just shut down?</div>-->
<!--				<div>default promo message</div>-->
			</div>
		</div>
	</div>
</div>

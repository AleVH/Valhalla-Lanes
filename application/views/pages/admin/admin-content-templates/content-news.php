<div class="admin__news">
	<div class="section_status <?= $disabled ?>">
		<div class="current_status"><?= $status ?></div>
		<div>News</div>
		<?= form_open_multipart('news/savenews', $form_attr) ?>
		<div class="news__form">
			<div class="news__form-row">
				<div class="news__form-label">Title</div>
				<input type="text" name="news_title" placeholder="Type the title here...">
			</div>
			<div class="news__form-row">
				<div class="news__form-label">Text</div>
				<textarea name="news_text" cols="200" rows="20" placeholder="Type the text here..."></textarea>
			</div>
			<div class="news__form-row">
				<div class="news__form-submit">
					<input type='submit' value='Save News' name='savenews' />
				</div>
			</div>
		</div>
		</form>

		<hr/>

		<div class="news__articles">
			<h4>Previous Articles</h4>
			<div class="news__articles-reference">
				<div class="color-ref-label">Reference: </div>
				<div class="color-ref">
					<div class="color-sample disabled-news"></div>
					<div class="color-meaning">Not visible</div>
				</div>
				<div class="color-ref">
					<div class="color-sample enabled-news"></div>
					<div class="color-meaning">Visible</div>
				</div>
			</div>
			<div class="news__articles-all">
			<?php
			if(isset($news) && !empty($news)){
				foreach ($news as $newsIndex => $eachNews) {
					?>
					<div id="news-<?= $newsIndex ?>" class="news__wrapper <?= ($eachNews['is_enabled']) ? 'enabled-news' : 'disabled-news' ?>">
						<div class="news__header">
							<div class="news__header-firstline">
								<div class="news__date"><?= $eachNews['created'] ?></div>
								<div class="news__title"><?= $eachNews['title'] ?></div>
								<div class="news__visibility"><span class="news__update-status"></span><input id="news-chbx-<?= $newsIndex ?>" type="checkbox" name="news__checkbox" value="<?= $newsIndex ?>" <?= ($eachNews['is_enabled'])?'checked':'' ?>></div>
							</div>
							<div class="news__header-secondline">
								<div class="news__author"><?= $eachNews['author'] ?></div>
							</div>
						</div>
						<div class="news__text">
							<p><?= $eachNews['text'] ?></p>
						</div>
					</div>
					<?php
				}
			}else{
			?>
			<p>There are currently no news saved.</p>
			<?php
			}
			?>
			</div>
		</div>
	</div>
</div>

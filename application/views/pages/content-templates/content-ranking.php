<div class="ranking__wrapper">
	<h2>Rankings</h2>
	<div class="ranking_tables__wrapper">
		<?php
		if(isset($rankings) && !empty($rankings)){
			foreach($rankings as $aRanking){ ?>
				<div class="ranking_table">
					<div class="ranking_table__rank_details">
						<div class="rank_details_row">
							<div>Title: <?= $aRanking["rank_details"]["title"] ?></div>
						</div>
						<div class="rank_details_row">
							<div>Top <?= $aRanking["rank_details"]["tops"] ?></div>
						</div>
						<div class="rank_details_row">
							<div>From: <?= date('d-m-Y', strtotime($aRanking["rank_details"]["start_date"])) ?></div>
						</div>
						<div class="rank_details_row">
							<?php
							if($aRanking["rank_details"]["end_date"] !== null){
								?>
								<div>Until: <?= date('d-m-Y', strtotime($aRanking["rank_details"]["end_date"])) ?></div>
								<?php
							}else{
								?>
								<div>Until: No end date</div>
								<?php
							}
							?>
						</div>
					</div>
					<div class="ranking_table__players_details">
						<?php
						foreach ($aRanking["players_details"] as $playerPosition => $eachPlayer){
							?>
								<div class="rank_player_row">
									<div class="player_position">#<?= $playerPosition + 1 ?></div>
									<div class="player_name"><?= $eachPlayer["name"] ?></div>
									<div class="player_score"><?= $eachPlayer["score"] ?></div>
								</div>
						<?php
						}
						?>
					</div>
				</div>

			<?php
			}
		}else{
			?>
				<div>
					<p>There are currently no rankings to display.</p>
				</div>
			<?php
		}
		?>

	</div>
</div>

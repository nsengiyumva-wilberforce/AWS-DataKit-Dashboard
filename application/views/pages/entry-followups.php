	<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
		<h1 class="h2">Entry - <?= $entry->title ?></h1>
<!-- 		<div class="btn-toolbar mb-2 mb-md-0">
			<div class="btn-group mr-2">
				<button type="button" class="btn btn-sm btn-outline-secondary">Share</button>
				<button type="button" class="btn btn-sm btn-outline-secondary">Export</button>
			</div>
			<button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle">
				<i data-feather="calendar"></i>
				This week
			</button>
		</div> -->
	</div>
	<!-- <h3><?= $entry->title ?></h3> -->
	<div class="row mb-5">
		<div class="col">

			<?php // $this->custom->print($entry->comp->followups, true) ?>

			<div class="tab-links">
				<?php $counter = 1; ?>
				<?php foreach ($entry->comp->followups as $followup): ?>
				<a href="#tab-followup-<?= $counter ?>" class="tab-link <?php if ($counter == 1) echo 'active' ?>">Followup <?= $counter ?></a>
				<?php $counter++; ?>
				<?php endforeach; ?>
			</div>
	

			<div class="tab-wrapper">
				<?php $counter = 1; ?>
				<?php foreach ($entry->comp->followups as $followup): ?>
				<div class="tab <?php if ($counter == 1) echo 'active' ?>" id="tab-followup-<?= $counter ?>">
					<?php foreach ($followup as $qn): ?>
						<?php if (isset($qn->question) && isset($qn->response)): ?>
						<p>
							<strong><?= $qn->question ?></strong><br>
							<?php if (gettype($qn->response) == 'array') { ?>
								<?php foreach ($qn->response as $response): ?>
									<?= $response ?> 
								<?php endforeach; ?>
							<?php } else { ?>
							<?= $qn->response ?>
							<?php } ?>
						</p>
						<?php endif ?>
					<?php endforeach; ?>

					<?php if (isset($entry->geo)) { ?>
						<p><strong>Geo Cordinates</strong><br><?= $entry->geo ?></p>
					<?php } ?>

					<?php if (isset($followup->photo)) { ?>
						<a href="<?= $entry->media_directory.$followup->photo ?>" target="_blank">
							<img src="<?= $entry->media_directory.$followup->photo ?>" alt="" style="width: 500px;">
						</a>
					<?php } ?>

					<hr class="mt-5 mb-2">
					<small>Created by: <?= $followup->followup_creator ?></small>
				</div>
				<?php $counter++; ?>
				<?php endforeach; ?>




			</div>




		</div>
	</div>



<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">	
<h1 class="h2">Entry - <?= $entry->title ?></h1>
	<div class="btn-toolbar mb-2 mb-md-0">
			<div class="btn-group mr-2">
				<button type="button" class="btn btn-sm btn-outline-secondary">Share</button>
				<button type="button" class="btn btn-sm btn-outline-secondary">Export</button>
			</div>
			<button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle">
				<i data-feather="calendar"></i>
				This week
			</button>
		</div>
		
	</div>
	<div class="row mb-5">
		<div class="col">

			<div class="tab-links">
				<a href="#tab-baseline" class="tab-link active">Baseline</a>
				<a href="#tab-followup" class="tab-link">Followup</a>
			</div>
	

			<div class="tab-wrapper">
				<div class="tab active" id="tab-baseline">
					<div><a href="<?= base_url('entry/'.$entry->response_id.'/edit/baseline') ?>">Edit Baseline</a></div>
					<?php foreach ($entry->comp->baseline as $qn): ?>
						<p>
							<strong><?= $qn->question??"" ?></strong><br>
							<?php if (gettype($qn->response??"") == 'array') { ?>
								<?php foreach ($qn->response as $response): ?>
									<?= $response ?> 
								<?php endforeach; ?>
							<?php } else { ?>
							<?= $qn->response??"" ?>
							<?php } ?>					
						</p>
					<?php endforeach; ?>

					<?php if (isset($entry->responses[0]->coordinates)) { ?>
						<p><strong>Geo Cordinates</strong><br><?= $entry->responses[0]->coordinates ?></p>
					<?php } ?>

					<?php if (isset($entry->baseline->photo_file)) { ?>
						<a href="<?= $entry->media_directory.$entry->comp->baseline->photo ?>" target="_blank">
						<img src="<?= $entry->media_directory.$entry->comp->baseline->photo ?>" alt="" style="width: 500px;">
						</a>
					<?php } ?>

					<hr class="mt-5 mb-2">
					<small>Created by: <?= $entry->responses[0]->creator ?></small>
					<?php //check if the form has been rejected ?>
					<?php if (isset($entry->responses[0]->rejection_status)) { ?>
						<?php if(($entry->responses[0]->rejection_status) == "rejected"){ ?>
						<!-- show form rejected message -->
						<p class="text-danger">This entry was rejected</p>
					<?php } else{ ?>
						<button type="button" id="reject-entry" class="btn btn-outline-danger btn-sm mx-5" data-entry-id="<?=$entry->response_id?>">Reject Entry</button>
					<?php } 
					} else { ?>
						<button type="button" id="reject-entry" class="btn btn-outline-danger btn-sm mx-5" data-entry-id="<?=$entry->response_id?>">Reject Entry</button>
					<?php } ?>
				</div>

				<div class="tab" id="tab-followup">
					<?php if ($entry->followup_count >= 1){ ?>
						<!-- For the old data -->
						<?php if($entry->comp->has_an_array == 0){ ?>
						<p>Most recent of <a href="<?= base_url('entry-followups/'.$entry->response_id) ?>"><?= $entry->followup_count ?> followups</a></p>
						<div><a href="<?= base_url('entry/'.$entry->response_id.'/edit/followup') ?>">Edit Followup</a></div>
						<?php 	
							
							foreach ($entry->comp->followup as $qn): ?>
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
							<?php endif; ?>

							<!-- For the new data -->
						<?php endforeach; ?>

						<?php if (isset($entry->responses[0]->coordinates)) { ?>
							<p><strong>Geo Cordinates</strong><br><?= $entry->responses[0]->coordinates ?></p>
						<?php } ?>

						<?php $entry->comp->followup->photo ?>
							<a href="<?= $entry->media_directory.$entry->comp->followup->photo ?>" target="_blank">
								<img src="<?= $entry->media_directory.$entry->comp->followup->photo ?>" alt="" style="width: 500px;">
							</a>

						<hr class="mt-5 mb-2">
						<small>Created by: <?= $entry->comp->followup->followup_creator ?></small>
						<?php if (isset($entry->responses[0]->rejection_status)) { ?>
						<?php if(($entry->responses[0]->rejection_status) == "rejected"){ ?>
						<!-- show form rejected message -->
						<p class="text-danger">This entry was rejected</p>
					<?php } else{ ?>
						<button type="button" id="reject-entry" class="btn btn-outline-danger btn-sm mx-5" data-entry-id="<?=$entry->response_id?>">Reject Entry</button>
					<?php } 
					} else { ?>
						<button type="button" id="reject-entry" class="btn btn-outline-danger btn-sm mx-5" data-entry-id="<?=$entry->response_id?>">Reject Entry</button>
					<?php } ?>						<?php } else { ?>
							<p>Most recent of <a href="<?= base_url('entry-followups/'.$entry->response_id) ?>"><?= $entry->followup_count ?> followups</a></p>
						<div><a href="<?= base_url('entry/'.$entry->response_id.'/edit/followup') ?>">Edit Followup</a></div>
						<?php 	
							
							foreach ($entry->comp->followup[0] as $qn): ?>
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
							<?php endif; ?>

							<!-- For the new data -->
						<?php endforeach; ?>

						<?php if (isset($entry->responses[0]->coordinates)) { ?>
							<p><strong>Geo Cordinates</strong><br><?= $entry->responses[0]->coordinates ?></p>
						<?php } ?>

						<?php $entry->comp->followup[0]->photo ?>
							<a href="<?= $entry->media_directory.$entry->comp->followup[0]->photo ?>" target="_blank">
								<img src="<?= $entry->media_directory.$entry->comp->followup[0]->photo ?>" alt="" style="width: 500px;">
							</a>

						<hr class="mt-5 mb-2">
						<small>Created by: <?= $entry->comp->followup[0]->followup_creator ?></small>
						<?php if (isset($entry->responses[0]->rejection_status)) { ?>
						<?php if(($entry->responses[0]->rejection_status) == "rejected"){ ?>
						<!-- show form rejected message -->
						<p class="text-danger">This entry was rejected</p>
					<?php } else{ ?>
						<button type="button" id="reject-entry" class="btn btn-outline-danger btn-sm mx-5" data-entry-id="<?=$entry->response_id?>">Reject Entry</button>
					<?php } 
					} else { ?>
						<button type="button" id="reject-entry" class="btn btn-outline-danger btn-sm mx-5" data-entry-id="<?=$entry->response_id?>">Reject Entry</button>
					<?php } ?>						<?php } ?>
						<?php } else { ?>
						<p>No Followup</p>
						<?php } ?>
				</div>
			</div>
		</div>
	</div>

	<script>
		$(document).ready(function() {
			$('#reject-entry').click(function() {
				var r = confirm("Are you sure you want to reject this entry?");
				if (r == true) {
					console.log('rejected');
				}
			});
		});
	</script>

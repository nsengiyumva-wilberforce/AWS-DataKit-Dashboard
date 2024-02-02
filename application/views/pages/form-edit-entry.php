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

<form action="<?= base_url('form/edit-entry/'.$entry->response_id.'/'.$target) ?>" method="POST" class="mb-5">
	<?php $i = 1; ?>
	<?php $entry_baseline = (array) $entry->comp->baseline; ?>
	<?php if (isset($entry->comp->followup)) { $entry_followup = (array) $entry->comp->followup; } ?>

	<?php foreach ($form->question_list as $question): ?>
		<?php $question->question_number = $i; ?>
		<?php if ($target == 'baseline') { ?>
			<?php $question->question_answer = $entry_baseline[$i-1]->response; ?>
		<?php } elseif ($target == 'followup') { ?>
			<?php $question->question_answer = $entry_followup[$i-1]->response; ?>
		<?php } else { ?>
			<?php $question->question_answer = []; ?>
		<?php } ?>
		<?php $question->app_lists = $app_lists; ?>
		<?php $question->target = $target; ?>
		<?php $this->load->view('ajax-pages/question-form-card-view', $question); ?>
		<?php $i++; ?>
	<?php endforeach; ?>
	<div class="card">
		<div class="card-body">
			<button type="submit" class="btn btn-primary">Save Changes</button>
		</div>
	</div>
</form>


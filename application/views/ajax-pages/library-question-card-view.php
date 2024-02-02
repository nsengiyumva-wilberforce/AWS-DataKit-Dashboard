<div class="card question-card my-2" id="question-card-<?= $question_id ?>" data-question-id="<?= $question_id ?>">
	<div class="card-body">
		<p><span class="question-number"><?= $question_number ?></span>. <?= $question ?></p>
		<?php if (!is_null($answer_values)): ?>
			<?php if ($answer_type == 'checkbox' || $answer_type == 'radio') { ?>
			<?php $input = $answer_type == 'checkbox' ? 'square' : 'circle'; ?>
			<ul class="list-unstyled">
				<?php foreach ($answer_values as $answer): ?>
				<li>
					<i data-feather="<?= $input ?>"></i> <?= $answer ?>
				</li>
				<?php endforeach; ?>
			</ul>
			<?php } elseif ($answer_type == 'app_list') { ?>
				<!-- <span>App list value</span> -->
			<?php } ?>
		<?php endif; ?>
	</div>
    <div class="card-footer">
        <a href="<?= base_url('ajax/edit-library-question-form/'.$question_id) ?>" class="btn btn-sm btn-success edit-library-question" data-question-id="<?= $question_id ?>">Edit</a>
        <a href="<?= base_url('ajax/delete-library-question/'.$question_id) ?>" class="btn btn-sm btn-danger btn-delete-question" data-target-element="#question-card-<?= $question_id ?>" data-question-id="<?= $question_id ?>">Remove</a>
    </div>
</div>

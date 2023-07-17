<div class="card question-card my-2" data-question-id="<?= $question_id ?>">
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
</div>
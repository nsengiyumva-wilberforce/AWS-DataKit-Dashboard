<div class="card question-card my-2" data-question-id="<?= $question_id ?>">
	<div class="card-header">
		<nav class="nav d-inline-flex float-right">
			<a class="nav-link py-0 edit-question" data-question-id="<?= $question_id ?>" title="Edit" href="#">
				<i data-feather="edit-2"></i>
			</a>
			<a class="nav-link py-0 delete-question" data-question-id="<?= $question_id ?>" title="Delete" href="#">
				<i data-feather="trash"></i>
			</a>
			<a class="nav-link py-0 add-logic-to-question btn-logic" href="<?= base_url('ajax/logic-question/'.$form_id.'/'.$question_id) ?>" data-question-id="<?= $form_id ?>" title="Add Conditional Logic" href="#">
				<i data-feather="code"></i>
			</a>
		</nav>
	</div>
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

				<!-- question condions -->
		<?php if (isset($conditions) && !is_null($conditions)): ?>
			<?php echo $conditions; ?>
		<?php endif; ?>
	</div>
</div>

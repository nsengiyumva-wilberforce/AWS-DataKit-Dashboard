<div class="card question-card my-2" data-question-id="<?= $question_id ?>">
	<div class="card-body">
		<p><span class="question-number"><?= $question_number ?></span>. <?= $question ?> <!-- <small>[<?= $question_id ?>]</small>--></p>
		<?php if (!is_null($answer_values) || !isset($answer_values)): ?>
			<?php if ($answer_type == 'checkbox') { ?>
				<?php foreach ($answer_values as $answer): ?>
				<div class="form-check">
					<input class="form-check-input" type="checkbox" value="<?= $answer ?>" id="checkbox-<?= $question_id ?>" name="qn<?= $question_id ?>[]" <?php if (in_array($answer, is_array($question_answer)? $question_answer : [])) { echo 'checked'; } ?>>
					<label class="form-check-label" for="checkbox-<?= $question_id ?>"><?= $answer ?></label>
				</div>
				<?php endforeach; ?>
			<?php } elseif ($answer_type == 'radio') { ?>
				<?php foreach ($answer_values as $answer): ?>
				<div class="form-check">
					<input class="form-check-input" type="radio" value="<?= $answer ?>" id="radio-<?= $question_id ?>" name="qn<?= $question_id ?>" <?php if ($answer == $question_answer) { echo 'checked'; } ?>>
					<label class="form-check-label" for="radio-<?= $question_id ?>"><?= $answer ?></label>
				</div>
				<?php endforeach; ?>
			<?php } elseif ($answer_type == 'app_list') { ?>
				<select class="form-control" name="qn<?= $question_id ?>" id="">
					<?php $app_list = $app_lists->{$answer_values->db_table}; ?>
					<?php foreach ($app_list as $list_item): ?>
					<option value="<?= $list_item->name ?>" <?php if ($list_item->name == $question_answer) { echo 'selected'; } ?>><?= $list_item->name ?></option>
					<?php endforeach; ?>
				</select>
			<?php } else { ?>
				<input class="form-control" type="<?= $answer_type ?>" name="qn<?= $question_id ?>" value="<?= $question_answer ?>">
			<?php } ?>
		<?php endif; ?>
	</div>
</div>
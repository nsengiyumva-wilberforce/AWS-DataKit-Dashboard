
<form action="<?= base_url($form_action) ?>" method="POST" id="form-builder-question" class="card">
    <?php if (isset($question)): ?>
        <input type="hidden" name="question_id" value="<?= $question->question_id ?>">
    <?php endif; ?>

	<div class="card-body">
		<div class="row">
			<div class="col-8" id="question-detail">
				<!-- Question -->
				<div class="form-group">
					<input type="text" name="question" class="form-control" placeholder="Enter your question" value="<?php if (isset($question)) { echo $question->question; } ?>" />
				</div>
				<div id="answer-values-wrapper">
					<?php if (isset($question)): ?>
						<!-- radio = 3, checkbox = 2 -->
						<?php if ($question->answer_type_id == 3 || $question->answer_type_id == 2): ?>
							<?php foreach ($question->answer_values as $value): ?>
							<div id="answer-values" id="sortable">
								<div class="form-group d-flex">
									<span class="my-2 mr-3"><i data-feather="move"></i></span>
									<input type="text" name="answer_values[]" class="form-control w-100" placeholder="Enter Answer" value="<?= $value ?>">
									<a href="#" class="remove-answer-value my-2 ml-3"><i data-feather="x-circle"></i></a>
								</div>
							</div>
							<?php endforeach; ?>
							<div class="form-group d-flex">
								<a href="javascript:;" id="add-answer-value" data-dom='<div class="form-group d-flex"><span class="my-2 mr-3"><i data-feather="move"></i></span><input type="text" name="answer_values[]" class="form-control w-100" placeholder="Enter Answer" value=""><a href="#" class="remove-answer-value my-2 ml-3"><i data-feather="x-circle"></i></a></div>'><i data-feather="plus"></i> Add another answer</a>
							</div>
						<?php endif; ?>

						<?php if ($question->answer_type_id == 7): ?>
						<div class="form-group">
						<select name="answer_values[]" class="form-control">
							<option value="">Select list</option>
							<?php foreach ($app_list as $list): ?>
							<option value="<?= $list ?>" <?php if ($question->answer_values->db_table == $list) { echo 'selected'; } ?>><?= ucfirst(str_replace('_', ' ', str_replace('app_', '', $list))) ?></option>
							<?php endforeach; ?>
						</select>
						</div>

						<?php endif; ?>
					<?php endif; ?>
				</div>

			</div>
			<div class="col-4">
				<div class="form-group">
					<select name="answer_type_id" id="answer-type" class="form-control" placeholder="Answer Type" required>
						<?php foreach ($answer_types as $type): ?>
						<option data-name="<?= $type->machine_name ?>" value="<?= $type->answer_type_id ?>" <?php echo (isset($question) && $question->answer_type_id == $type->answer_type_id) ? 'selected' : '' ; ?>><?= $type->label ?></option>
						<?php endforeach; ?>
					</select>
				</div>
			</div>
		</div>
	</div>
	<div class="card-footer d-flex justify-content-between">
		<div>
            <?php if (isset($question)) { ?>
                <button type="submit" class="btn btn-sm btn-primary btn-update-question" data-target-element="#question-card-<?= $question->question_id ?>">Save Changes</button>
            <?php } else { ?>
                <button type="submit" class="btn btn-sm btn-primary btn-save-question">Save</button>
            <?php } ?>			
			<button id="remove-question-form" class="btn btn-sm btn-primary">Cancel</button>
		</div>
	</div>
</form>

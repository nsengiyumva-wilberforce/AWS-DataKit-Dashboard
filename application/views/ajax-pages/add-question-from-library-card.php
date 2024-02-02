<form class="card" id="form-builder-question" action="<?= base_url($form_action) ?>" method="POST">
		<div class="card-body">
		<div class="row">
            <div class="col-8">
	    <input type="hidden" name="form_id" value="<?= $form_id ?>">
                <div class="form-group">
                    <label>Select Question from Library</label>
                    <select class="form-control" name="question_id" required>
                    <option></option>
                    <?php foreach($question_list as $qn): ?>
                    <option value="<?= $qn->question_id ?>"><?= $qn->question ?></option>
                    <?php endforeach; ?>
                    </select>
                </div>
            </div>
		</div>
	</div>
	<div class="card-footer d-flex justify-content-between">
		<div>
            <button type="submit" class="btn btn-sm btn-primary btn-save-question">Save</button>
			<button id="remove-question-form" class="btn btn-sm btn-primary">Cancel</button>
		</div>
	</div>
</form>

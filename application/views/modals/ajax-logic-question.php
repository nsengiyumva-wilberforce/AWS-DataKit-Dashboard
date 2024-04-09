<form class="card add-logic-to-question-form" id="add-logic-to-question-form-<?= $question->question_id ?>" action="<?= base_url('ajax/create-conditional-logic') ?>" method="POST" data-target-element="#logic-help-text-<?= $question->question_id ?>">
	<div class="card-body">
		<div class="row">
			<div class="col-12">
                <!-- <p><strong><?= $question->question ?></strong></p> -->
                <input type="hidden" name="form_id" value="<?= $form_id ?>">
                <input type="hidden" name="question_id" value="<?= $question->question_id ?>">
                <?php $this->load->view('modals/partial-modal-logic-question') ?>
                <div id="answer-condition-wrapper"></div>
                <div id="form-status-bar"></div>
			</div>
		</div>
	</div>
	<div class="card-footer d-flex justify-content-between">
		<div>
            <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
            <button type="submit" class="btn btn-sm btn-primary" id="add-logic-to-question-btn" data-target-element="#logic-help-text-<?= $question->question_id ?>">Submit</button>
			<!-- <button type="submit" class="btn btn-sm btn-primary btn-save-question">Save</button> -->
			<button id="remove-logic-to-question-form" class="btn btn-sm btn-primary" data-target-element="add-logic-to-question-form-<?= $question->question_id ?>">Cancel</button>
        </div>
	</div>
</form>

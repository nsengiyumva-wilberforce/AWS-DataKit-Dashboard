<div class="modal-header">
	<h5 class="modal-title" id="staticBackdropLabel">Logic Question</h5>
	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		<span aria-hidden="true">&times;</span>
	</button>
</div>
<div class="modal-body">
    <p class="lead"><strong><?= $question->question ?></strong></p>
    <form id="add-logic-to-question-form" action="<?= base_url('ajax/create-conditional-logic') ?>" method="POST">
        <input type="hidden" name="form_id" value="<?= $form_id ?>">
        <input type="hidden" name="question_id" value="<?= $question->question_id ?>">
        <?php $this->load->view('modals/partial-modal-logic-question') ?>
        <div id="answer-condition-wrapper"></div>
        <div id="form-status-bar"></div>
    </form>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
	<button type="submit" class="btn btn-primary" id="add-logic-to-question-btn" data-target-element="#logic-help-text-<?= $question->question_id ?>">Submit</button>
</div>

	<!-- <button type="submit" class="btn btn-primary" form="add-logic-to-question-form">Submit</button> -->

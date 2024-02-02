<div class="modal-header">
	<h5 class="modal-title" id="staticBackdropLabel">Library Question</h5>
	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		<span aria-hidden="true">&times;</span>
	</button>
</div>
<div class="modal-body">
    <form id="modal-jquery-form" action="<?= base_url('ajax/create-form-question-from-library') ?>" method="POST">
        <input type="hidden" name="form_id" value="<?= $form_id ?>">
        <div class="form-group">
            <label>Select Question</label>
            <select class="form-control" name="question_id" required>
            <option></option>
            <?php foreach($question_list as $qn): ?>
            <option value="<?= $qn->question_id ?>"><?= $qn->question ?></option>
            <?php endforeach; ?>
            </select>
        </div>
        
    </form>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
	<button type="submit" class="btn btn-primary" form="modal-jquery-form">Submit</button>
</div>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
	<h1 class="h2">Form Builder</h1>
	<div class="btn-toolbar mb-2 mb-md-0">
		<div class="btn-group mr-2">
			<a href="<?= base_url('form/'.$form->form_id) ?>" class="btn btn-sm btn-outline-secondary"><i data-feather="eye"></i> Preview</a>
			<a href="<?= base_url('form/'.$form->form_id.'/settings') ?>" class="btn btn-sm btn-outline-secondary"><i data-feather="settings"></i> Settings</a>
			<a href="#" class="btn btn-sm btn-outline-secondary"><i data-feather="trash"></i> Delete</a>
		</div>
	</div>
</div>

<h3><?= $form->title ?></h3>

<div id="form-builder" data-form-id="<?= $form->form_id ?>">
	<?php if($form->question_list==null) $form->question_list=[]; ?>

	<?php $i = 1; ?>
	<?php foreach ($form->question_list as $question): ?>
		<?php $question->question_number = $i; ?>
		<?php $question->form_id = $form->form_id; ?>
		<?php $this->load->view('ajax-pages/question-card', $question); ?>
		<?php $i++; ?>
	<?php endforeach; ?>

</div>

<a href="javascript:;" id="new-question" class="btn btn-primary my-4 mr-2" data-form-id="<?= $form->form_id ?>">New Question</a>
<a href="<?= base_url('ajax/use-question-library/'.$form->form_id) ?>" id="new-question-from-library" class="btn btn-success" data-question-id="<?= $form->form_id ?>">Question Library</a>



<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Edit Form</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">

				<form id="form-update-form" action="<?= base_url('form/update-form/'.$form->form_id) ?>" method="POST">
					<div class="form-group">
						<label>Form Title</label>
						<input type="text" class="form-control" name="title" placeholder="Untitled Form" value="<?= $form->title ?>">
					</div>
					<div class="form-group form-check">
						<input type="checkbox" id="checkbox1" class="form-check-input" name="is_geotagged" value="1" <?= $form->is_geotagged == 1 ? 'checked' : '' ?>>
						<label class="form-check-label" for="checkbox1">Collect Geo Location</label>
					</div>
					<div class="form-group form-check">
						<input type="checkbox" id="checkbox2" class="form-check-input" name="is_photograph" value="1" <?= $form->is_photograph == 1 ? 'checked' : '' ?>>
						<label class="form-check-label" for="checkbox2">Capture Photos</label>
					</div>
					<div class="form-group form-check">
						<input type="checkbox" id="checkbox3" class="form-check-input" name="is_followup" value="1" <?= $form->is_followup == 1 ? 'checked' : '' ?>>
						<label class="form-check-label" for="checkbox3">Enable Follow Up Data Collection</label>
					</div>


					<div class="form-group">
						<label>Entry Title</label>
						<input type="text" class="form-control" id="tokenfield-title" name="entry_title" placeholder="Entry Title" value="">
					</div>
					<div class="form-group">
						<label>Entry Sub Title</label>
						<input type="text" class="form-control" id="tokenfield-sub-title" name="entry_subtitle" placeholder="Entr Sub Title" value="">
					</div>
					<div class="form-group form-check">
						<input type="checkbox" id="checkbox3" class="form-check-input" name="is_publish" value="1" <?= $form->is_publish == 1 ? 'checked' : '' ?>>
						<label class="form-check-label" for="checkbox3">Publish this Form</label>
					</div>

				</form>

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				<button type="submit" class="btn btn-primary" form="form-update-form">Save Changes</button>
			</div>
		</div>
	</div>
</div>


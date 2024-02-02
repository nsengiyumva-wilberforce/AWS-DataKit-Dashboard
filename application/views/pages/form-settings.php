<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
	<h1 class="h2">Form Settings</h1>
	<div class="btn-toolbar mb-2 mb-md-0">
		<div class="btn-group mr-2">
			<a href="<?= base_url('form/'.$form->form_id.'/edit') ?>" class="btn btn-sm btn-outline-secondary"><i data-feather="eye"></i> Return</a>
		</div>
	</div>
</div>

<h3><?= $form->title ?></h3>

<form id="form-update-form" class="mb-5" action="<?= base_url('form/update-form/'.$form->form_id) ?>" method="POST">
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
		<label>Number of days between followups</label>
		<input type="number" class="form-control" id="followup-interval" name="followup_interval" min="0" placeholder="Interval in days" value="<?= $form->followup_interval ?? 30 ?>">
	</div>

	<div class="form-group">
		<label>Entry Title</label>
		<input type="text" class="form-control" id="tokenfield-title" name="entry_title" placeholder="Entry Title" value="">
	</div>
	<div class="form-group">
		<label>Entry Sub Title</label>
		<input type="text" class="form-control" id="tokenfield-sub-title" name="entry_subtitle" placeholder="Entry Sub Title" value="">
	</div>
	<div class="form-group">
		<label>Entry Followup Prefills</label>
		<input type="text" class="form-control" id="tokenfield-followup-prefill" name="followup_prefill" placeholder="Followup Prefill" value="">
	</div>
	<div class="form-group form-check">
		<input type="checkbox" id="checkbox3" class="form-check-input" name="is_publish" value="1" <?= $form->is_publish == 1 ? 'checked' : '' ?>>
		<label class="form-check-label" for="checkbox3">Publish this Form</label>
	</div>
	<button type="submit" class="btn btn-primary" form="form-update-form">Save Changes</button>
</form>



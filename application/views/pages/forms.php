<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
	<h1 class="h2">Forms</h1>
	<div class="btn-toolbar mb-2 mb-md-0">
		<!-- Button trigger modal -->
		<?php if ($this->session->permissions->create_form): ?>
		<button type="button" class="btn btn-sm btn-outline-secondary" data-toggle="modal" data-target="#exampleModal">
			<i data-feather="plus-square"></i>
			Form Builder
		</button>
		<?php endif; ?>
	</div>
</div>
<div class="table-responsive mb-5">
	<table id="datatable" class="table">
		<thead>
			<tr>
				<th scope="col">Name</th>
				<th scope="col">Last Modified</th>
				<th scope="col">Status</th>
				<th scope="col">Action</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($forms as $form): ?>
			<tr>
				<td><a href="<?= base_url('form/'.$form->form_id) ?>"><i data-feather="file-text"></i> <?= $form->title ?></a></td>
				<td><span data-toggle="Last modified on Jul 4, 2019" title="Last modified on <?= date('M j, Y', strtotime($form->date_created)) ?>"><?= date('Y-m-d', strtotime($form->date_modified)) ?></span></td>
				<td><?= $form->is_publish ? 'Published' : 'Deactivated' ?></td>
				<td>
					<nav class="nav d-inline-flex">
						<a class="nav-link py-0" data-toggle="View" title="View" href="<?= base_url('form/'.$form->form_id) ?>"><i data-feather="eye"></i></a>
						<?php if ($this->session->permissions->edit_form): ?>
						<a class="nav-link py-0" data-toggle="Edit" title="Edit" href="<?= base_url('form/'.$form->form_id.'/edit') ?>"><i data-feather="edit-2"></i></a>
						<?php endif; ?>
						<?php if ($this->session->permissions->delete_form): ?>
						<a class="nav-link py-0 confirm-delete" data-toggle="Delete" title="Delete" href="<?= base_url('form/'.$form->form_id.'/delete') ?>"><i data-feather="trash"></i></a>
						<?php endif; ?>
					</nav>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>




<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">New Form</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">

				<form id="form-create-form" action="<?= base_url('ajax/create-form') ?>" method="POST">
					<div class="form-group">
						<label>Form Title</label>
						<input type="text" class="form-control" name="title" placeholder="Untitled Form">
					</div>
					<div class="form-group form-check">
						<input type="checkbox" id="checkbox1" class="form-check-input" name="is_geotagged" value="1">
						<label class="form-check-label" for="checkbox1">Collect Geo Location</label>
					</div>
					<div class="form-group form-check">
						<input type="checkbox" id="checkbox2" class="form-check-input" name="is_photograph" value="1">
						<label class="form-check-label" for="checkbox2">Capture Photos</label>
					</div>
					<div class="form-group form-check">
						<input type="checkbox" id="checkbox3" class="form-check-input" name="is_followup" value="1">
						<label class="form-check-label" for="checkbox3">Enable Follow Up Data Collection</label>
					</div>
				</form>

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				<button type="submit" class="btn btn-primary" form="form-create-form">Create Form</button>
			</div>
		</div>
	</div>
</div>
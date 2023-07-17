<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
	<h1 class="h2">Entries</h1>
	<div class="btn-toolbar mb-2 mb-md-0">
<!-- 		<div class="btn-group mr-2">
			<button type="button" class="btn btn-sm btn-outline-secondary">Share</button>
			<button type="button" class="btn btn-sm btn-outline-secondary">Export</button>
		</div>
		<button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle">
			<i data-feather="calendar"></i>
			This week
		</button> -->
	</div>
</div>
<div class="table-responsive mb-5">
	<table id="datatable" class="table">
		<thead>
			<tr>
				<th scope="col">Name</th>
				<!-- <th scope="col">Last Modified</th> -->
				<!-- <th scope="col">Action</th> -->
			</tr>
		</thead>
		<tbody>
			<?php foreach ($forms as $form): ?>
			<tr>
				<td><a href="<?= base_url('form-entries/'.$form->form_id) ?>"><i data-feather="file-text"></i> <?= $form->title ?></a></td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>
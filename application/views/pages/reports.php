<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
	<h1 class="h2">Reports</h1>
</div>
<div class="table-responsive mb-5">
	<table id="datatable" class="table">
		<thead>
			<tr>
				<th scope="col">Name</th>
				<th scope="col">Links</th>
				<!-- <th scope="col">Action</th> -->
			</tr>
		</thead>
		<tbody>
			<?php foreach ($forms as $form): ?>
			<tr>
				<td><i data-feather="file-text"></i> <?= $form->title ?></td>
				<td><a href="<?= base_url('report/form/'.$form->form_id.'/data/baseline') ?>">Baseline</a> | <?php if ($form->is_followup == 1): ?><a href="<?= base_url('report/form/'.$form->form_id.'/data/followup') ?>">Followup</a> |<?php endif; ?> <a href="<?= base_url('report/form/'.$form->form_id.'/data/aggregated') ?>">Aggregated</a></td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>
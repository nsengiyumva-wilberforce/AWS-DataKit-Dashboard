<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
	<h1 class="h2">Districts</h1>
	<div class="btn-toolbar mb-2 mb-md-0">
		<!-- Button trigger modal -->
		<a href="<?= base_url('add-district') ?>" class="btn btn-sm btn-outline-secondary">
			<i data-feather="plus"></i>
			Add District
		</a>
	</div>
</div>
<div class="table-responsive mb-5">
	<table id="datatable" class="table">
		<thead>
			<tr>
				<th scope="col">Name</th>
				<th scope="col">Region</th>
				<th scope="col">Action</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($districts as $district): ?>
			<tr>
				<td><?= $district->name ?></td>
				<td><?= $district->region ?></td>
				<td>
					<nav class="nav d-inline-flex">
						<a class="nav-link py-0" data-toggle="Edit" title="Edit" href="<?= base_url('district/'.$district->district_id.'/edit') ?>"><i data-feather="edit-2"></i></a>
						<a class="nav-link py-0 confirm-delete" data-toggle="Delete" title="Delete" href="<?= base_url('district/'.$district->district_id.'/delete') ?>"><i data-feather="trash"></i></a>
					</nav>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>


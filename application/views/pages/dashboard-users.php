	<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
		<h1 class="h2">Dashboard Users</h1>
		<div class="btn-toolbar mb-2 mb-md-0">
			<?php if ($this->session->permissions->create_admins): ?>
			<a href="<?=  base_url('dashboard-user/add') ?>" class="btn btn-sm btn-outline-secondary mr-1">
				<i data-feather="plus"></i>
				Add User
			</a>
			<a href="<?=  base_url('admin-roles') ?>" class="btn btn-sm btn-outline-secondary">
				<i data-feather="eye"></i>
				Admin Roles
			</a>
			<?php endif; ?>
		</div>
	</div>
	<!-- <h2>Section title</h2> -->

	<div class="table-responsive">
		<table id="datatable" class="table">
			<thead>
				<tr>
					<th scope="col">Name</th>
					<th scope="col">Role</th>
					<th scope="col">Region</th>
					<th scope="col">Email</th>
					<th scope="col">Status</th>
					<th scope="col">Action</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($users as $user): ?>
				<tr>
					<td><a href="<?= base_url('dashboard-user/'.$user->user_id) ?>"><?= trim($user->first_name.' '.$user->last_name) ?></a></td>
					<td><?= $user->role_label ?></td>
					<td><?= $user->region ?></td>
					<td><?= $user->email ?></td>
					<td><?= $user->active ? 'Active' : 'Deactiveted' ?></td>
					<td>
						<nav class="nav d-inline-flex">
							<a class="nav-link py-0" data-toggle="View" title="View" href="<?= base_url('dashboard-user/'.$user->user_id) ?>"><i data-feather="eye"></i></a>
							<?php if ($this->session->permissions->edit_admins): ?>
							<a class="nav-link py-0" data-toggle="Edit" title="Edit" href="<?= base_url('dashboard-user/'.$user->user_id.'/edit') ?>"><i data-feather="edit-2"></i></a>
							<?php endif; ?>
							<?php if ($this->session->permissions->delete_admins): ?>
							<a class="nav-link py-0 confirm-delete" data-toggle="Delete" title="Delete" href="<?= base_url('dashboard-user/'.$user->user_id.'/delete') ?>"><i data-feather="trash"></i></a>
							<?php endif; ?>
						</nav>
					</td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>

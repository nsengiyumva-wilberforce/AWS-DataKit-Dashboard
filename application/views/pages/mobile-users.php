	<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
		<h1 class="h2">Mobile Users</h1>
		<div class="btn-toolbar mb-2 mb-md-0">
			<?php if ($this->session->permissions->create_user): ?>	
			<a href="<?=  base_url('mobile-user/add') ?>" class="btn btn-sm btn-outline-secondary mr-1">
				<i data-feather="plus"></i>
				Add User
			</a>
			<?php endif; ?>
			<a href="<?=  base_url('mobile-user/report') ?>" class="btn btn-sm btn-outline-secondary">
				<i data-feather="file-text"></i>
				Report
			</a>
		</div>
	</div>
	<!-- <h2>Section title</h2> -->

	<div class="table-responsive mb-5">
		<table id="datatable" class="table">
			<thead>
				<tr>
					<th scope="col">Name</th>
					<th scope="col">Region</th>
					<th scope="col">Email</th>
					<th scope="col">Status</th>
					<th scope="col">Action</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($users as $user): ?>
				<tr>
					<td><a href="<?= base_url('mobile-user/'.$user->user_id) ?>"><?= trim($user->first_name.' '.$user->last_name) ?></a></td>
					<td><?= $user->region_name ?></td>
					<td><?= $user->email ?></td>
					<td><?= $user->active ? 'Active' : 'Deactiveted' ?></td>
					<td>
						<nav class="nav d-inline-flex">
							<a class="nav-link py-0" data-toggle="View" title="View" href="<?= base_url('mobile-user/'.$user->user_id) ?>"><i data-feather="eye"></i></a>
							<a class="nav-link py-0" data-toggle="Edit" title="Edit" href="<?= base_url('mobile-user/'.$user->user_id.'/edit') ?>"><i data-feather="edit-2"></i></a>
							<a class="nav-link py-0 confirm-delete" data-toggle="Delete" title="Delete" href="<?= base_url('mobile-user/'.$user->user_id.'/delete') ?>"><i data-feather="trash"></i></a>
						</nav>
					</td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>

<!--                     [user_id] => 1
                    [first_name] => James
                    [last_name] => Smugen
                    [email] => jsmugen@customail.com
                    [password] => smugen
                    [role_id] => 1
                    [region_id] => 1
                    [region_name] => Central
                    [code] => C
                    [date_created] => 2018-11-09 00:00:00
                    [active] => 1 -->
	<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
		<h1 class="h2">Dashboard User</h1>
		<div class="btn-toolbar mb-2 mb-md-0">
			<!-- <div class="btn-group mr-2">
				<button type="button" class="btn btn-sm btn-outline-secondary">Share</button>
				<button type="button" class="btn btn-sm btn-outline-secondary">Export</button>
			</div> -->
			<a href="<?= base_url('dashboard-user/'.$user->user_id.'/edit') ?>" class="btn btn-sm btn-outline-secondary dropdown-toggle">
				<i data-feather="calendar"></i>
				Edit Dashboard User
			</a>
		</div>
	</div>
	<!-- <h3><?= $entry->title ?></h3> -->
	<div class="row mb-5">
		<div class="col">
			<p><strong>Name</strong><br><?= $user->first_name.' '.$user->last_name ?></p>
			<p><strong>Role</strong><br><?= $user->role_label ?></p>
			<p><strong>Email</strong><br><?= $user->email ?></p>
			<p><strong>Status</strong><br><?= $user->active ? 'Active' : 'Deactiveted' ?></p>
		</div>
	</div>


	<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
		<h1 class="h2">Admin Roles</h1>
	</div>
	<!-- <h3><?= $entry->title ?></h3> -->
	<div class="row mb-5">
		<div class="col">

			<?php foreach ($roles as $role): ?>
				<h4><?= $role->label ?></h4>
				<div>
					<ul class="list-unstyled" style="column-count: 5;">
					<?php $permission_list = json_decode($role->permission_list); ?>
					<?php foreach ($permission_list as $permission => $value): ?>
						<li>
								<strong><?= ucwords(str_replace('_', ' ', $permission)) ?>:</strong> <?= $value == 1 ? 'Yes' : 'No' ?>
						</li>
					<?php endforeach; ?>
					</ul>
				</div>
				<hr>				
			<?php endforeach; ?>



		</div>
	</div>


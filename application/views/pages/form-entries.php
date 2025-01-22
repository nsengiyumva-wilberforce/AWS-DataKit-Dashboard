<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
	<h1 class="h2">Entries</h1>
	<div class="btn-toolbar mb-2 mb-md-0">
		<form class="form-inline mr-2" id="filter-entries">
			<label class="my-1 mr-2">Region</label>
			<select name="region_id" id="region_id" class="custom-select my-1 mr-sm-2">
				<option value="0">All Regions</option>
				<?php foreach ($regions as $region): ?>
					<option value="<?= $region->region_id ?>" <?php if ($region->region_id == $this->session->region_id) {
						  echo 'selected';
					  } ?>><?= $region->name ?></option>
				<?php endforeach; ?>
			</select>
			<label class="my-1 mr-2">Date Range</label>
			<input type="text" name="dates" class="form-control my-1 mr-sm-2">
			<label class="my-1 mr-2">Creator</label>
			<select name="creator_id" id="creator" class="custom-select my-1 mr-sm-2">
				<option value="0">All</option>
				<?php
				// Group users by region name
				$grouped_users = [];
				foreach ($users as $user) {
					$grouped_users[$user->region_name][] = $user;
				}

				// Sort users alphabetically within each region
				foreach ($grouped_users as $region_name => &$users_in_region) {
					usort($users_in_region, function ($a, $b) {
						return strcmp($a->first_name, $b->first_name); // Sort by first_name
					});
				}
				unset($users_in_region); // Unset reference to avoid side effects
				
				// Generate the dropdown
				foreach ($grouped_users as $region_name => $users_in_region): ?>
					<optgroup label="<?= htmlspecialchars($region_name) ?>">
						<?php foreach ($users_in_region as $user): ?>
							<option value="<?= $user->user_id ?>" <?php if ($user->user_id == $this->session->creator) {
								  echo 'selected';
							  } ?>><?= $user->first_name . ' ' . $user->last_name ?></option>
						<?php endforeach; ?>
					</optgroup>
				<?php endforeach; ?>
			</select>

			<input type="hidden" name="form_id" value="<?= $form_id ?>">
			<button type="submit" class="btn btn-outline-secondary btn-sm my-1 mr-sm-2">Go</button>

		</form>
	</div>
</div>

<div class="row mb-3">
	<div class="col"><?= $report_title ?></div>
</div>
<div class="row mb-5">
	<div class="col">
		<div id="loader" style="display: none;">
			<div class="d-inline-block" style="border: thin solid #CCCCCC; margin: 0 auto; padding: 5px 15px;">Fetching
				Data...</div>
		</div>
		<div id="ajax-table" style="width: 100%;">
			<table id="dt-entries" class="table">
				<thead>
					<tr>
						<th scope="col">Title</th>
						<th scope="col">Location</th>
						<th scope="col">Created By</th>
						<th scope="col">Followed Up By</th>
						<th scope="col">Last Modified</th>
						<th scope="col">Action</th>
					</tr>
				</thead>
				<!--  -->
			</table>
		</div>
	</div>
</div>
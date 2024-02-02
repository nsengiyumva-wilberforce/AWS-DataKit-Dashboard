<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
	<h1 class="h2">Entries</h1>
	<div class="btn-toolbar mb-2 mb-md-0">
		<form class="form-inline mr-2" id="form-row-data-report" action="<?= base_url('ajax/ajax-form-entries') ?>" method="POST">
			
			<label class="my-1 mr-2">Region</label>
			<select name="region_id" id="region_id" class="custom-select my-1 mr-sm-2">
				<option value="0">All Regions</option>
				<?php foreach ($regions as $region): ?>
				<option value="<?= $region->region_id ?>" <?php if ($region->region_id == $this->session->region_id) { echo 'selected'; } ?>><?= $region->name ?></option>
				<?php endforeach; ?>
			</select>

			<label class="my-1 mr-2">Year</label>
			<select name="year" id="region_id" class="custom-select my-1 mr-sm-2">
				<option value="2024">2024</option>
				<option value="2023">2023</option>
				<option value="2022">2022</option>
				<option value="2021">2021</option>
				<option value="2020">2020</option>
				<option value="2019">2019</option>
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
			<div class="d-inline-block" style="border: thin solid #CCCCCC; margin: 0 auto; padding: 5px 15px;">Fetching Data...</div>
		</div>
		<div id="ajax-table" style="width: 100%;">
			<table id="datatable-entries" class="table">
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
				<tbody>
					<?php foreach ($entries as $entry): ?>
				<?php if (!isset($entry->responses[0]->rejection_status)) { ?>
					<tr id="<?= 'entry-' . $entry->response_id ?>">
					<?php } else {
					if (($entry->responses[0]->rejection_status) == "rejected") {
						?>

						<tr id="<?= 'entry-' . $entry->response_id ?>" class="table-danger">
						<?php } else { ?>
						<tr id="<?= 'entry-' . $entry->response_id ?>" class="table-success">
						<?php } ?>

					<?php } ?>
						<td><a href="<?= base_url('entry/'.$entry->response_id) ?>"><i data-feather="file-text"></i> <?= $entry->title ?></a></td>
						<td>
							<?php if (isset($entry->village)) {
								echo $entry->village;
							} elseif (isset($entry->parish)) {
								echo $entry->parish;
							} elseif (isset($entry->sub_county)) {
								echo $entry->sub_county;
							} echo ', '.$entry->district; ?>
						</td>
						
						<td><?= $entry->creator_id ?></td>
						<td><?= $entry->last_follower??"" ?></td>
					<td data-order="<?= strtotime($entry->updated_at ?? $entry->created_at) ?>">
							<span data-toggle="Last modified on <?= date('M j, Y', strtotime($entry->updated_at ?? $entry->created_at)) ?>" title="Last modified on <?= date('M j, Y', strtotime($entry->updated_at ?? $entry->created_at)) ?>"><?= date('M j, Y', strtotime($entry->updated_at ?? $entry->created_at)) ?></span>
						</td>

						<td>
							<nav class="nav d-inline-flex">
								<a class="nav-link py-0" data-toggle="View" title="View" href="<?= base_url('entry/'.$entry->response_id) ?>"><i data-feather="eye"></i></a>

								<!-- <a class="nav-link py-0" data-toggle="Edit" title="Edit" href="<?= base_url('entry/'.$entry->response_id.'/edit') ?>"><i data-feather="edit-2"></i></a> -->
								<?php if ($this->session->permissions->delete_response): ?>
								<a class="nav-link py-0 confirm-tr-delete" data-toggle="Delete" title="Delete" href="<?= base_url('entry/'.$entry->response_id.'/delete') ?>"><i data-feather="trash"></i></a>
								<?php endif; ?>
							</nav>
						</td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>


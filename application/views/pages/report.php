<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
		<h1 class="h2">Report</h1>
		<div class="btn-toolbar mb-2 mb-md-0">
			<form class="form-inline mr-2" id="form-row-data-report" action="<?= base_url('ajax/raw-data-report') ?>" method="POST">
				<label class="my-1 mr-2">Region</label>
				<select name="region_id" id="region_id" class="custom-select my-1 mr-sm-2">
					<option value="all">All Regions</option>
					<?php foreach ($regions as $region): ?>
					<option value="<?= $region->region_id ?>" <?php if ($region->region_id == $this->session->region_id) { echo 'selected'; } ?>><?= $region->name ?></option>
					<?php endforeach; ?>
				</select>
				<label class="my-1 mr-2">Project</label>
				<select name="project" id="project" class="custom-select my-1 mr-sm-2">
					<option value="all">All Projects</option>
					<?php foreach ($projects as $project): ?>
					<option value="<?= $project->name ?>"><?= $project->name ?></option>
					<?php endforeach; ?>
				</select>
				<input type="text" name="dates" class="form-control my-1 mr-sm-2">
				<input type="hidden" name="form_id" value="<?= $form_id ?>">
				<input type="hidden" name="entry_data" value="<?= $entry_data ?>">
				<button type="submit" class="btn btn-outline-secondary btn-sm my-1 mr-sm-2">Go</button>
			</form>
		</div>		
	</div>

<div class="row mb-3">
	<div class="col"><?= $report_title ?></div>
</div>
	<!-- <h3><?= $entry->title ?></h3> -->
	<div class="row mb-5">
		<div class="col">
			<div id="loader" style="display: none;">
				<div class="d-inline-block" style="border: thin solid #CCCCCC; margin: 0 auto; padding: 5px 15px;">Fetching Data...</div>
			</div>
			<div id="ajax-table" style="width: 100%;">
				<p class="lead">Pick date range for data to show</p>
			</div>
		</div>
	</div>


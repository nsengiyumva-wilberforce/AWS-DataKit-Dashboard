	<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
		<h1 class="h2">Aggregated Report</h1>
		<div class="btn-toolbar mb-2 mb-md-0">
			<form class="form-inline mr-2" id="form-row-data-report" action="<?= base_url('ajax/ajax-agg-entries-report') ?>" method="POST">
				<input type="hidden" name="form_id" value="<?= $form_id ?>">
				<input type="hidden" name="field" value="region_id">
<!-- 				<select name="field" id="field">
					<option value="region_id" selected="selected">Region</option>
					<option value="district_id">District</option>
					<option value="sub_county_id">Sub County</option>
					<option value="parish_id">Parish</option>
					<option value="village_id">Village</option>
				</select> -->
				<label class="my-1 mr-2">Region</label>
				<select name="field_id" id="field_id" class="custom-select my-1 mr-sm-2">
					<?php foreach ($regions as $region): ?>
					<option value="<?= $region->region_id ?>" <?php if ($region->region_id == $this->session->region_id) { echo 'selected'; } ?>><?= $region->name ?></option>
					<?php endforeach; ?>
				</select>

				<label class="my-1 mr-2">Group By</label>
				<select name="group_by" id="group_by" class="custom-select my-1 mr-sm-2">
					<!-- <option value="region">Region</option> -->
					<option value="district">District</option>
					<option value="sub_county">Sub County</option>
					<option value="parish">Parish</option>
					<option value="village" selected="selected">Village</option>
				</select>
				<label class="my-1 mr-2">Project</label>
				<select name="project" id="project" class="custom-select my-1 mr-sm-2">
					<option value="all">All Projects</option>
					<?php foreach ($projects as $project): ?>
					<option value="<?= $project->name ?>"><?= $project->name ?></option>
					<?php endforeach; ?>
				</select>
				<label class="my-1 mr-2">Data</label>
				<select name="data_type" id="data_type" class="custom-select my-1 mr-sm-2">
					<option value="baseline">Baseline</option>
					<option value="followup">Follow-up</option>
				</select>
				<input type="text" name="dates" class="form-control my-1 mr-sm-2">
				<button type="submit" class="btn btn-outline-secondary btn-sm my-1 mr-sm-2">Go</button>
			</form>
		</div>		
	</div>


<div class="row">
	<div class="col"><?= $report_title ?></div>
</div>
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


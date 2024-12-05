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
			<input type="text" name="dates" class="form-control my-1 mr-sm-2">
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
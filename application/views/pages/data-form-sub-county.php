	<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
		<h1 class="h2"><?= $action ?> Sub Counties</h1>
	</div>
	<!-- <h2>Section title</h2> -->

	<form class="box" action="<?= base_url($form_action) ?>" method="POST">
		<div class="form-group">
			<label for="">Region</label>
			<select class="form-control dynamic-select" name="region_id" id="dynamic-list-regions">
				<option value="">Select Region</option>
				<?php foreach ($regions as $region): ?>
				<option value="<?= $region->region_id ?>" <?php echo (isset($sub_county) && $sub_county->region_id == $region->region_id) ? 'selected' : '' ; ?> ><?= $region->name ?></option>
				<?php endforeach; ?>
			</select>
		</div>

		<div class="form-group">
			<label for="">District</label>
			<select class="form-control dynamic-select" name="district_id" <?= ($page_name == 'create-sub-county') ? 'disabled' : '' ?> id="dynamic-list-districts">
				<option value="">Select District</option>
				<?php if ($page_name == 'edit-sub-county'): ?>
				<?php foreach ($districts as $district): ?>
				<option value="<?= $district->district_id ?>" <?php echo (isset($sub_county) && $sub_county->district_id == $district->district_id) ? 'selected' : '' ; ?> ><?= $district->name ?></option>
				<?php endforeach; ?>
				<?php endif; ?>
			</select>
		</div>	

		<div id="clone-wrapper">
			<?php if (!isset($sub_county)): ?>
			<a href="#" id="add-another-field" class="btn btn-primary"><i class="fa fa-plus"></i> Add another field</a>
			<?php endif; ?>

			<div class="form-group cloneable">
				<label for="">Name</label>
				<input type="text" class="form-control" name="name_list" value="<?php if (isset($sub_county)) { echo $sub_county->name; } ?>">
			</div>
		</div>
		<button type="submit" class="btn btn-primary">Submit</button>
	</form>

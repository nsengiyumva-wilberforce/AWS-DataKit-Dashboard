	<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
		<h1 class="h2"><?= $action ?> Dashboard User</h1>
	</div>
	<!-- <h2>Section title</h2> -->

	<form action="<?= base_url($form_action) ?>" method="POST" autocomplete="off">
		<div class="form-group">
			<label for="input1">First Name</label>
			<input type="text" class="form-control" id="input1" name="first_name" value="<?php if (isset($user)) { echo $user->first_name; } ?>">
		</div>
		<div class="form-group">
			<label for="input2">Last Name</label>
			<input type="text" class="form-control" id="input2" name="last_name" value="<?php if (isset($user)) { echo $user->last_name; } ?>">
		</div>
		<div class="form-group">
			<label for="input3">Email Address</label>
			<input type="email" class="form-control" id="input3" name="email" value="<?php if (isset($user)) { echo $user->email; } ?>">
		</div>
		<div class="form-group">
			<label for="input3">Password</label>
			<input type="password" class="form-control" id="input3" name="password" value="<?php //if (isset($user)) { echo $user->password; } ?>">
		</div>
		<div class="form-group">
			<label for="input4">Role</label>
			<select class="form-control" id="input4" name="role_id">
				<option value="">Select Role</option>
				<?php foreach ($roles as $role): ?>
				<option value="<?= $role->role_id ?>" <?php echo (isset($user) && $user->role_id == $role->role_id) ? 'selected' : '' ; ?> ><?= $role->label ?></option>
				<?php endforeach; ?>
			</select>
		</div>
		<div class="form-group">
			<label for="input4">Region</label>
			<select class="form-control" id="input4" name="region_id">
				<!-- <option value="">Select Region</option> -->
				<option value="">All Regions</option>
				<?php foreach ($regions as $region): ?>
				<option value="<?= $region->region_id ?>" <?php echo (isset($user) && $user->region_id == $region->region_id) ? 'selected' : '' ; ?> ><?= $region->name ?></option>
				<?php endforeach; ?>
			</select>
		</div>
		<div class="form-group form-check">
			<input type="checkbox" class="form-check-input" id="input5" name="active" value="1" <?php echo (isset($user) && $user->active == 1) ? 'checked' : '' ; ?>>
			<label class="form-check-label" for="input5">Activate User</label>
		</div>
		<button type="submit" class="btn btn-primary">Submit</button>
	</form>


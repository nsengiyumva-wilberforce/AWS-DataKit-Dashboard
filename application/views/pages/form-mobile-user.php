	<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
		<h1 class="h2"><?= $action ?> Mobile User</h1>
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
			<label for="input6">Password</label>
			<input type="password" class="form-control" id="input6" name="password" value="<?php //if (isset($user)) { echo $user->password; } ?>">
		</div>
		<div class="form-group">
			<label for="input4">Region</label>
			<select class="form-control" id="input4" name="region_id">
				<option value="">Select Region</option>
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
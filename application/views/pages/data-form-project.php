	<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
		<h1 class="h2"><?= $action ?> Project</h1>
	</div>
	<!-- <h2>Section title</h2> -->

	<form class="box" action="<?= base_url($form_action) ?>" method="POST">
		<div class="form-group">
			<label for="input1">Name</label>
			<input type="text" class="form-control" name="name" value="<?php if (isset($project)) { echo $project->name; } ?>">
		</div>

		<button type="submit" class="btn btn-primary">Submit</button>
	</form>

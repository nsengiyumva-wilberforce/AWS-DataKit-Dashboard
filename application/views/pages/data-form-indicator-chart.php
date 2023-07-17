	<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
		<h1 class="h2"><?= $action ?> Indicator Chart</h1>
	</div>
	<!-- <h2>Section title</h2> -->

	<form class="box" action="<?= base_url($form_action) ?>" method="POST">
		<div class="form-group">
			<label for="input1">Chart Title</label>
			<input type="text" class="form-control" name="title" value="<?php if (isset($chart)) { echo $chart->title; } ?>">
		</div>

		<div class="form-group">
			<label>Form</label>
            <select class="form-control" name="form_id" id="select-indicator-form">
            <option></option>
            <?php foreach($forms as $form): ?>
            <option value="<?= $form->form_id ?>" <?php if (isset($chart) && $form->form_id == $chart->form_id) { echo 'selected'; } ?>><?= $form->title ?></option>
            <?php endforeach; ?>
            </select>


		</div>

		<div class="form-group">
			<label>Indicator</label>
            <select class="form-control" name="question_id" id="select-question">
                <?php if (isset($chart)): ?>
                <option></option>
                <?php foreach($question_list as $qn): ?>
                <option value="<?= $qn->question_id ?>" <?php if ($qn->question_id == $chart->question_id) { echo 'selected'; } ?>><?= $qn->question ?></option>
                <?php endforeach; ?>
                <?php endif; ?>
            </select>
		</div>

		<!-- <div class="form-group">
			<label for="">Units</label>
			<input type="text" class="form-control" name="unit" value="<?php //if (isset($chart)) { echo $chart->unit; } ?>">
		</div> -->

		<!-- <div class="form-group">
			<label for="">Target Value</label>
			<input type="number" class="form-control" name="target" value="<?php //if (isset($chart)) { echo $chart->target; } ?>">
		</div> -->

		<div class="form-group">
			<label for="">Date Range</label>
			<input type="text" class="form-control" name="dates" value="<?php if (isset($chart)) { echo date('m/d/Y', strtotime($chart->start_date)).' - '.date('m/d/Y', strtotime($chart->end_date)); } ?>">
		</div>



		<button type="submit" class="btn btn-primary">Submit</button>
	</form>




<!-- `chart_id`, `title`, `target`, `unit`, `start_date`, `end_date`, `form_list`, `date_created`, `active` -->
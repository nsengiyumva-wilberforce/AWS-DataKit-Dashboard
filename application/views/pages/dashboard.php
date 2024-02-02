<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
	<h1 class="h2">Dashboard</h1>
	<div class="btn-toolbar mb-2 mb-md-0">
		<!-- <div class="btn-group mr-2">
				<button type="button" class="btn btn-sm btn-outline-secondary">Share</button>
				<button type="button" class="btn btn-sm btn-outline-secondary">Export</button>
			</div> -->
		<?php if ($this->session->permissions->create_form): ?>
			<a href="<?= base_url('add-chart') ?>" class="btn btn-sm btn-outline-secondary">
				<i data-feather="plus"></i>
				Add Chart
			</a>
		<?php endif; ?>
	</div>
</div>


<div class="row">
	<br />
	<div class="col text-center">
		<!-- <h2>Overview</h2> -->
		<!-- <p>counter to count up to a target number</p> -->
	</div>
</div>


<div class="row text-center">
	<div class="col">
		<a href="<?= base_url('forms') ?>" class="counter d-block">
			<i class="fas fa-file-alt fa-2x"></i>
			<h2 class="timer count-title count-number" data-to="<?= $counter->forms ?>" data-speed="1500"></h2>
			<p class="count-text ">Forms</p>
		</a>
	</div>
	<div class="col">
		<a href="<?= base_url('entries') ?>" class="counter d-block">
			<i class="fa fa-inbox fa-2x"></i>
			<h2 class="timer count-title count-number" data-to="<?= $counter->entries ?>" data-speed="1500"></h2>
			<p class="count-text ">Entries</p>
		</a>
	</div>
	<div class="col">
		<a href="<?= base_url('mobile-users') ?>" class="counter d-block">
			<i class="fa fa-users fa-2x"></i>
			<h2 class="timer count-title count-number" data-to="<?= $counter->mobile_users ?>" data-speed="1500"></h2>
			<p class="count-text ">Mobile Users</p>
		</a>
	</div>
	<div class="col">
		<a href="<?= base_url('projects') ?>" class="counter d-block">
			<i class="fas fa-project-diagram fa-2x"></i>
			<h2 class="timer count-title count-number" data-to="<?= $counter->projects ?>" data-speed="1500"></h2>
			<p class="count-text ">Projects</p>
		</a>
	</div>
</div>


<div class="row mt-5">
	<?php foreach ($charts as $chart): ?>
		<div class="col-3 mb-5 chart-wrapper">
			<figure class="highcharts-figure">
				<div id="container<?= $chart->chart_id ?>"></div>
			</figure>
			<p>Target:
				<?= number_format($chart->target) . ' ' . $chart->unit ?><br> Actual:
				<?= number_format($chart->actual) . ' ' . $chart->unit ?>
			</p>
			<div>
				<?php if ($this->session->permissions->create_form): ?>
					<nav class="nav d-inline-flex">
						<a class="nav-link py-0" data-toggle="Edit" title="Edit"
							href="<?= base_url('chart/' . $chart->chart_id . '/edit') ?>"><i data-feather="edit-2"></i></a>
						<a class="nav-link py-0 confirm-chart-delete" data-toggle="Delete" title="Delete"
							href="<?= base_url('chart/' . $chart->chart_id . '/delete') ?>"><i data-feather="trash"></i></a>
					</nav>
				<?php endif; ?>
			</div>
		</div>
	<?php endforeach; ?>
</div>

<?php if ($this->session->permissions->create_form): ?>
	<div class="row mt-3 mb-5">
		<div class="col">
			<div class="meter-widget mb-2">
				Root Storage
				<div class="meter-wrapper">
					<div class="meter" style="width: <?= $storage['_dev_vda1']['Use%'] ?>;"></div>
				</div>
				<small>
					<?= $storage['_dev_vda1']['Used'] ?>B of
					<?= $storage['_dev_vda1']['Size'] ?>B used
				</small>
			</div>
		</div>
		<div class="col">
			<div class="meter-widget">
				Uploads Storage
				<div class="meter-wrapper">
					<div class="meter" style="width: <?= $storage['udev']['Use%'] ?>;"></div>
				</div>
				<small>
					<?= $storage['udev']['Used'] ?>B of
					<?= $storage['udev']['Size'] ?>B used
				</small>
			</div>
		</div>
	</div>
<?php endif; ?>
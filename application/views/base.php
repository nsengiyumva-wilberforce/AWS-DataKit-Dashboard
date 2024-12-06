<!DOCTYPE html>
<html lang="en">

<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="">
	<meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
	<meta name="generator" content="Jekyll v3.8.5">
	<!-- <title>Survey King</title> -->
	<title>AWS</title>

	<!-- Bootstrap core CSS -->
	<link rel="stylesheet" href="<?= base_url('assets/vendors/Bootstrap/bootstrap.css') ?>">
	<link rel="stylesheet" type="text/css"
		href="https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.10.18/b-1.5.6/b-html5-1.5.6/fc-3.2.5/fh-3.1.4/sc-2.0.0/datatables.min.css" />
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
	<link rel="stylesheet" href="https://unpkg.com/leaflet@1.5.1/dist/leaflet.css"
		integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ=="
		crossorigin="" />
	<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.css">
	<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.Default.css">


	<link
		href="<?= base_url('assets/vendors/Tokenfield/sliptree-bootstrap-tokenfield-9c06df4/dist/css/bootstrap-tokenfield.min.css') ?>"
		rel="stylesheet">
	<link
		href="<?= base_url('assets/vendors/Tokenfield/sliptree-bootstrap-tokenfield-9c06df4/dist/css/tokenfield-typeahead.min.css') ?>"
		rel="stylesheet">

	<!-- <link rel="stylesheet" href="https://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css"> -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.min.css"
		integrity="sha256-+N4/V/SbAFiW1MPBCXnfnP9QSN3+Keu+NlB+0ev/YKQ=" crossorigin="anonymous" />

	<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

	<!-- Custom styles for this template -->
	<link href="<?= base_url('assets/vendors/Bootstrap//dashboard.css') ?>" rel="stylesheet">
	<link href="<?= base_url('assets/css/custom.css') ?>" rel="stylesheet">

</head>

<body>
	<nav class="navbar navbar-dark fixed-top bg-dark flex-md-nowrap p-0 shadow">
		<a class="navbar-brand col-sm-3 col-md-2 mr-0" href="<?= base_url() ?>">AWS</a>
		<!-- <input class="form-control form-control-dark w-100" type="text" placeholder="Search" aria-label="Search"> -->
		<ul class="navbar-nav px-3">
			<li class="nav-item text-nowrap">
				<a class="nav-link" href="<?= base_url('logout') ?>">Sign out</a>
			</li>
		</ul>
	</nav>

	<div class="container-fluid">
		<div class="row">
			<nav class="col-md-2 d-none d-md-block bg-light sidebar">
				<div class="sidebar-sticky">
					<ul class="nav flex-column">
						<li class="nav-item">
							<a class="nav-link <?= $page_name == 'dashboard' ? 'active' : '' ?>"
								href="<?= base_url() ?>">
								<i data-feather="home"></i>
								Dashboard
							</a>
						</li>
						<?php if ($this->session->permissions->view_form): ?>
							<li class="nav-item">
								<a class="nav-link <?= $page_name == 'forms' ? 'active' : '' ?>"
									href="<?= base_url('forms') ?>">
									<i data-feather="file-text"></i>
									Forms
								</a>
							</li>
						<?php endif; ?>
						<?php if ($this->session->permissions->view_response): ?>
							<li class="nav-item">
								<a class="nav-link <?= $page_name == 'entries' || $page_name == 'form-entries' ? 'active' : '' ?>"
									href="<?= base_url('entries') ?>">
									<i data-feather="inbox"></i>
									Entries
								</a>
							</li>
						<?php endif; ?>
						<?php if ($this->session->permissions->view_reports): ?>
							<li class="nav-item">
								<a class="nav-link <?= $page_name == 'reports' ? 'active' : '' ?>"
									href="<?= base_url('reports') ?>">
									<i data-feather="file-text"></i>
									Reports
								</a>
							</li>
						<?php endif; ?>
						<li class="nav-item">
							<a class="nav-link <?= $page_name == 'insights' ? 'active' : '' ?>"
								href="<?= base_url('insights') ?>">
								<i data-feather="eye"></i>
								Insights
							</a>
						</li>
						<li class="nav-item">
							<a class="nav-link <?= $page_name == 'maps' ? 'active' : '' ?>"
								href="<?= base_url('maps') ?>">
								<i data-feather="map"></i>
								Maps
							</a>
						</li>
					</ul>

					<?php if ($this->session->permissions->view_form): ?>
						<h6
							class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
							<span>Settings</span>
						</h6>

						<ul class="nav flex-column mb-2">
							<li class="nav-item">
								<a class="nav-link <?= $page_name == 'regions' ? 'active' : '' ?>"
									href="<?= base_url('regions') ?>">
									<i data-feather="settings"></i>
									Regions
								</a>
							</li>
							<li class="nav-item">
								<a class="nav-link <?= $page_name == 'districts' ? 'active' : '' ?>"
									href="<?= base_url('districts') ?>">
									<i data-feather="settings"></i>
									Districts
								</a>
							</li>
							<li class="nav-item">
								<a class="nav-link <?= $page_name == 'sub-counties' ? 'active' : '' ?>"
									href="<?= base_url('sub-counties') ?>">
									<i data-feather="settings"></i>
									Sub Counties
								</a>
							</li>
							<li class="nav-item">
								<a class="nav-link <?= $page_name == 'parishes' ? 'active' : '' ?>"
									href="<?= base_url('parishes') ?>">
									<i data-feather="settings"></i>
									Parishes
								</a>
							</li>
							<li class="nav-item">
								<a class="nav-link <?= $page_name == 'villages' ? 'active' : '' ?>"
									href="<?= base_url('villages') ?>">
									<i data-feather="settings"></i>
									Villages
								</a>
							</li>
							<li class="nav-item">
								<a class="nav-link <?= $page_name == 'projects' ? 'active' : '' ?>"
									href="<?= base_url('projects') ?>">
									<i data-feather="settings"></i>
									Projects
								</a>
							</li>
							<li class="nav-item">
								<a class="nav-link <?= $page_name == 'organisations' ? 'active' : '' ?>"
									href="<?= base_url('organisations') ?>">
									<i data-feather="settings"></i>
									Organisations
								</a>
							</li>
							<li class="nav-item">
								<a class="nav-link <?= $page_name == 'question-library' ? 'active' : '' ?>"
									href="<?= base_url('question-library') ?>">
									<i data-feather="settings"></i>
									Question Library
								</a>
							</li>
						</ul>
					<?php endif; ?>


					<h6
						class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
						<span>Settings</span>
					</h6>

					<ul class="nav flex-column mb-2">
						<?php if ($this->session->permissions->view_user): ?>
							<li class="nav-item">
								<a class="nav-link <?= $page_name == 'mobile-users' ? 'active' : '' ?>"
									href="<?= base_url('mobile-users') ?>">
									<i data-feather="users"></i>
									Mobile App Users
								</a>
							</li>
						<?php endif; ?>
						<?php if ($this->session->permissions->view_admins): ?>
							<li class="nav-item">
								<a class="nav-link <?= $page_name == 'dashboard-users' ? 'active' : '' ?>"
									href="<?= base_url('dashboard-users') ?>">
									<i data-feather="monitor"></i>
									Dashboard Users
								</a>
							</li>
						<?php endif; ?>
						<li class="nav-item">
							<a class="nav-link <?= $page_name == 'settings' ? 'active' : '' ?>"
								href="<?= base_url('settings') ?>">
								<i data-feather="settings"></i>
								Account Settings
							</a>
						</li>
					</ul>
				</div>
			</nav>

			<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
				<?php // print_r($this->session) ?>
				<?php //$this->load->view($page); ?>
				<?php $this->load->view($page); ?>
			</main>

			<!-- Modal -->
			<div class="modal fade" id="dynamic-modal" tabindex="-1" role="dialog" aria-labelledby="dynamicModalLabel"
				aria-hidden="true">
				<div class="<?= $modal_size ?? 'modal-lg' ?> modal-dialog" role="document">
					<div class="modal-content">
						Loading Content...
					</div>
				</div>
			</div>



		</div>

		<script src="https://cdn.jsdelivr.net/npm/jquery@3.2.1/dist/jquery.min.js"></script>
		<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

		<script type="text/javascript"
			src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>

		<script src="<?= base_url('assets/vendors/Bootstrap/bootstrap.js') ?>"
			integrity="sha384-xrRywqdh3PHs8keKZN+8zzc5TX0GRTLCcmivcbNJWm2rs5C8PRhcEn3czEjhAO9o"
			crossorigin="anonymous"></script>
		<!-- <script src="<?= base_url('assets/vendors/Bootstrap/feather.js') ?>"></script> -->
		<script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
		<!-- <script src="<?= base_url('assets/vendors/Bootstrap/dashboard.js') ?>"></script> -->

		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.2.2/jquery.form.min.js"
			integrity="sha384-FzT3vTVGXqf7wRfy8k4BiyzvbNfeYjK+frTVqZeNDFl8woCbF0CYG6g2fMEFFo/i"
			crossorigin="anonymous"></script>

		<!-- <script type="text/javascript" src="https://unpkg.com/leaflet@1.0.3/dist/leaflet.js"></script> -->
		<!-- <script type="text/javascript" src="https://unpkg.com/leaflet@1.5.1/dist/leaflet.js"></script> -->
		<script src="https://unpkg.com/leaflet@1.5.1/dist/leaflet.js"
			integrity="sha512-GffPMF3RvMeYyc1LWMHtK8EbPv0iNZ8/oTtHPx9/cc2ILxQ+u905qIwdpULaqDkyBKgOaB57QTMg7ztg8Jm2Og=="
			crossorigin=""></script>
		<script src="https://unpkg.com/leaflet.markercluster@1.4.1/dist/leaflet.markercluster.js"></script>

		<script src="<?= base_url('assets/vendors/Bootstrap/dashboard-counter.js') ?>"></script>


		<script type="text/javascript"
			src="https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.10.18/b-1.5.6/b-html5-1.5.6/fc-3.2.5/fh-3.1.4/sc-2.0.0/datatables.min.js"></script>

		<script
			src="<?= base_url('assets/vendors/Tokenfield/sliptree-bootstrap-tokenfield-9c06df4/dist/bootstrap-tokenfield.min.js') ?>"></script>

		<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>




		<script type="text/javascript">
			var base_url = "<?= base_url('index.php/ajax/') ?>";
			// var api_base_url = 'http://127.0.0.1/aws-api/index.php/app/';
			// var api_base_url = 'http://116.203.142.9/aws-api/index.php/app/';
			// var api_base_url = 'http://127.0.0.1/aws.api/public/';
			var api_base_url = '<?= API_BASE_URL ?>';
		</script>

		<?php if ($page_name == 'dashboard'): ?>
			<script src="https://code.highcharts.com/highcharts.js"></script>

			<?php foreach ($charts as $chart): ?>
				<script>
					// Build the chart
					Highcharts.chart('container<?= $chart->chart_id ?>', {
						chart: {
							type: 'column'
						},
						title: {
							text: '<?= $chart->title ?>',
							style: {
								fontSize: '20px',
								fontWeight: 'bold',
								fontFamily: 'Segoe UI, sans-serif'
							}
						},
						xAxis: {
							categories: ['Actual', 'Target']
						},
						yAxis: {
							min: 0,
							title: {
								text: 'Households'
							}
						},
						legend: {
							shadow: false
						},
						tooltip: {
							shared: true
						},
						plotOptions: {
							column: {
								grouping: false,
								shadow: false,
								borderWidth: 0
							}
						},
						series: [{
							name: '<?= $chart->unit ?>',
							colorByPoint: true,
							innerSize: '50%',
							data: [{
								name: 'Actual',
								y: <?= $chart->actual ?>
							}, {
								name: 'Target',
								y: <?= $chart->target ?>
							}
							]
						}]
					});
				</script>
			<?php endforeach; ?>
		<?php endif; ?>

		<?php if ($page_name == 'insights'): ?>
			<script src="https://code.highcharts.com/highcharts.js"></script>
			<script src="https://code.highcharts.com/highcharts-more.js"></script>
			<script src="https://code.highcharts.com/modules/exporting.js"></script>
			<script src="https://code.highcharts.com/modules/accessibility.js"></script>
			<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
			<script type="text/javascript"
				src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
			<script>
				$(document).ready(function () {
					$('input[name="dates"]').daterangepicker({
						opens: 'left',
						autoApply: true,
						startDate: '12/01/2023',
						endDate: '04/30/2024'
					});
				});
			</script>
			<script>
				Highcharts.chart('bubblechart-container', {
					chart: {
						type: 'packedbubble',
						height: '70%'
					},
					title: {
						text: 'Monitoring per District',
						align: 'left'
					},
					tooltip: {
						useHTML: true,
						pointFormat: '<b>{point.name}:</b><br><b>Baseline:{point.value}HH</b><br><b>Monitoring: {point.followup}HH</b>'
					},
					plotOptions: {
						packedbubble: {
							minSize: '10%',
							maxSize: '100%',
							zMin: 10,
							zMax: 2000,
							layoutAlgorithm: {
								splitSeries: false,
								gravitationalConstant: 0.02
							},
							dataLabels: {
								enabled: true,
								format: '{point.name}',
								filter: {
									property: 'y',
									operator: '>',
									value: 250
								},
								style: {
									color: 'black',
									textOutline: 'none',
									fontWeight: 'normal',
									fontSize: '20px', // Adjust the font size as needed
								}
							}
						}
					},
					series: <?= $region_and_district ?>
				});

				Highcharts.chart('barchart-container-region', {
					chart: {
						renderTo: 'barchart-container-region',
						type: 'column',
						options3d: {
							enabled: true,
							alpha: 15,
							beta: 15,
							depth: 50,
							viewDistance: 25
						}
					},
					title: {
						text: 'Regional Baseline Vs Monitoring for 2024',
						align: 'left'
					},
					xAxis: {
						categories: [
							'Central',
							'Eastern',
							'South Western',
							'West Nile'
						],
						crosshair: true,
						accessibility: {
							description: 'Regions'
						}
					},
					yAxis: {
						min: 0,
						title: {
							text: 'House Holds (1000)'
						}
					},
					// tooltip: {
					// 	valueSuffix: ' (1000 MT)'
					// },
					plotOptions: {
						column: {
							pointPadding: 0.2,
							borderWidth: 0
						}
					},
					series: [
						{
							name: 'Baseline',
							data: <?= $baseline_region ?>
						},
						{
							name: 'Monitoring',
							data: <?= $followup_region ?>
						}
					]
				});

				Highcharts.chart('barchart-container-latrine-coverage', {
					chart: {
						type: 'column',
					},
					title: {
						text: 'latrine Coverage',
						align: 'left'
					},
					xAxis: {
						categories: [
							'Yes',
							'No (uses field)',
							'No (shares a latrine)',
							'null'
						],
						crosshair: true,
						accessibility: {
							description: 'Regions'
						}
					},
					yAxis: {
						min: 0,
						title: {
							text: 'House Holds (1000)'
						}
					},
					tooltip: {
						valueSuffix: ' (1000 MT)'
					},
					plotOptions: {
						column: {
							pointPadding: 0.2,
							borderWidth: 0
						}
					},
					series: [
						{
							name: 'Baseline',
							data: <?= $baseline_latrine_coverage ?>
						},
						{
							name: 'Monitoring',
							data: <?= $followup_latrine_coverage ?>
						}
					]
				});

				Highcharts.chart('barchart-container-sanitation-category', {
					chart: {
						type: 'bar'
					},
					title: {
						text: 'Sanitation Categories',
						align: 'left'
					},
					xAxis: {
						categories: [
							'un-recommendable facility',
							'Shares latrine',
							'Recommendable facility',
							'Open defecation (Field)',
							'null'
						],
						crosshair: true,
						accessibility: {
							description: 'Regions'
						}
					},
					yAxis: {
						min: 0,
						title: {
							text: 'House Holds (1000)'
						}
					},
					tooltip: {
						valueSuffix: ' (1000 MT)'
					},
					plotOptions: {
						column: {
							pointPadding: 0.2,
							borderWidth: 0
						}
					},
					series: [
						{
							name: 'Baseline',
							data: <?= $baseline_sanitation_category ?>
						},
						{
							name: 'Monitoring',
							data: <?= $followup_sanitation_category ?>
						}
					]
				});

				Highcharts.chart('barchart-duration-of-water-collection', {
					chart: {
						type: 'column'
					},
					title: {
						text: 'Duration of Water Collection',
						align: 'left'
					},
					xAxis: {
						categories: [
							'1 hour',
							'<10 minutes',
							'31-60 minutes',
							'10-30 minutes',
							'null'
						],
						crosshair: true,
						accessibility: {
							description: 'Regions'
						}
					},
					yAxis: {
						min: 0,
						title: {
							text: 'House Holds (1000)'
						}
					},
					tooltip: {
						valueSuffix: ' (1000 MT)'
					},
					plotOptions: {
						column: {
							pointPadding: 0.2,
							borderWidth: 0
						}
					},
					series: [
						{
							name: 'Baseline',
							data: <?= $baseline_water_collection ?>
						},
						{
							name: 'Monitoring',
							data: <?= $followup_water_collection ?>
						}
					]
				});

				Highcharts.chart('barchart-water-treatment', {
					chart: {
						type: 'column'
					},
					title: {
						text: 'Drinking Water Treatment',
						align: 'left'
					},
					xAxis: {
						categories: [
							'SODIS',
							'None',
							'Filter',
							'Chlorination',
							'Boiling',
							'null'
						],
						crosshair: true,
						accessibility: {
							description: 'Regions'
						}
					},
					yAxis: {
						min: 0,
						title: {
							text: 'House Holds (1000)'
						}
					},
					tooltip: {
						valueSuffix: ' (1000 MT)'
					},
					plotOptions: {
						column: {
							pointPadding: 0.2,
							borderWidth: 0
						}
					},
					series: [
						{
							name: 'Baseline',
							data: <?= $baseline_water_treatment ?>
						},
						{
							name: 'Monitoring',
							data: <?= $followup_water_treatment ?>
						}
					]
				});

				Highcharts.chart('barchart-family-savings', {
					chart: {
						type: 'column'
					},
					title: {
						text: 'Family Savings',
						align: 'left'
					},
					xAxis: {
						categories: [
							'Nothing',
							'More than 6$(>22,000)',
							'More than 1$- 3$ (3600 -10,900)',
							'3$ - 6$ (11,000 - 22,000)',
							'1 dollar and less (3600 & less)',
							'null'
						],
						crosshair: true,
						accessibility: {
							description: 'Regions'
						}
					},
					yAxis: {
						min: 0,
						title: {
							text: 'House Holds (1000)'
						}
					},
					tooltip: {
						valueSuffix: ' (1000 MT)'
					},
					plotOptions: {
						column: {
							pointPadding: 0.2,
							borderWidth: 0
						}
					},
					series: [
						{
							name: 'Baseline',
							data: <?= $baseline_family_savings ?>
						},
						{
							name: 'Monitoring',
							data: <?= $followup_family_savings ?>
						}
					]
				});
				$(document).on('submit', '#form-insights', function (event) {
					event.preventDefault();
					$('#loader').show();
					$(this).ajaxSubmit({
						success: function (response) {
							$('#loader').hide();
							$('#graphs').html(response);
						}
					})

					return false
				})
			</script>
		<?php endif; ?>

		<?php if ($page_name == 'add-chart' || $page_name == 'edit-chart'): ?>
			<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
			<script type="text/javascript"
				src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
		<?php endif; ?>



		<?php if ($page_name == 'add-chart' || $page_name == 'edit-chart'): ?>
			<script type="text/javascript">
				$(document).ready(function () {

					$('input[name="dates"]').daterangepicker({
						// opens: 'left',
						autoApply: true
						// maxDate: '<?= date('') ?>'
						// opens: 'left',
						// locale: { format: 'DD/MM/YYYY' }
					});


					$('#tokenfield-forms').tokenfield({
						autocomplete: {
							source: <?= json_encode($forms) ?>,
							delay: 100
						},
						showAutocompleteOnFocus: true,
						createTokensOnBlur: true,
						delimiter: [',', ' ']
					});

				});
			</script>
		<?php endif; ?>

		<?php if ($page_name == 'entry'): ?>
			<script type="text/javascript">
				//reject entry on clicking button with id reject-entry
				$(document).on('click', '#reject-entry', function (e) {
					e.preventDefault();
					let entry_id = $(this).attr('data-entry-id');
					let index = 0;
					let url = api_base_url + 'entry/reject-entry';
					console.log(url);
					$.ajax({
						url: url,
						type: 'POST',
						dataType: 'json',
						data: {
							'response_id': entry_id,
							'index': index
						},
						success: function (response) {
							if (response.status == 201) {
								//show an alert that entry has been rejected
								alert('Entry has been rejected');
								$('#reject-entry').hide();

								console.log(response);
							} else {
								console.log(response);
							}
						},
						error: function (response) {
							alert('Error rejecting entry');
						}
					});

				});
			</script>
		<?php endif; ?>



		<?php if ($page_name == 'edit-chart'): ?>
			<script type="text/javascript">
				$(document).ready(function () {

					// $('input[name="dates"]').daterangepicker({
					// 	// opens: 'left',
					// 	autoApply: true
					// 	// maxDate: '<?= date('') ?>'
					// 	// opens: 'left',
					// 	// locale: { format: 'DD/MM/YYYY' }
					// });

					$('input[name="dates"]').daterangepicker({
						autoApply: true,
						startDate: '<?= date('m/d/Y', strtotime($chart->start_date)) ?>',
						endDate: '<?= date('m/d/Y', strtotime($chart->end_date)) ?>'
					});


					$('#tokenfield-forms').tokenfield({
						autocomplete: {
							source: <?= json_encode($forms) ?>,
							delay: 100
						},
						showAutocompleteOnFocus: true,
						createTokensOnBlur: true,
						delimiter: [',', ' ']
					});

					$('#tokenfield-forms').tokenfield('setTokens', <?= json_encode($selected_forms) ?>);
				});
			</script>
		<?php endif; ?>





		<?php if ($page_name == 'form-entries'): ?>
			<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
			<script type="text/javascript"
				src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

			<script>
				$(document).ready(function () {
					var currentDate = new Date();

					// Get the first of December in the previous year
					var firstDecemberLastYear = new Date(currentDate.getFullYear() - 1, 11, 1);  // Month is 0-based (11 = December)

					// Get the last day of November in the current year
					var lastNovemberThisYear = new Date(currentDate.getFullYear(), 10, 30);  // Month is 0-based (10 = November)
					$('input[name="dates"]').daterangepicker({
						opens: 'left',
						autoApply: true,
						startDate: firstDecemberLastYear,
						endDate: lastNovemberThisYear,
					});
				});
			</script>
		<?php endif; ?>








		<?php if ($page_name == 'report'): ?>
			<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
			<script type="text/javascript"
				src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

			<script>
				$(document).ready(function () {
					$('input[name="dates"]').daterangepicker({
						opens: 'left',
						autoApply: true
						// maxDate: '<?= date('') ?>'
						// opens: 'left',
						// locale: { format: 'DD/MM/YYYY' }
					});

					feather.replace();

					$('#data-table').DataTable({
						fixedHeader: true,
						scrollX: true,
						scrollCollapse: true,
						fixedColumns: true,
						// dom: 'lfBrtip',
						dom: "<'row'<'col-sm-12 col-md-6'B>><'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>rt<'row'<'col-sm-12 col-md-6'i><'col-sm-12 col-md-6'p>>",
						buttons: ['copy', 'excel', 'pdf']
					});

				});
			</script>
		<?php endif; ?>



		<script type="text/javascript">
			$(document).ready(function () {

				feather.replace();
				$('[data-toggle="tooltip"]').tooltip();

				$("#sortable").sortable();
				$("#sortable").disableSelection();


				attach_sort();

				$('#datatable').DataTable();
				//$('#datatable-entries').DataTable();
				$('#datatable-entries').DataTable({
					"order": [[4, "desc"]],
					//add export buttons
					dom: "<'row'<'col-sm-12 col-md-6'B>><'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>rt<'row'<'col-sm-12 col-md-6'i><'col-sm-12 col-md-6'p>>",
					buttons: ['copy', 'excel', 'pdf']
				});
				$('#filter-entries').on('submit', function (e) {
					console.log('Form submitted');
					e.preventDefault(); // Prevent the default form submission

					// Trigger DataTable reload with the new filters
					$('#dt-entries').DataTable().ajax.reload();
				});
				$('#dt-entries').DataTable({
					"serverSide": true,
					"processing": true,
					"ajax": {
						"url": "https://dashboard.africawatersolutions.org/aws.api/public/entry/getRegionalEntries", // Update this URL as needed
						"data": function (d) {
							// Include necessary parameters (region_id, year, form_id)
							d.dates = $('input[name="dates"]').val();
							d.form_id = $('input[name="form_id"]').val();
							d.region_id = $('#region_id').val();
						},
						"type": "GET",
						"dataSrc": function (json) {
							// Ensure the server returns the correct data format (data and recordsFiltered)
							return json.data; // json.data should be the list of entries
						}
					},
					"order": [[4, "desc"]], // Order by "Last Modified" column (index 4)
					// Add export buttons
					dom: "<'row'<'col-sm-12 col-md-6'B>><'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>rt<'row'<'col-sm-12 col-md-6'i><'col-sm-12 col-md-6'p>>",
					buttons: ['copy', 'excel', 'pdf'],
					columns: [
						{ data: 'title' },
						{ data: 'location' },
						{ data: 'creator_id' },
						{ data: 'last_follower' },
						{ data: 'updated_at' },
						{ data: 'actions' }
					],
					"columnDefs": [
						{
							"targets": 0, // Title column
							"render": function (data, type, row) {
								// Format the "Title" column with a link to the entry page
								return `<a href="<?= base_url('entry/') ?>${row.response_id}">${data}</a>`;
							}
						},
						{
							"targets": 1, // location column
							"render": function (data, type, row) {
								// Format the "location" column with a link to the entry page
								return `${row.location}`;
							}
						},

						{
							"targets": 3, // Last Modified column
							"render": function (data, type, row) {
								// Format the "Last Modified" column with the date
								return `${row.last_follower}`;
							}
						},
						{
							"targets": 4, // updated_at column modification
							"render": function (data, type, row) {
								// Ensure the data is a valid date string
								let date = new Date(row.updated_at);

								// Check if the date is valid
								if (!isNaN(date.getTime())) {
									// Format the date as "Jan 20, 2024"
									let formattedDate = date.toLocaleDateString('en-US', {
										year: 'numeric',
										month: 'short',
										day: 'numeric'
									});

									return `<span title="Last modified on ${formattedDate}">${formattedDate}</span>`;
								}

								return data; // Return the original value if the date is invalid
							}

						},

						{
							"targets": 5, // Actions column
							"render": function (data, type, row) {
								let can_delete = '<?= $can_delete??0 ?>';
								console.log(can_delete);
								// Dynamically generate action buttons for each row
								let buttonsHtml = '<nav class="nav d-inline-flex">';
								let viewUrl = '<?= base_url('entry/') ?>' + row.response_id;
								let deleteUrl = '<?= base_url('entry/') ?>' + row.response_id + '/delete';

								// View button
								buttonsHtml += `<a class="nav-link py-0 btn-info" data-toggle="View" title="View" href="${viewUrl}"><i data-feather="eye">view</i></a>`;
								if (can_delete==1) {

									// Check if the user has permission to delete and generate the Delete button if allowed
									buttonsHtml += ` <a class="nav-link py-0 confirm-tr-delete btn-danger" data-toggle="Delete" title="Delete" href="${deleteUrl}"><i data-feather="trash"></i>delete</a>`;
								}
								buttonsHtml += '</nav>';
								return buttonsHtml; // Return the generated HTML
							}
						}
					]
				});

				// New Question
				$(document).on('click', '#new-question', function (e) {
					e.preventDefault();
					let form_id = $(this).attr('data-form-id');
					let url = '<?= base_url("ajax/new-question/") ?>' + form_id;
					$.get(url, function (response) {
						$('#form-builder').append(response);
						// Initialize for ajax submit
						$('#form-builder-question').ajaxForm();
					});
				});


				$(document).on('click', '#new-question-from-library', function (e) {
					e.preventDefault();
					let url = $(this).attr('href');
					$.get(url, function (response) {
						$('#form-builder').append(response);
						// Initialize for ajax submit
						$('#form-builder-question').ajaxForm();
					});
				});







				$(document).on('click', '.tab-link', function (e) {
					e.preventDefault();

					let selected_tab = $(this).attr('href');
					$('.tab-link').removeClass('active');
					$(this).addClass('active');

					$('.tab').removeClass('active');
					$(selected_tab).addClass('active');
				});

				// Edit Question
				$(document).on('click', '.edit-question', function (e) {
					e.preventDefault();
					let el = $(this).parents('.question-card');
					let question_id = $(this).attr('data-question-id');
					let form_id = $(this).attr('data-form-id');
					let url = '<?= base_url("ajax/edit-question-form/") ?>' + question_id + '/' + form_id;
					// console.log(url);
					$.get(url, function (response) {
						// $('#form-builder').append(response);
						$(response).insertAfter(el);
						$(el).hide();
						// Initialize for ajax submit
						$('#form-builder-question').ajaxForm();
						feather.replace();
					});
				});



				// Remove Question Form
				$(document).on('click', '#remove-question-form', function (e) {
					e.preventDefault();
					$('#form-builder-question').remove();
					$('.question-card').show();
				});


				// Remove Question Form
				$(document).on('click', '#remove-logic-to-question-form', function (e) {
					e.preventDefault();
					let target_element = $(this).attr('data-target-element');
					$('#' + target_element).remove();
				});

				// Question Answers
				$(document).on('change', '#answer-type', function (e) {
					e.preventDefault();
					let answer_type = $('option:selected', this).attr('data-name');
					let answer_type_id = $(this).val();
					if (answer_type == 'radio' || answer_type == 'checkbox') {
						let url = '<?= base_url("ajax/answer-values") ?>';
						$.get(url, function (response) {
							$('#answer-values-wrapper').html(response);
							feather.replace();
						});
					} else if (answer_type == 'app_list') {
						let url = '<?= base_url("ajax/app-list") ?>';
						console.log(url);
						$.get(url, function (app_list_data) {
							console.log(app_list_data);
							$('#answer-values-wrapper').html(app_list_data);
						});
					} else {
						$('#answer-values-wrapper').html('');
					}
				});

				// Add Question Answer
				$(document).on('click', '#add-answer-value', function (e) {
					e.preventDefault();
					let el = $(this).attr('data-dom');
					$('#answer-values').append(el);
					feather.replace();
				});

				// Remove Question Answer
				$(document).on('click', '.remove-answer-value', function (e) {
					e.preventDefault();
					let el = $(this).parents('.form-group').remove();
				});


				// Submit created question
				// $(document).unbind('submit').on('submit', '#form-builder-question', function(event) {			
				// 	$(this).ajaxSubmit({
				// 		success: function(response) {
				// 			console.log(response);
				// 			$('#form-builder-question').remove();
				// 			$('#form-builder').append(response);
				// 			feather.replace();
				// 			renumber_questions();
				// 			attach_sort();
				// 		}
				// 	});
				// 	event.preventDefault();
				// 	return false;
				// });

				$(document).on('click', '.btn-save-question', function (event) {
					event.preventDefault();
					$('#form-builder-question').ajaxSubmit({
						success: function (response) {
							console.log(response);
							$('#form-builder-question').remove();
							$('#form-builder').append(response);
							feather.replace();
							renumber_questions();
							attach_sort();
						}
					});
					return false;
				});

				$(document).on('click', '.btn-update-question', function (event) {
					event.preventDefault();
					let element = $(this).attr('data-target-element');
					$('#form-builder-question').ajaxSubmit({
						success: function (response) {
							// console.log(response);
							$('#form-builder-question').remove();
							$(element).replaceWith(response);
							feather.replace();
							renumber_questions();
							attach_sort();
						}
					});
					return false;
				});



				// Delete question
				$(document).on('click', '.btn-delete-question', function (e) {
					e.preventDefault();

					var r = confirm("This question will permanently be deleted. Do you want to continue?");
					if (r == true) {
						let url = $(this).attr('href');
						let element = $(this).attr('data-target-element');
						let question_id = $(this).attr('data-question-id');
						let form_id = $(this).parents('#form-builder').attr('data-form-id');

						let post_values = { question_id: question_id };
						if (form_id != undefined) {
							post_values = { form_id: form_id, question_id: question_id };
						}

						$.post(url, post_values, function (data) {
							console.log(data);
							let result = JSON.parse(data);
							if (result.status) {
								$(element).remove();
								renumber_questions();
							} else {
								console.log(result.message);
							}
						});

					} else {
						// alert("You pressed Cancel!");
						console.log('Action cancelled')
					}
				});











				// Delete question
				$(document).on('click', '.delete-question', function (e) {
					e.preventDefault();

					var r = confirm("This question will permanently be deleted. Do you want to continue?");
					if (r == true) {
						let question_id = $(this).attr('data-question-id');
						let form_id = $(this).parents('#form-builder').attr('data-form-id');
						let question_card = $(this).parents('.question-card');
						let api_url = base_url + 'delete-question';
						let post_values = { form_id: form_id, question_id: question_id, format: 'json' };
						$.post(api_url, post_values, function (data) {
							console.log(data);
							let json_data = JSON.parse(data);
							console.log(json_data);
							if (json_data.status) {
								question_card.remove();
								renumber_questions();
							} else {
								console.log(json_data.message);
							}
						});

					} else {
						// alert("You pressed Cancel!");
						console.log('Action cancelled')
					}
				});






				// $(document).on('click', '.ajax-form-link', function(e) {
				// 	e.preventDefault();

				// 	let target = $(this).attr('data-target');
				// 	let action = $(this).attr('data-action');
				// 	let url = $(this).attr('href');

				// 	$.get(url)
				// 	.done(function(response) {

				// 		let element = response;
				// 		if (action == 'add-element') {
				// 			$('#library-question-wrapper').append(element);
				// 		} else if (action == 'remove-element') {
				// 			$('#library-question-wrapper').remove(element);
				// 		} else if (action == 'replace-element') {
				// 			$('#library-question-wrapper').replace(element);
				// 		}

				// 	.fail(function(error) {
				// 		console.log(error);
				// 	});
				// });

				// id="new-library-question" class="btn btn-primary my-4 ajax-form-link" data-target="#library-question-wrapper" data-action="add-element"




				$(document).on('click', '.edit-library-question', function (e) {
					e.preventDefault();
					let el = $(this).parents('.question-card');
					let url = $(this).attr('href');
					$.get(url, function (response) {
						// $('#form-builder').append(response);
						$(response).insertAfter(el);
						$(el).hide();
						// Initialize for ajax submit
						$('#form-builder-question').ajaxForm();
						feather.replace();
					});
				});


				$(document).on('click', '.delete-library-question', function (e) {
					e.preventDefault();
					let question_id = $(this).attr('data-question-id');
					let url = '<?= base_url("ajax/delete-library-question-form/") ?>'.question_id;
					$.get(url)
						.done(function (response) {
							console.log(response);

						})
						.fail(function (error) {
							console.log(error);
						});
				});



				$(document).on('click', '.ajax-link', function (e) {
					e.preventDefault();
					let target = $(this).attr('data-target');
					let action = $(this).attr('data-action');
					let url = $(this).attr('href');
					$.get(url)
						.done(function (response) {
							let element = response;
							if (action == 'add-element') {
								$('#library-question-wrapper').append(element);
							} else if (action == 'remove-element') {
								$('#library-question-wrapper').remove(element);
							} else if (action == 'replace-element') {
								$('#library-question-wrapper').replace(element);
							}
						})
						.fail(function (error) {
							console.log(error);
						});
				});


				// $(document).on('click', '#add-logic-to-question-btn', function(event) {
				// 	// event.preventDefault();
				// 	let element = $(this).attr('data-target-element');
				// 	$('#form-builder-question').ajaxSubmit({
				// 		success: function(response) {
				// 			console.log(response);
				// 			$('#form-builder-question').remove();
				// 			// $(element).replaceWith(response);
				// 			// feather.replace();
				// 			// renumber_questions();
				// 			// attach_sort();
				// 		}
				// 	});
				// 	return false;
				// });


				$(document).on('submit', '.add-logic-to-question-form', function (event) {
					console.log("adding question..........")
					event.preventDefault();

					let element = $(this).attr('id');
					console.log("element:", element)
					let response_element = $(this).attr('data-target-element');
					console.log("response element:", response_element);
					//print form data
					console.log($(this).serialize());
					$(this).ajaxSubmit({
						success: function (response) {
							// console.log(response);
							$('#' + element).remove();
							$(response_element).html(response);
						}
					});
					return false;
				});


				$(document).on('click', '.remove-conditions', function (e) {
					console.log("removing conditions.........")
					e.preventDefault();
					var r = confirm("This condition will permanently be removed. Do you want to continue?");
					if (r == true) {
						let url = $(this).attr('href');
						let element = $(this).find('href');
						//get data-question-element
						let questionId = $(this).attr('data-question-element');
						console.log("question id:", questionId);
						//get data-form-element
						let formId = $(this).attr('data-form-element');
						console.log("form id:", formId);

						//conditional logic from data-logic-element

						let logic =
							$.ajax({
								url: url,
								method: 'POST',
								dataType: 'json', // Expecting JSON response (optional)
								data: {
									question_id: questionId,
									form_id: formId,
								},
								success: function (response) {
									if (response.success) {
										console.log('Conditional logic removed!');

										// Update UI or display success message (optional)
									} else {
										console.error('Error removing logic:', response.message);
										// Handle error response (optional)
									}
								},
								error: function (jqXHR, textStatus, errorThrown) {
									console.error('Error during AJAX request:', textStatus, errorThrown);
									// Handle general errors (optional)
								}
							});
					} else {
						// alert("You pressed Cancel!");
						console.log('Action cancelled');
					}
				});


				// Submit created question
				$(document).on('submit', '#form-row-data-report', function (event) {
					event.preventDefault();
					// alert('gwe');
					$('#loader').show();
					$(this).ajaxSubmit({
						success: function (response) {
							$('#loader').hide();
							$('#ajax-table').html(response);
							feather.replace();

							// Initialize data table
							$('#data-table').DataTable({
								fixedHeader: true,
								scrollX: true,
								scrollCollapse: true,
								fixedColumns: true,
								dom: "<'row'<'col-sm-12 col-md-6'B>><'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>rt<'row'<'col-sm-12 col-md-6'i><'col-sm-12 col-md-6'p>>",
								buttons: ['copy', 'excel', 'pdf']
							});

							$('#datatable-entries').DataTable({
								// order: [4, 'desc'],
								fixedHeader: true,
								// scrollX: true,
								scrollCollapse: true,
								fixedColumns: true,
								 dom: "<'row'<'col-sm-12 col-md-6'B>><'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>rt<'row'<'col-sm-12 col-md-6'i><'col-sm-12 col-md-6'p>>",
								buttons: ['copy', 'excel', 'pdf']
							});
						}
					});
					return false;
				});


				$(document).on('click', '.modal-link', function (e) {
					e.preventDefault();
					// let question_id = $(this).attr('data-question-id');
					let url = $(this).attr('href');
					$.get(url)
						.done(function (response) {
							$('#dynamic-modal').modal('show');
							$('.modal-content').html(response);
							// $('.select2').select2();
						})
						.fail(function (error) {
							console.log(error);
						});
				});


				// $(document).on('click', '.btn-logic', function(e) {
				// 	e.preventDefault();
				// 	// let question_id = $(this).attr('data-question-id');
				// 	let url = $(this).attr('href');
				// 	$.get(url)
				// 	.done(function(response) {
				// 		$('#dynamic-modal').modal('show');
				// 		$('.modal-content').html(response);
				// 		// $('.select2').select2();
				// 		$('#add-logic-to-question-form').ajaxForm();
				// 	})
				// 	.fail(function(error) {
				// 		console.log(error);
				// 	});
				// });


				$(document).on('click', '.btn-logic', function (e) {
					e.preventDefault();
					let el = $(this).parents('.question-card');
					let url = $(this).attr('href');
					$.get(url)
						.done(function (response) {
							$(response).insertAfter(el);
							// $(el).hide();
							$('#add-logic-to-question-form').ajaxForm();
						})
						.fail(function (error) {
							console.log(error);
						});
				});


				$(document).on('change', '.logic-action', function () {
					let action = $(this).val();
					$('.option-qns').removeClass('show');
					$('.' + action + '-option').addClass('show');
				});

				$(document).on('change', 'select[name="prefill_question_ids[]"]', function (e) {
					let question_id = $(this).val();
					let target = $(this).closest('.prefill-pair').find('select[name="prefill_question_answers[]"]');
					let url = api_base_url + 'questions?question_id=' + question_id;
					$.get(url)
						.done(function (response) {
							if (response.status == 200) {
								let list = '<option></option>';
								response.data.answer_values.forEach(value => {
									list += '<option value="' + value + '">' + value + '</option>';
								});
								target.html(list);
							}
						})
						.fail(function (error) {
							console.log(error);
						});
				});

				$(document).on('click', '#add-prefill-question-answer', function (e) {
					e.preventDefault();
					let prefill_pair = $('#default-prefill-pair').clone().removeAttr('id');
					prefill_pair.append('<div class="col-2"><a href="javascript:;" class="btn btn-danger btn-sm remove-prefill-pair">Remove</a></div>');
					prefill_pair.find('label').remove();
					$('.prefill-list-wrapper').append(prefill_pair);
				});


				$(document).on('click', '.remove-prefill-pair', function (e) {
					e.preventDefault();
					$(this).closest('.prefill-pair').remove();
				});




				$(document).on('submit', '#modal-jquery-form', function (event) {
					event.preventDefault();
					$(this).ajaxSubmit({
						success: function (response) {
							// console.log(response);
							$('#dynamic-modal').modal('hide');
							$('#form-builder-question').remove();
							$('#form-builder').append(response);
							feather.replace();
							renumber_questions();
							attach_sort();
						}
					});
					return false;
				});








				// $(document).on('submit', '#modal-jquery-form', function(e) {
				// alert();

				// 	let url = $(this).attr('href');
				// 	$.get(url)
				// 	.done(function(response) {
				// 		$('#dynamic-modal').modal('show');
				// 		$('.modal-content').html(response);
				// 		// $('.select2').select2();
				// 	})
				// 	.fail(function(error) {
				// 		console.log(error);
				// 	});

				// 	return false;
				// });



				$(document).on('change', '#select-indicator-form', function (e) {
					let form_id = $(this).val();
					let url = api_base_url + 'forms?form_id=' + form_id;
					$.get(url)
						.done(function (response) {
							if (response.status == 201) {
								let question_list = response.data.question_list;
								let list = '<option></option>';
								question_list.forEach(value => {
									if (value.answer_type_id == 2 || value.answer_type_id == 3) {
										list += '<option value="' + value.question_id + '">' + value.question + '</option>';
									}
								});
								$('#select-question').html(list);
							}
						})
						.fail(function (error) {
							console.log(error);
						});
				});




				function order_questions() {
					let form_id = $('#form-builder').attr('data-form-id');
					let order = [];

					$('.question-card').each(function () {
						let id = $(this).attr('data-question-id');
						order.push(id);
					});
					let question_list_order = JSON.stringify(order);

					// update 
					let api_url = base_url + 'update-form-question-order';
					let post_values = { form_id: form_id, question_list: question_list_order, format: 'json' };
					$.post(api_url, post_values, function (data) {
						let json_data = JSON.parse(data);
						// console.log(json_data);
						if (json_data.status) {
							console.log('Order has been saved');
						} else {
							console.log(json_data.message);
						}
					});
				}

				function renumber_questions() {
					let number = 1;
					$('.question-card').each(function () {
						$(this).find('.question-number').text(number);
						number++;
					});
				}

				function attach_sort() {
					$("#form-builder").sortable({
						stop: function (event, ui) {
							order_questions();
							renumber_questions();
						}
					});
					$("#form-builder").disableSelection();
				}




			});
		</script>

		<?php //print_r($form) ?>
		<?php if ($page_name == 'form-settings'): ?>
			<?php $source = []; ?>
			<?php foreach ($form->question_list as $question): ?>
				<?php $source[] = array('value' => $question->question_id, 'label' => $question->question); ?>
			<?php endforeach; ?>

			<?php $title_source = []; ?>
			<?php foreach ($form->title_fields->entry_title as $question): ?>
				<?php $title_source[] = array('value' => $question->question_id, 'label' => $question->question); ?>
			<?php endforeach; ?>

			<?php $subtitle_source = []; ?>
			<?php foreach ($form->title_fields->entry_sub_title as $question): ?>
				<?php $subtitle_source[] = array('value' => $question->question_id, 'label' => $question->question); ?>
			<?php endforeach; ?>

			<?php $prefill_source = []; ?>
			<?php foreach ($form->followup_prefill as $question): ?>
				<?php $prefill_source[] = array('value' => $question->question_id, 'label' => $question->question); ?>
			<?php endforeach; ?>

			<script type="text/javascript">
				$(document).ready(function () {
					$('#tokenfield-title, #tokenfield-sub-title, #tokenfield-followup-prefill').tokenfield({
						autocomplete: {
							source: <?= json_encode($source) ?>,
							delay: 100
						},
						showAutocompleteOnFocus: true,
						createTokensOnBlur: true,
						delimiter: [',', ' ']
					});

					$('#tokenfield-title').tokenfield('setTokens', <?= json_encode($title_source) ?>);
					$('#tokenfield-sub-title').tokenfield('setTokens', <?= json_encode($subtitle_source) ?>);
					$('#tokenfield-followup-prefill').tokenfield('setTokens', <?= json_encode($prefill_source) ?>);
				});
			</script>
		<?php endif; ?>



		<script type="text/javascript">
			$(document).on('click', '.confirm-delete', function () {
				return confirm('Deleting this item could affect other records that are dependant on it. Are you sure you want to delete this item?');
			});

			$(document).on('click', '.confirm-tr-delete', function (e) {
				e.preventDefault();
				let remove = confirm('Deleting this item could affect other records that are dependant on it. Are you sure you want to delete this item?');
				if (remove == true) {
					let link = $(this);
					let tr = link.parents('tr');
					let api_url = link.attr('href');
					// console.log(api_url);
					$.get(api_url)
						.done(function (data) {
							// let response = JSON.parse(data);
							// console.log(response);
							console.log(data);
							// if (response.status == 200) {
							tr.remove();
							// }
						})
						.fail(function (error) {
							console.log(error);
						});
				}
			});

			$(document).on('click', '.confirm-chart-delete', function (e) {
				e.preventDefault();
				let remove = confirm('Deleting this chart will permanently remove it from the dashboard. Are you sure you want to delete this chart?');
				if (remove == true) {
					let link = $(this);
					let chart = link.parents('.chart-wrapper');
					let api_url = link.attr('href');
					// console.log(api_url);
					$.get(api_url)
						.done(function (data) {
							console.log(data);
							chart.remove();
						})
						.fail(function (error) {
							console.log(error);
						});
				}
			});

			$(document).on('click', '.confirm-update', function () {
				return confirm('Changing this item could affect other records that are dependant on it. Are you sure you want to save changes to this item?');
			});
		</script>

		<script type="text/javascript">
			$(document).ready(function () {

				$(document).on('change', '#dynamic-list-regions', function (e) {
					let id = $(this).val();
					let api_url = base_url + 'form-list-districts/' + id;
					$.get(api_url)
						.done(function (dom) {
							console.log(dom);
							$('#dynamic-list-districts').removeAttr('disabled').html(dom);
							$('#dynamic-list-sub-counties').html('').attr('disabled', 'disabled');
							$('#dynamic-list-parishes').html('').attr('disabled', 'disabled');
							$('#dynamic-list-villages').html('').attr('disabled', 'disabled');
						})
						.fail(function (error) {
							console.log(error);
						});
				});

				$(document).on('change', '#dynamic-list-districts', function (e) {
					let id = $(this).val();
					let api_url = base_url + 'form-list-sub-counties/' + id;
					$.get(api_url)
						.done(function (dom) {
							console.log(dom);
							$('#dynamic-list-sub-counties').removeAttr('disabled').html(dom);
							$('#dynamic-list-parishes').html('').attr('disabled', 'disabled');
							$('#dynamic-list-villages').html('').attr('disabled', 'disabled');
						})
						.fail(function (error) {
							console.log(error);
						});
				});

				$(document).on('change', '#dynamic-list-sub-counties', function (e) {
					let id = $(this).val();
					let api_url = base_url + 'form-list-parishes/' + id;
					$.get(api_url)
						.done(function (dom) {
							console.log(dom);
							$('#dynamic-list-parishes').removeAttr('disabled').html(dom);
							$('#dynamic-list-villages').html('').attr('disabled', 'disabled');
						})
						.fail(function (error) {
							console.log(error);
						});
				});

				$(document).on('change', '#dynamic-list-parish', function (e) {
					let id = $(this).val();
					let api_url = base_url + 'form-list-villages/' + id;
					$.get(api_url)
						.done(function (dom) {
							console.log(dom);
							$('#dynamic-list-villages').removeAttr('disabled').html(dom);
						})
						.fail(function (error) {
							console.log(error);
						});
				});

			});
		</script>

		<script type="text/javascript">
			$(document).ready(function () {

				$(document).on('click', '#add-another-field', function (e) {
					e.preventDefault();
					$('.cloneable:first').clone().appendTo('#clone-wrapper');
					$('.cloneable:last input').val('');
				});

			});
		</script>

		<?php if ($page_name == 'edit-entry'): ?>
			<script type="text/javascript">
				$(document).ready(function () {
					var conditional_logic = <?= json_encode($form->conditional_logic) ?>;
					// console.log(conditional_logic);

					$(document).on('change', 'input[type="radio"], input[type="checkbox"]', function (event) {
						let qn = $(this).attr('name');
						let answer = $(this).val();

						if (conditional_logic != undefined && conditional_logic[qn] != undefined && conditional_logic[qn][answer] != undefined) {
							if (conditional_logic[qn][answer]['hide'] != undefined) {

								// // Hidden fields to be fixed in next release
								// let hidden = conditional_logic[qn][answer]['hide'];
								// for (var i in hidden) {
								// 	let elem = 'input[name="' + i + '"][value="' + prefills[i] + '"]';
								// 	$$(elem).attr('disabled', true);
								// 	$$('#card-'+i).style('display', 'none');
								// }

							} else if (conditional_logic[qn][answer]['prefill'] != undefined) {
								let prefills = conditional_logic[qn][answer]['prefill'];
								for (var i in prefills) {
									let elem = 'input[name="' + i + '"][value="' + prefills[i] + '"]';
									$(elem).prop("checked", true);
								}
							}
						}
					});
				});
			</script>
		<?php endif; ?>


		<?php if ($page_name == 'map'): ?>
			<script type="text/javascript">
				var latitude = 0.2977574773742665;
				var longitude = 32.62463092803956;
				var map_data = JSON.parse(<?= json_encode($geodata) ?>);
				var marker_dir = "<?= base_url('assets/images/markers/') ?>";
			</script>
			<script type="text/javascript" src="<?= base_url('assets/js/script-manage-map.js') ?>"></script>
		<?php endif; ?>



</body>

</html>
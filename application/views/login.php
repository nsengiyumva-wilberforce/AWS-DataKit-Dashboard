<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8">
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="description" content="">
		<meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
		<meta name="generator" content="Jekyll v3.8.5">
		<title>Dashboard · AWS</title>

		<!-- Bootstrap core CSS -->
		<link rel="stylesheet" href="<?= base_url('assets/vendors/Bootstrap/bootstrap.css') ?>">

		<!-- Custom styles for this template -->
		<!-- <link href="<?= base_url('assets/vendors/Bootstrap/signin.css') ?>" rel="stylesheet"> -->
	</head>
	<body>
		<div class="container">
			<div class="row" style="margin-top: 100px;">
				<div class="col-6 d-flex align-items-center">
					<img src="<?= base_url('assets/images/Logo/large-logo.png') ?>" alt="" style="width: 80%; margin: 0 10%;">
				</div>
				<div class="col-6 d-flex align-items-center">
					<form class="p-4" action="<?= base_url('authenticate') ?>" method="POST" style="width: 80%; margin: 0 auto; background-color: #f5f5f5; border: 1px solid #ced4da;">
						<h1 class="h3 mb-3 font-weight-normal">Dashboard Sign In</h1>
						<div class="form-group">
						<label for="input3">Email Address</label>
							<input type="email" class="form-control" id="input3" name="username" required="required">
						</div>
						<div class="form-group">
							<label for="input6">Password</label>
							<input type="password" class="form-control" id="input6" name="password" required="required">
						</div>
						<button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
						<p><?= $this->session->flashdata('err_msg') ?></p>						
					</form>
				</div>
			</div>
			<div class="row text-center">
				<div class="col">
					<p class="mt-5 mb-3 text-muted">© 2017-<?= date('Y') ?></p>
				</div>
			</div>
		</div>
	</body>
</html>

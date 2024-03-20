
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
         
            <div class="container">
    <div class="row justify-content-md-center">
        
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <h2 class="card-title">Verify Please!</h2>
                                <div class="text">
                                    <!-- Display error message if set in flashdata -->
                                    <?php if ($this->session->flashdata('error')): ?>
                                        <div class="alert alert-danger" role="alert">
                                            <?= $this->session->flashdata('error') ?>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <p class="card-text">Please enter the six-digit code that has been sent to the <strong>GMAIL</strong> that you provided on regiration.</p>
                                <?php if(isset($errors)){?>
                                    <div class="alert alert-danger" role="alert">
                                        <?php print_r($errors);?>
                                    </div>
                                <?php }?>
                                <form data-toggle="validator" action="<?= base_url('codeValidation') ?>" method="post">
                                    <input type="hidden" name="uni_id" value="<?= $uni_id?>">
                                    <div class="form-group">
                                        <label for="code" class="control-label">Enter code here</label>
                                        <input id="code" class="form-control" type="text" name="code" required placeholder="******">
                                    </div>
                                    <div class="form-group">
                                        <button class="btn btn-primary btn-block" type="submit">Confirm Login</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
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


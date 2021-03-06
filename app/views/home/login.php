<h1>Logga in</h1>

<?php if ( Session::has('log_in_error') ): ?>
	<p class="alert alert-danger">
		<?php echo Session::get('log_in_error'); ?>
	</p>
<?php endif; ?>

<form action="<?php echo URL::to('/logga-in'); ?>" method="post" class="form-horizontal" role="form">
	<div class="form-group">
		<label for="inputEmail1" class="col-lg-2 control-label">E-mail</label>
		<div class="col-lg-10">
			<input type="email" name="email" id="email" class="form-control">
		</div>
	</div>

	<div class="form-group">
		<label for="inputPassword1" class="col-lg-2 control-label">Lösenord</label>
		<div class="col-lg-10">
			<input type="password" name="password" id="password" class="form-control">
		</div>
	</div>

	<div class="form-group">
		<div class="col-lg-offset-2 col-lg-10">
			<button type="submit" class="btn btn-default">Logga in</button>

            <a href="<?= URL::route('sign-up') ?>" class="btn btn-default">Bli medlem</a>
            <a href="<?= $facebook_login_link ?>" class="btn btn-default">Logga in med Facebook</a>
		</div>
	</div>
</form>
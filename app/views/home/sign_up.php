<h1>Bli Medlem</h1>

<?php if ( Session::has('sign_up_error') ): ?>
	<p class="alert alert-danger">
		<?php echo Session::get('sign_up_error'); ?>
	</p>
<?php endif; ?>

<form action="<?php echo URL::route('sign-up'); ?>" method="post" class="form-horizontal" role="form" id="sign_up_form">
	<!-- Användarnamn -->
	<div class="form-group">
		<label for="username" class="col-lg-2 control-label">Användarnamn <em>*</em></label>
		<div class="col-lg-10">
			<input type="text" name="username" id="username" class="form-control">
		</div>
	</div>

	<!-- Förnamn -->
	<div class="form-group">
		<label for="first_name" class="col-lg-2 control-label">Förnamn <em>*</em></label>
		<div class="col-lg-10">
			<input type="text" name="first_name" id="first_name" class="form-control">
		</div>
	</div>

	<!-- Efternamn -->
	<div class="form-group">
		<label for="last_name" class="col-lg-2 control-label">Efternamn <em>*</em></label>
		<div class="col-lg-10">
			<input type="text" name="last_name" id="last_name" class="form-control">
		</div>
	</div>

	<!-- E-mail -->
	<div class="form-group">
		<label for="email" class="col-lg-2 control-label">E-mail <em>*</em></label>
		<div class="col-lg-10">
			<input type="text" name="email" id="email" class="form-control">
		</div>
	</div>

	<!-- Stad -->
	<div class="form-group">
		<label for="city" class="col-lg-2 control-label">Stad <em>*</em></label>
		<div class="col-lg-10">
			<input type="text" name="city" id="city" class="form-control">
		</div>
	</div>

	<!-- Födelsedag -->
	<div class="form-group">
		<label for="birthdate" class="col-lg-2 control-label">Födelsedag <em>*</em></label>
		<div class="col-lg-10">
			<input type="text" name="birthdate" id="birthdate" class="form-control">
		</div>
	</div>

	<!-- Lösenord -->
	<div class="form-group">
		<label for="password" class="col-lg-2 control-label">Ditt lösenord <em>*</em></label>
		<div class="col-lg-10">
			<input type="password" name="password" id="password" class="form-control">
		</div>
	</div>

	<!-- Lösenord Igen -->
	<div class="form-group">
		<label for="password_verify" class="col-lg-2 control-label">Ditt lösenord igen <em>*</em></label>
		<div class="col-lg-10">
			<input type="password" name="password_verify" id="password_verify" class="form-control">
		</div>
	</div>

	<!-- Spara -->
	<div class="form-group">
		<div class="col-lg-offset-2 col-lg-10">
			<button type="submit" name="save" class="btn btn-primary">Bli Medlem</button>
		</div>
	</div>
</form>
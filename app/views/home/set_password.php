<h1>Sätt nytt lösenord</h1>

<p>Vi ser att det är första gången du loggar in sedan vi uppdaterat vår hemsida. Var vänlig ange ditt lösenord igen nedan.</p>

<form action="<?= URL::route('set-password') ?>" method="post" id="set_password_form">
	<div class="form-group">
		<input type="password" name="password" id="password" class="form-control">
	</div>

	<div class="form-group">
		<button type="submit" class="btn btn-primary">Spara och logga in</button>
	</div>
</form>
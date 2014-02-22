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
<p class="info-short">Fördelar som medlem</p>
<p>Som medlem får du en hel del fler funktioner kring dina spelningar. Vi kommer inte bara ihåg din senaste
    spelning vi kommer även ihåg alla dina spelningar.</p>
<p>Allt presenteras under "Min Sida" som du kommer åt när du klickar på ditt namn i menyn, som inloggad.
    Där ser du alla dina senaste spelningar, vilka spelningar som ej är avslutade, samt vilka podcasts du prenumererar på.
    Under tabellen "Ej lyssnat klart" kan du enkelt se hur långt du har kvar av varje avsnitt. Här kan du även starta avsnittet därifrån du var senast.
    Om du känner att du är klar med avsnittet klickar du bara på "markera som färdiglyssnad".
<p class="info-short">Statuskoder för avsnitt:</p>

<p class="label label-danger">Lyssna nu</p>
<p>Denna visar att du inte påbörjat någon spelning av avsnittet.</p>

<span class="label label-warning">Påbörjad</span>
<p>Denna ser du när avsnittet nån gång har börjat spelats. avsnittet finns då även med under min sida där du kan starta från där du slutade sist.
    Du kan även gå in på avsnittet från vilken plats på sidan som helt och trycka på play för börja spela där du var senast.</p>

<span class="label label-success">Lyssnad</span>
<p> Denna innebär att du lyssnat klart på avsnittet. Vi har själva valt att aldrig automatiskt avgöra om ett avsnitt
    är färdigspelat eller inte. Det avgör du själv, antingen via min sida där du kan välja "markera som färdiglyssnad". Eller direkt nere i spelaren
    när ett avsnitt spelas, då trycker du på den gröna bocken, till höger om play/pause.
</p>
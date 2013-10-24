<h1><?= $user->first_name; ?></h1>

<div class="row">
	<div class="col-lg-6">
		<p><?= $user->first_name, ' ', $user->last_name; ?></p>

		<img src="<?= $user->getAvatar(); ?>" alt="..." class="img-rounded">
	</div>

	<div class="col-md-4">
		<h2>Historik</h2>

		<h2>Prenumerationer</h2>
	</div>
</div>
<h1>Användare</h1>

<div id="add_button_container">
	<a href="<?= URL::to('admin/user', array(), false); ?>" class="btn btn-primary">Lägg till</a>
</div>

<table class="table table-bordered">
	<tr>
		<th>ID</th>
		<th>Användarnamn</th>
		<th>E-mail</th>
		<th>Namn</th>
		<th>Stad</th>
		<th>Födelsedag</th>
		<th>Senast Inloggad</th>
		<th>Blev Medlem</th>
		<th></th>
	</tr>

	<?php foreach ( $users as $user ): ?>
		<tr>
			<td><?= $user->id; ?></td>
			<td><?= $user->username; ?></td>
			<td><?= $user->email; ?></td>
			<td><?= $user->getDisplayName(); ?></td>
			<td><?= $user->city ?></td>
			<td><?= ($user->birthdate !== NULL ? $user->birthdate . ' (' . $user->get_age() . ' år)' : '/') ?></td>
			<td><?= $user->last_login !== NULL ? $user->last_login . ' (' . $user->num_logins . ')' : '/' ?></td>
			<td><?= $user->created_at ?></td>
			<td>
				<div class="btn-group">
					<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
						Hantera <span class="caret"></span>
					</button>
					<ul class="dropdown-menu" role="menu">
						<li><a href="<?= $user->get_profile_page() ?>">Visa Profil</a></li>
						<li class="divider"></li>
						<li><a href="<?= URL::to('admin/user/' . $user->id, array(), false); ?>">Ändra</a></li>
						<li><a href="<?= URL::to('admin/ta_bort_anvandare/' . $user->id, array(), false); ?>">Ta bort</a></li>
					</ul>
				</div>
			</td>
		</tr>
	<?php endforeach; ?>
</table>
<h1><?= $edit_mode ? 'Ändra' : 'Lägg till'; ?></h1>

<form class="form-horizontal" method="post" enctype="multipart/form-data" role="form">
	<?php if ( $edit_mode ): ?>
		<input type="hidden" name="user_id" value="<?= $user_to_edit->id; ?>">
	<?php endif; ?>

	<!-- Användarnamn -->
	<div class="form-group">
		<label for="username" class="col-lg-2 control-label">Användarnamn</label>
		<div class="col-lg-10">
			<input type="text" name="username" id="username" class="form-control"<?php if ($edit_mode): ?> value="<?= e($user_to_edit->username); ?>"<?php endif; ?>>
		</div>
	</div>

	<!-- Förnamn -->
	<div class="form-group">
		<label for="first_name" class="col-lg-2 control-label">Förnamn</label>
		<div class="col-lg-10">
			<input type="text" name="first_name" id="first_name" class="form-control"<?php if ($edit_mode): ?> value="<?= e($user_to_edit->first_name); ?>"<?php endif; ?>>
		</div>
	</div>

	<!-- Efternamn -->
	<div class="form-group">
		<label for="last_name" class="col-lg-2 control-label">Efternamn</label>
		<div class="col-lg-10">
			<input type="text" name="last_name" id="last_name" class="form-control"<?php if ($edit_mode): ?> value="<?= e($user_to_edit->last_name); ?>"<?php endif; ?>>
		</div>
	</div>

	<!-- E-mail -->
	<div class="form-group">
		<label for="email" class="col-lg-2 control-label">E-mail</label>
		<div class="col-lg-10">
			<input type="text" name="email" id="email" class="form-control"<?php if ($edit_mode): ?> value="<?= e($user_to_edit->email); ?>"<?php endif; ?>>
		</div>
	</div>

	<!-- Stad -->
	<div class="form-group">
		<label for="city" class="col-lg-2 control-label">Stad</label>
		<div class="col-lg-10">
			<input type="text" name="city" id="city" class="form-control"<?php if ($edit_mode): ?> value="<?= e($user_to_edit->city); ?>"<?php endif; ?>>
		</div>
	</div>

	<!-- Födelsedag -->
	<div class="form-group">
		<label for="birthdate" class="col-lg-2 control-label">Födelsedag</label>
		<div class="col-lg-10">
			<input type="text" name="birthdate" id="birthdate" class="form-control"<?php if ($edit_mode): ?> value="<?= e($user_to_edit->birthdate); ?>"<?php endif; ?>>
		</div>
	</div>

	<!-- Spara -->
	<div class="form-group">
		<div class="col-lg-offset-2 col-lg-10">
			<button type="submit" name="save" class="btn btn-primary"><?= $edit_mode ? 'Spara' : 'Lägg till'; ?></button>

			<a href="<?= URL::route('admin/users') ?>" class="btn btn-default"><?= $edit_mode ? 'Tillbaka' : 'Avbryt'; ?></a>
		</div>
	</div>
</form>
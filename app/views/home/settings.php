<ul class="breadcrumb">
	<li><a href="<?= URL::route('home', array(), ''); ?>">Podcasts.nu</a></li>
	<li><a href="<?= URL::route('min-sida', array(), ''); ?>">Min sida</a></li>
	<li>Inställningar</li>
</ul>

<h1>Inställningar</h1>

<div>
	<ul class="nav nav-tabs">
		<li<?php if ( $default_tab === 'general' ): ?> class="active"<?php endif ?>><a href="#general_tab" data-toggle="tab">Allmänt</a></li>
		<li<?php if ( $default_tab === 'avatar' ): ?> class="active"<?php endif ?>><a href="#avatar_tab" data-toggle="tab">Profilbild</a></li>
		<?php /*<li<?php if ( $default_tab === 'password' ): ?> class="active"<?php endif ?>><a href="#password_tab" data-toggle="tab">Lösenord</a></li>*/ ?>
	</ul>

	<div class="tab-content">
		<!-- Allmänt -->
		<div id="general_tab" class="tab-pane<?php if ( $default_tab === 'general' ): ?> active<?php endif ?>">
			<form action="<?= URL::route('installningar') ?>" method="post" id="general_settings_form">
				<!-- Förnamn -->
				<div class="form-group clearfix">
					<label for="first_name" class="col-lg-2 control-label">Förnamn</label>
					<div class="col-lg-10">
						<input type="text" name="first_name" id="first_name" class="form-control" value="<?= e($user->first_name); ?>">
					</div>
				</div>

				<!-- Efternamn -->
				<div class="form-group clearfix">
					<label for="last_name" class="col-lg-2 control-label">Efternamn</label>
					<div class="col-lg-10">
						<input type="text" name="last_name" id="last_name" class="form-control" value="<?= e($user->last_name); ?>">
					</div>
				</div>

				<!-- E-mail -->
				<div class="form-group clearfix">
					<label for="email" class="col-lg-2 control-label">E-mail</label>
					<div class="col-lg-10">
						<input type="text" name="email" id="email" class="form-control" value="<?= e($user->email); ?>">
					</div>
				</div>

				<!-- Stad -->
				<div class="form-group clearfix">
					<label for="city" class="col-lg-2 control-label">Stad</label>
					<div class="col-lg-10">
						<input type="text" name="city" id="city" class="form-control" value="<?= e($user->city); ?>">
					</div>
				</div>

				<!-- Födelsedag -->
				<div class="form-group clearfix">
					<label for="birthdate" class="col-lg-2 control-label">Födelsedag</label>
					<div class="col-lg-10">
						<input type="text" name="birthdate" id="birthdate" class="form-control" value="<?= e($user->birthdate); ?>">
					</div>
				</div>

				<!-- Spara -->
				<div class="form-group clearfix">
					<div class="col-lg-offset-2 col-lg-10">
						<button type="submit" name="save_general" class="btn btn-primary">Spara</button>
					</div>
				</div>
			</form>
		</div>

		<!-- Profilbild -->
		<div id="avatar_tab" class="tab-pane<?php if ( $default_tab === 'avatar' ): ?> active<?php endif ?>">
			<?php if ( $user->have_avatar() ): ?>
				<div style="margin-bottom:10px">
					<?= $user->get_avatar_image('installningar') ?>
				</div>
			<?php else: ?>
				<p>Ingen profilbild.</p>
			<?php endif ?>

			<form action="<?= URL::route('installningar') ?>" method="post" enctype="multipart/form-data" id="upload_avatar_form">
				<div class="form-group clearfix">
					<input type="file" name="avatar" id="avatar_file_input">
				</div>

				<!-- Spara -->
				<div class="form-group clearfix">
					<button type="submit" name="save_avatar" id="save_avatar_button" class="btn btn-primary disabled">Spara</button>
				</div>
			</form>
		</div>

		<!-- Lösenord -->
		<?php /*<div id="password_tab" class="tab-pane<?php if ( $default_tab === 'password' ): ?> active<?php endif ?>">
			<p>Etsy mixtape wayfarers, ethical wes anderson tofu before they sold out mcsweeney's organic lomo retro fanny pack lo-fi farm-to-table readymade. Messenger bag gentrify pitchfork tattooed craft beer, iphone skateboard locavore carles etsy salvia banksy hoodie helvetica. DIY synth PBR banksy irony. Leggings gentrify squid 8-bit cred pitchfork. Williamsburg banh mi whatever gluten-free, carles pitchfork biodiesel fixie etsy retro mlkshk vice blog. Scenester cred you probably haven't heard of them, vinyl craft beer blog stumptown. Pitchfork sustainable tofu synth chambray yr.</p>
		</div>*/ ?>
	</div>
</div>
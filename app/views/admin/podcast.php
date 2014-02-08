<h1><?= $edit_mode ? 'Ändra' : 'Lägg till'; ?></h1>

<form class="form-horizontal" method="post" enctype="multipart/form-data" role="form">
	<?php if ( $edit_mode ): ?>
		<input type="hidden" name="podcast_id" value="<?= $podcast_to_edit->id; ?>">
	<?php endif; ?>

	<div class="form-group">
		<label for="name" class="col-lg-2 control-label">Namn</label>
		<div class="col-lg-10">
			<input type="text" name="name" id="name" class="form-control"<?php if ($edit_mode): ?> value="<?= e($podcast_to_edit->name); ?>"<?php endif; ?>>
		</div>
	</div>

	<div class="form-group">
		<label for="slug" class="col-lg-2 control-label">Slug</label>
		<div class="col-lg-10">
			<input type="text" name="slug" id="slug" class="form-control"<?php if ($edit_mode): ?> value="<?= e($podcast_to_edit->slug); ?>"<?php endif; ?>>
		</div>
	</div>

	<div class="form-group">
		<label for="description" class="col-lg-2 control-label">Beskrivning</label>
		<div class="col-lg-10">
			<textarea name="description" id="description" class="form-control"><?php if ($edit_mode): ?><?= $podcast_to_edit->description; ?><?php endif; ?></textarea>
		</div>
	</div>

	<div class="form-group">
		<label for="category" class="col-lg-2 control-label">Kategori</label>
		<div class="col-lg-10">
			<select name="category" id="category" class="form-control">
				<option value="">Välj</option>

				<?php foreach ( $categories as $category ): ?>
					<option value="<?= $category->id; ?>"<?php if ($edit_mode && $podcast_to_edit->category_id === $category->id): ?> selected<?php endif; ?>><?= $category->title; ?></option>
				<?php endforeach; ?>
			</select>
		</div>
	</div>

	<div class="form-group">
		<label for="name" class="col-lg-2 control-label">RSS</label>
		<div class="col-lg-10">
			<input type="text" name="rss" id="rss" class="form-control"<?php if ($edit_mode): ?> value="<?= e($podcast_to_edit->rss); ?>"<?php endif; ?>>
		</div>
	</div>

	<div class="form-group">
		<label for="name" class="col-lg-2 control-label">Webbplats</label>
		<div class="col-lg-10">
			<input type="text" name="homepage" id="homepage" class="form-control"<?php if ($edit_mode): ?> value="<?= e($podcast_to_edit->homepage); ?>"<?php endif; ?>>
		</div>
	</div>

	<div class="form-group">
		<label for="facebook" class="col-lg-2 control-label">Facebook-URL</label>
		<div class="col-lg-10">
			<input type="text" name="facebook" id="facebook" class="form-control"<?php if ($edit_mode): ?> value="<?= e($podcast_to_edit->facebook); ?>"<?php endif; ?>>
		</div>
	</div>

	<div class="form-group">
		<label for="twitter" class="col-lg-2 control-label">Twitter-URL</label>
		<div class="col-lg-10">
			<input type="text" name="twitter" id="twitter" class="form-control"<?php if ($edit_mode): ?> value="<?= e($podcast_to_edit->twitter); ?>"<?php endif; ?>>
		</div>
	</div>

	<div class="form-group">
		<label for="itunes" class="col-lg-2 control-label">iTunes</label>
		<div class="col-lg-10">
			<input type="text" name="itunes" id="itunes" class="form-control"<?php if ($edit_mode): ?> value="<?= e($podcast_to_edit->itunes); ?>"<?php endif; ?>>
		</div>
	</div>

	<div class="form-group">
		<label for="itunes" class="col-lg-2 control-label">E-mail</label>
		<div class="col-lg-10">
			<input type="text" name="email" id="email" class="form-control"<?php if ($edit_mode): ?> value="<?= e($podcast_to_edit->email); ?>"<?php endif; ?>>
		</div>
	</div>

	<fieldset>
		<legend>Bilder</legend>

		<?php if ( $edit_mode && $podcast_to_edit->hasImage('standard') ): ?>
			<h3>Standard</h3>
			<img src="<?= $podcast_to_edit->getImage('standard', false, true); ?>" width="" height="" alt="">
		<?php endif; ?>

		<?php if ( $edit_mode && $podcast_to_edit->hasImage('panorama') ): ?>
			<h3>Panorama</h3>
			<img src="<?= $podcast_to_edit->getImage('panorama', false, true); ?>" width="" height="" alt="">
		<?php endif; ?>

		<div class="form-group">
			<label class="col-lg-2 control-label">Standard</label>
			<div class="col-lg-10">
				<input type="file" name="image_standard">
			</div>
		</div>

		<div class="form-group">
			<label class="col-lg-2 control-label">Panorama</label>
			<div class="col-lg-10">
				<input type="file" name="image_panorama">
			</div>
		</div>
	</fieldset>

	<div class="form-group">
		<div class="col-lg-offset-2 col-lg-10">
			<button type="submit" name="save" class="btn btn-primary"><?= $edit_mode ? 'Spara' : 'Lägg till'; ?></button>
		</div>
	</div>
</form>
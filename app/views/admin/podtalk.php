<ul class="breadcrumb">
	<li><a href="<?= URL::route('home', array(), ''); ?>">Podcasts.nu</a></li>
	<li><a href="<?= URL::route('min-sida', array(), ''); ?>">Administratör</a></li>
	<li><a href="<?= URL::route('admin/poddsnack', array(), ''); ?>">Poddsnack</a></li>
	<li><?= $edit_mode ? $podtalk_to_edit->title : 'Lägg till'; ?></li>
</ul>

<h1><?= $edit_mode ? 'Ändra' : 'Lägg till'; ?></h1>

<form class="form-horizontal" method="post" enctype="multipart/form-data" role="form">
	<?php if ( $edit_mode ): ?>
		<input type="hidden" name="podtalk_id" value="<?= $podtalk_to_edit->id; ?>">
	<?php endif; ?>

	<div class="form-group">
		<label for="title" class="col-lg-2 control-label">Titel</label>
		<div class="col-lg-10">
			<input type="text" name="title" id="title" class="form-control"<?php if ($edit_mode): ?> value="<?= $podtalk_to_edit->title; ?>"<?php endif; ?>>
		</div>
	</div>

	<div class="form-group">
		<label for="slug" class="col-lg-2 control-label">Slug</label>
		<div class="col-lg-10">
			<input type="text" name="slug" id="slug" class="form-control"<?php if ($edit_mode): ?> value="<?= $podtalk_to_edit->slug; ?>"<?php endif; ?>>
		</div>
	</div>

	<div class="form-group">
		<label for="image" class="col-lg-2 control-label">Bild</label>
		<div class="col-lg-10">
			<input type="file" name="image" id="image">
		</div>
	</div>

	<?php if ( $podtalk_to_edit && $podtalk_to_edit->image === 'yes' ): ?>
		<div class="form-group">
			<label class="col-lg-2 control-label"></label>
			<div class="col-lg-10">
				<?= $podtalk_to_edit->get_image('admin_poddsnack') ?>
			</div>
		</div>
	<?php endif ?>

	<div class="form-group">
		<label for="description" class="col-lg-2 control-label">Beskrivning</label>
		<div class="col-lg-10">
			<input type="text" name="description" id="description" class="form-control"<?php if ($edit_mode): ?> value="<?= $podtalk_to_edit->description; ?>"<?php endif; ?>>
		</div>
	</div>

	<div class="form-group">
		<label for="body" class="col-lg-2 control-label">Innehåll</label>
		<div class="col-lg-10">
			<textarea name="body" id="body"><?php if ($edit_mode): ?><?= $podtalk_to_edit->body; ?><?php endif; ?></textarea>
		</div>
	</div>

	<div class="form-group">
		<div class="col-lg-offset-2 col-lg-10">
			<button type="submit" name="save" class="btn btn-primary"><?= $edit_mode ? 'Spara' : 'Lägg till'; ?></button>
		</div>
	</div>
</form>
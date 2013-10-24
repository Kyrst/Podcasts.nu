<ul class="breadcrumb">
	<li><a href="<?= URL::route('home', array(), ''); ?>">Podcasts.nu</a></li>
	<li><a href="<?= URL::route('min-sida', array(), ''); ?>">Administratör</a></li>
	<li><a href="<?= URL::route('admin/episodes', array(), ''); ?>">Avsnitt</a></li>
	<li><?= $edit_mode ? $episode_to_edit->getTitle() : 'Lägg till'; ?></li>
</ul>

<h1><?= $edit_mode ? 'Ändra' : 'Lägg till'; ?></h1>

<form class="form-horizontal" method="post" enctype="multipart/form-data" role="form">
	<?php if ( $edit_mode ): ?>
		<input type="hidden" name="episode_id" value="<?= $episode_to_edit->id; ?>">
	<?php endif; ?>

	<div class="form-group">
		<label for="podcast" class="col-lg-2 control-label">Podcast</label>
		<div class="col-lg-10">
			<select name="podcast" id="podcast" class="form-control">
				<option value="">Välj</option>

				<?php foreach ( $podcasts as $podcast ): ?>
					<option value="<?php echo $podcast->id; ?>"<?php if ($edit_mode && $episode_to_edit->podcast_id === $podcast->id): ?> selected<?php endif; ?>><?php echo $podcast->name; ?></option>
				<?php endforeach; ?>
			</select>
		</div>
	</div>

	<div class="form-group">
		<label for="title" class="col-lg-2 control-label">Titel</label>
		<div class="col-lg-10">
			<input type="text" name="title" id="title" class="form-control"<?php if ($edit_mode): ?> value="<?= $episode_to_edit->title; ?>"<?php endif; ?>>
		</div>
	</div>

	<div class="form-group">
		<label for="slug" class="col-lg-2 control-label">Slug</label>
		<div class="col-lg-10">
			<input type="text" name="slug" id="slug" class="form-control"<?php if ($edit_mode): ?> value="<?= $episode_to_edit->slug; ?>"<?php endif; ?>>
		</div>
	</div>

	<div class="form-group">
		<label for="description" class="col-lg-2 control-label">Beskrivning</label>
		<div class="col-lg-10">
			<textarea name="description" id="description"><?php if ($edit_mode): ?><?= $episode_to_edit->description; ?><?php endif; ?></textarea>
		</div>
	</div>

	<div class="form-group">
		<label for="media_link" class="col-lg-2 control-label">Media-URL</label>
		<div class="col-lg-10">
			<input type="text" name="media_link" id="media_link" class="form-control"<?php if ($edit_mode): ?> value="<?= $episode_to_edit->media_link; ?>"<?php endif; ?>>
		</div>
	</div>

	<div class="form-group">
		<div class="col-lg-offset-2 col-lg-10">
			<button type="submit" name="save" class="btn btn-default"><?= $edit_mode ? 'Spara' : 'Lägg till'; ?></button>
		</div>
	</div>
</form>
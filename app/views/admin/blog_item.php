<ul class="breadcrumb">
	<li><a href="<?= URL::route('home', array(), ''); ?>">Podcasts.nu</a></li>
	<li><a href="<?= URL::route('min-sida', array(), ''); ?>">Administratör</a></li>
	<li><a href="<?= URL::route('admin/blogg', array(), ''); ?>">Blogg</a></li>
	<li><?= $edit_mode ? $blog_item_to_edit->title : 'Lägg till'; ?></li>
</ul>

<h1><?= $edit_mode ? 'Ändra' : 'Lägg till'; ?></h1>

<form class="form-horizontal" method="post" enctype="multipart/form-data" role="form">
	<?php if ( $edit_mode ): ?>
		<input type="hidden" name="blog_item_id" value="<?= $blog_item_to_edit->id; ?>">
	<?php endif; ?>

	<div class="form-group">
		<label for="title" class="col-lg-2 control-label">Titel</label>
		<div class="col-lg-10">
			<input type="text" name="title" id="title" class="form-control"<?php if ($edit_mode): ?> value="<?= $blog_item_to_edit->title; ?>"<?php endif; ?>>
		</div>
	</div>

	<div class="form-group">
		<label for="slug" class="col-lg-2 control-label">Slug</label>
		<div class="col-lg-10">
			<input type="text" name="slug" id="slug" class="form-control"<?php if ($edit_mode): ?> value="<?= $blog_item_to_edit->slug; ?>"<?php endif; ?>>
		</div>
	</div>

	<div class="form-group">
		<label for="body" class="col-lg-2 control-label">Innehåll</label>
		<div class="col-lg-10">
			<textarea name="body" id="body"><?php if ($edit_mode): ?><?= $blog_item_to_edit->body; ?><?php endif; ?></textarea>
		</div>
	</div>

	<div class="form-group">
		<label for="title" class="col-lg-2 control-label">Tillagd</label>
		<div class="col-lg-10">
			<input type="datetime" name="added" id="added" class="form-control" value="<?php if ($edit_mode): ?><?= $blog_item_to_edit->created_at ?><?php else: ?>Now<?php endif; ?>">
			<br>
			<span class="helper-text">T.ex. Now, Today 20:30, Tomorrow, Yesterday 14:30, 2011-05-15 10:00</span>
		</div>
	</div>

	<div class="form-group">
		<div class="col-lg-offset-2 col-lg-10">
			<button type="submit" name="save" class="btn btn-primary"><?= $edit_mode ? 'Spara' : 'Lägg till'; ?></button>
		</div>
	</div>
</form>
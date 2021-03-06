<style>
	.cke_dialog_ui_input_file { height: 200px }
	.cke_dialog_ui_fileButton { margin-top: 100px !important }
</style>

<ul class="breadcrumb">
	<li><a href="<?= URL::route('home', array(), ''); ?>">Podcasts.nu</a></li>
	<li><a href="<?= URL::route('min-sida', array(), ''); ?>">Administratör</a></li>
	<li><a href="<?= URL::route('admin/nyheter', array(), ''); ?>">Nyheter</a></li>
	<li><?= $edit_mode ? $news_item_to_edit->title : 'Lägg till'; ?></li>
</ul>

<h1><?= $edit_mode ? 'Ändra' : 'Lägg till'; ?></h1>

<form class="form-horizontal" method="post" role="form">
	<?php if ( $edit_mode ): ?>
		<input type="hidden" name="news_item_id" value="<?= $news_item_to_edit->id; ?>">
	<?php endif; ?>

	<div class="form-group">
		<label for="title" class="col-lg-2 control-label">Titel</label>
		<div class="col-lg-10">
			<input type="text" name="title" id="title" class="form-control"<?php if ($edit_mode): ?> value="<?= $news_item_to_edit->title; ?>"<?php endif; ?>>
		</div>
	</div>

	<div class="form-group">
		<label for="_content" class="col-lg-2 control-label">Innehåll</label>
		<div class="col-lg-10">
			<textarea name="content" id="_content"><?php if ($edit_mode): ?><?= $news_item_to_edit->content; ?><?php endif; ?></textarea>
		</div>
	</div>

	<div class="form-group">
		<div class="col-lg-offset-2 col-lg-10">
			<button type="submit" name="save" class="btn btn-primary"><?= $edit_mode ? 'Spara' : 'Lägg till'; ?></button>
		</div>
	</div>
</form>
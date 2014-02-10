
<h1>Poddar</h1>

<a href="javascript:" data-view_type="grid" class="change-view-type btn btn-primary">Grid</a>
<a href="javascript:" data-view_type="list" class="change-view-type btn btn-primary">List</a>

<div class="btn-group">
	<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
		<span id="selected_category_text">Alla kategorier</span>
		<span class="caret"></span>
	</button>

	<ul class="dropdown-menu">
		<?php foreach ( $categories as $category ): ?>
			<li><a href="javascript:" data-id="<?= $category->id; ?>" data-title="<?= $category->title; ?>" class="category"><?= $category->title; ?></a></li>
		<?php endforeach; ?>
	</ul>
</div>

<div id="podcasts">
	<?= $podcasts_html; ?>
</div>
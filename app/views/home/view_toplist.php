 <h1>Topplista</h1>

<!-- Alla kategorier -->
<div class="btn-group">
	<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
		<span id="selected_category_text">Alla kategorier</span>
		<span class="caret"></span>
	</button>

	<ul id="category_select" class="dropdown-menu">
		<li><a href="javascript:" data-id="" data-title="Alla kategorier" class="category">Alla kategorier</a></li>

		<?php foreach ( $categories as $category ): ?>
			<li><a href="javascript:" data-id="<?= $category->id; ?>" data-title="<?= $category->title; ?>" class="category"><?= $category->title; ?></a></li>
		<?php endforeach ?>
	</ul>
</div>

<!-- Vad -->
<div class="btn-group">
	<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
		<span id="selected_type_text">Mest spelat</span>
		<span class="caret"></span>
	</button>

	<ul id="category_select" class="dropdown-menu">
		<?php foreach ( $types as $type_id => $type_name ): ?>
			<li><a href="javascript:" data-id="<?= $type_id ?>" data-title="<?= $type_name ?>" class="type"><?= $type_name; ?></a></li>
		<?php endforeach ?>
	</ul>
</div>



<div id="stats_container" style="margin-top:15px"></div>
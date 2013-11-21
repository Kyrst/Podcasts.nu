<h1>Nyheter</h1>

<div id="add_button_container">
	<a href="<?= URL::route('admin/nyhet', array(), false); ?>" class="btn btn-primary">Lägg till</a>
</div>

<?php if ( $num_news_items > 0 ): ?>
	<table class="table table-bordered">
		<tr>
			<th>ID</th>
			<th>Titel</th>
			<th>Innehåll</th>
			<th>Tillagd</th>
			<th></th>
		</tr>

		<?php foreach ( $news_items as $news_item ): ?>
			<tr>
				<td><?= $news_item->id; ?></td>
				<td><?= $news_item->title; ?></td>
				<td><?= Str::limit($news_item->content, 50); ?></td>
				<td><?= $news_item->created_at; ?></td>
				<td><a href="<?= URL::to('admin/nyhet/' . $news_item->id, array(), false); ?>" class="btn btn-primary">Ändra</a></td>
			</tr>
		<?php endforeach; ?>
	</table>
<?php else: ?>
	<p>Inga nyheter.</p>
<?php endif; ?>
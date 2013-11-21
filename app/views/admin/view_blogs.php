<h1>Blogg</h1>

<div id="add_button_container">
	<a href="<?= URL::route('admin/blogginlagg', array(), false); ?>" class="btn btn-primary">Nytt blogginlägg</a>
</div>

<table class="table table-bordered">
	<tr>
		<th>ID</th>
		<th>Av</th>
		<th>Titel</th>
		<th>Innehåll</th>
		<th>Skapad</th>
		<th></th>
	</tr>

	<?php foreach ( $blog_items as $blog_item ): ?>
		<tr>
			<td><?= $blog_item->id ?></td>
			<td><?= $blog_item->user->getDisplayName() ?></td>
			<td><?= $blog_item->title ?></td>
			<td><?= Str::limit($blog_item->body, 100) ?></td>
			<td><?= $blog_item->created_at ?></td>
			<td><a href="<?= URL::to('admin/blogginlagg/' . $blog_item->id, array(), false); ?>" class="btn btn-primary">Ändra</a></td>
		</tr>
	<?php endforeach; ?>
</table>
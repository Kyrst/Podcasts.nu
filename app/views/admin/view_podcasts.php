<h1>Poddar</h1>

<div id="add_button_container">
	<a href="<?= URL::route('admin/podd', array(), false); ?>" class="btn btn-primary">Lägg till</a>
</div>

<table class="table table-bordered">
	<tr>
		<th>ID</th>
		<th>Artist</th>
		<th>Kategori</th>
		<th></th>
	</tr>

	<?php foreach ( $podcasts as $podcast ): ?>
		<tr>
			<td><?= $podcast->id; ?></td>
			<td><?= $podcast->name; ?></td>
			<td><?= !empty($podcast->category) ? $podcast->category->title : '/'; ?></td>
			<td><a href="<?= URL::to('admin/podd/' . $podcast->id, array(), false); ?>" class="btn btn-primary">Ändra</a></td>
		</tr>
	<?php endforeach; ?>
</table>
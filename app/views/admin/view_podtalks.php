<h1>Poddsnack</h1>

<div id="add_button_container">
	<a href="<?= URL::route('admin/poddsnack', array(), false); ?>" class="btn btn-primary">Lägg till</a>
</div>

<table class="table table-bordered">
	<tr>
		<th></th>
		<th>ID</th>
		<th>Titel</th>
		<th>Beskrivning</th>
		<th>Innehåll</th>
		<th></th>
	</tr>

	<?php foreach ( $podtalks as $podtalk ): ?>
		<tr>
			<th><?= $podtalk->get_image('small') ?></th>
			<td><?= $podtalk->id; ?></td>
			<td><?= $podtalk->title; ?></td>
			<td><?= Str::limit($podtalk->description, 50); ?></td>
			<td><?= Str::limit($podtalk->body, 50); ?></td>
			<td><a href="<?= URL::to('admin/poddsnack/' . $podtalk->id, array(), false); ?>" class="btn btn-primary">Ändra</a></td>
		</tr>
	<?php endforeach; ?>
</table>
<h1>Avsnitt</h1>

<div id="add_button_container">
	<a href="<?= URL::route('admin/episode', array(), false); ?>" class="btn btn-primary">Lägg till</a>
</div>

<table class="table table-bordered">
	<tr>
		<th></th>
		<th>ID</th>
		<th>Titel</th>
		<th>Podd</th>
		<th>Beskrivning</th>
		<th></th>
	</tr>

	<?php foreach ( $episodes as $episode ): ?>
		<tr>
			<td>
				<?php if ( $episode->haveMedia() ): ?>
					<?= $episode->printPlayButton(); ?>
				<?php endif; ?>
			</td>
			<td><?= $episode->id; ?></td>
			<td><?= $episode->title; ?></td>
			<td><?= $episode->podcast->name; ?></td>
			<td><?= Str::limit($episode->description, 50); ?></td>
			<td><a href="<?= URL::to('admin/episode/' . $episode->id, array(), false); ?>" class="btn btn-primary">Ändra</a></td>
		</tr>
	<?php endforeach; ?>
</table>
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
		<th>Dold</th>
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
			<td><?= $episode->hide === 'yes' ? 'Ja' : 'Nej' ?></td>
			<td>
				<div class="btn-group">
					<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
						Hantera <span class="caret"></span>
					</button>
					<ul class="dropdown-menu" role="menu">
						<li><a href="<?= URL::to('admin/episode/' . $episode->id, array(), false); ?>"">Ändra</a></li>
						<li><a href="<?= URL::to('admin/hide-episode/' . $episode->id, array(), false); ?>">Dölj</a></li>
					</ul>
				</div>
			</td>
		</tr>
	<?php endforeach; ?>
</table>
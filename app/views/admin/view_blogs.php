<?php if ( $user->blog !== NULL ): ?>
	<h1><?= $user->blog->name ?></h1>

	<div id="add_button_container">
		<a href="<?= URL::route('admin/blogginlagg', array(), false); ?>" class="btn btn-primary">Nytt blogginlägg</a>
	</div>

	<?php if ( $num_blog_items > 0 ): ?>
		<table class="table table-bordered">
			<tr>
				<th>ID</th>
				<th>Av</th>
				<th>Titel</th>
				<th>Skapad</th>
				<th></th>
			</tr>

			<?php foreach ( $blog_items as $blog_item ): ?>
				<tr>
					<td><?= $blog_item->id ?></td>
					<td><?= $blog_item->user->getDisplayName() ?></td>
					<td><?= $blog_item->title ?></td>
					<td><?= $blog_item->created_at ?></td>
					<td>
						<a href="<?= URL::to('admin/blogginlagg/' . $blog_item->id, array(), false); ?>" class="btn btn-primary">Ändra</a>
						<a href="<?= URL::to('admin/blogginlagg/ta-bort/' . $blog_item->id, array(), false); ?>" class="btn btn-primary delete-blog">Ta bort</a>
					</td>
				</tr>
			<?php endforeach; ?>
		</table>
	<?php else: ?>
		<p>Inga blogginlägg.</p>
	<?php endif ?>
<?php else: ?>
	<h1>Blogg</h1>

	<p>Du är inte kopplad till någon blogg.</p>
<?php endif; ?>
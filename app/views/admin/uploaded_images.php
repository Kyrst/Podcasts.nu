<ul class="breadcrumb">
	<li><a href="<?= URL::route('home', array(), ''); ?>">Podcasts.nu</a></li>
	<li><a href="<?= URL::route('min-sida', array(), ''); ?>">Administratör</a></li>
	<li>Uppladdade Bilder</li>
</ul>

<h1>Uppladdade Bilder</h1>

<?php if ( count($uploaded_images) > 0 ): ?>
	<table class="table">
		<thead>
			<tr>
				<th></th>
				<th>Filnamn</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ( $uploaded_images as $image ): ?>
				<tr>
					<td><img src="<?= $image['url'] ?>" width="100" alt=""></td>
					<td>
						<strong><?= $image['basename'] ?></strong>
						<br>
						<?= $image['filename'] ?>
					</td>
					<td><a href="<?= URL::to('/admin/uppladdade-bilder/ta-bort/' . e($image['basename'])) ?>/<?= $image['type'] ?>" class="btn btn-primary delete-image">Ta bort</a></td>
				</tr>
			<?php endforeach ?>
		</tbody>
	</table>
<?php else: ?>
<?php endif ?>
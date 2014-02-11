<h1>Poddsnack</h1>

<?php if ( count($podtalks) > 0 ): ?>
	<?php foreach ( $podtalks as $podtalk ): ?>

		<div id="podtalk_<?= $podtalk->id ?>" class="col-lg-3 col-md-3 col-sm-4 col-xs-6">
			<div class="thumbnail text-center">
				<a href="<?= $podtalk->get_link() ?>"><?= $podtalk->get_image('medium') ?></a>

				<div class="caption">
					<h3><a href="<?= $podtalk->get_link() ?>"><?= $podtalk->title ?></a></h3>
					<p><?= $podtalk->description ?></p>
				</div>
			</div>
		</div>

	<?php endforeach ?>
<?php else: ?>
	<p>Inga poddsnack.</p>
<?php endif ?>
<?php foreach ( $podtalks as $podtalk ): ?>
	<div id="podtalk_<?= $podtalk->id ?>" class="col-lg-3 col-md-3 col-sm-4 col-xs-6">
		<div class="thumbnail">
			<div class="caption">
				<h3><a href="<?= $podtalk->get_link() ?>"><?= $podtalk->title ?></a></h3>
				<p><?= $podtalk->description ?></p>
			</div>
		</div>
	</div>
<?php endforeach ?>
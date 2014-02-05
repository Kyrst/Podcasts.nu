<?php foreach ( $episodes as $episode ): ?>
	<div class="media">
		<?php if ( $episode->podcast !== NULL ): ?>
			<a href="/" class="pull-left">
				<img src="<?= $episode->podcast->getImage('standard', false, true) ?>" width="60" height="" alt="...">
			</a>

			<div class="media-body">
				<h4 class="media-heading"><?= $episode->printPlayButton() ?> <?php if ( $_podcast === NULL ): ?><a href="<?= $episode->podcast->getLink('avsnitt') ?>"><?= $episode->podcast->name ?></a> - <?php endif ?><a href="<?= $episode->getLink('avsnitt'); ?>"><?= $episode->title; ?></a> </h4>
				<p><?=date('Y-m-d H:i:s', $episode->pub_date) ?></p>

				<?= $episode->print_rater() ?>
			</div>
		<?php else: ?>
			yoyo
		<?php endif ?>
	</div>
<?php endforeach; ?>
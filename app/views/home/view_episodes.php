<ul class="breadcrumb">
	<li><a href="<?= URL::route('home', array(), ''); ?>">Podcasts.nu</a></li>
	<li><a href="<?= URL::route('avsnitt', array(), ''); ?>">Avsnitt</a></li>
	<?php if ( $podcast ): ?><li><?= $podcast->name; ?></li><?php endif; ?>
</ul>

<div class="clearfix">
	<div class="col-md-9">
		<h1>Avsnitt<?php if ( $podcast ): ?> - <?= $podcast->name ?><?php endif ?></h1>

		<?php if ( $num_episodes > 0 ): ?>
			<div id="episodes">
				<?php foreach ( $episodes as $episode ): ?>
					<div class="media">
						<a href="/" class="pull-left">
							<img src="<?= $episode->podcast->getImage('standard', false, true) ?>" width="60" height="" alt="...">
						</a>

						<div class="media-body">
							<h4 class="media-heading"><?= $episode->printPlayButton() ?> <a href="<?= $episode->podcast->getLink('avsnitt') ?>"><?= $episode->podcast->name ?></a> - <a href="<?= $episode->getLink('avsnitt'); ?>"><?= $episode->title; ?></a> </h4>
							<p><?= $episode->created_at; ?></p>

							<?= $episode->print_rater() ?>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		<?php else: ?>
			<p>Inga avsnitt.</p>
		<?php endif; ?>
	</div>

	<?php if ( $podcast ): ?>
		<div class="col-md-3">
			<h3><?= $podcast->name ?></h3>

			<?= $podcast->print_rater() ?>
			<?= $podcast->get_score(1) ?>
		</div>
	<?php endif ?>
</div>
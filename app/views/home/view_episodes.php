<ul class="breadcrumb">
	<li><a href="<?= URL::route('home', array(), ''); ?>">Podcasts.nu</a></li>
	<li><?php if ( $podcast ): ?><a href="<?= URL::route('avsnitt', array(), ''); ?>">Avsnitt</a><?php else: ?>Avsnitt<?php endif ?></li>
	<?php if ( $podcast ): ?><li><?= $podcast->name; ?></li><?php endif; ?>
</ul>

<div class="btn-group">
	<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
		<span id="selected_category_text">Alla kategorier</span>
		<span class="caret"></span>
	</button>

	<ul id="category_select" class="dropdown-menu">
		<?php foreach ( $categories as $category ): ?>
			<li><a href="javascript:" data-id="<?= $category->id; ?>" data-title="<?= $category->title; ?>" class="category"><?= $category->title; ?></a></li>
		<?php endforeach; ?>
	</ul>
</div>

<div id="episodes" class="clearfix">
	<div class="col-md-9">
		<h1>Avsnitt<?php if ( $podcast ): ?> - <?= $podcast->name ?><?php endif ?></h1>

		<div id="episodes_container">
			<?= $episodes_html; ?>
		</div>
	</div>

	<?php if ( $podcast ): ?>
		<div class="col-md-3">
			<h3><?= $podcast->name ?></h3>

			<?= $podcast->print_rater() ?>
			<?= $podcast->get_score(1) ?>

			<div class="clear"></div>

			<?= $podcast->get_subscription_link('Följ', 'Följer', $user, 'btn btn-default btn-sm') ?>
		</div>
	<?php endif ?>
</div>

<?php /*<div class="clearfix">
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
                            <p><?=date('Y-m-d H:i:s', $episode->pub_date) ?></p>

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

			<div class="clear"></div>

			<?= $podcast->get_subscription_link('Följ', 'Följer', $user, 'btn btn-default btn-sm') ?>
		</div>
	<?php endif ?>

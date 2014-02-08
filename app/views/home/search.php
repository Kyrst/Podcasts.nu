<h1><?= $search_term ?></h1>

<?php if ( $num_found > 0 ): ?>
	<p><strong><?= $num_found ?></strong> träff<?php if ( $num_found !== 1 ): ?>ar<?php endif ?>.</p>

	<!-- Poddar -->
	<?php if ( count($podcasts) > 0 ): ?>
		<h2>Podcasts</h2>

		<?php foreach ( $podcasts as $podcast ): ?>
			<h3><a href="<?= $podcast->getLink('poddar') ?>"><?= $podcast->name ?></a></h3>
			<p><?= $podcast->description ?></p>
			<?= $podcast->print_rater() ?>
		<?php endforeach ?>
	<?php endif ?>

	<!-- Avsnitt -->
	<?php if ( count($episodes) > 0 ): ?>
		<h2>Avsnitt</h2>

		<?php foreach ( $episodes as $episode ): ?>
			<h3><a href="<?= $episode->getLink() ?>"><?= $episode->getTitle() ?></a></h3>
			<?= $episode->print_rater(true) ?>
		<?php endforeach ?>
	<?php endif ?>
<?php elseif ( $search_term !== '' ): ?>
	<p>Inga sökträffar på <strong><?= $search_term ?></strong>.</p>
<?php endif ?>
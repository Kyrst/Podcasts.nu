<div class="col-lg-4">
	<h2>Högst Betyg</h2>

	<h3>Avsnitt</h3>

	<?php if ( count($episodes) ): ?>
		<ol>
			<?php foreach ( $episodes as $episode ): ?>
				<li><a href="/"><?= $episode->title ?></a> (<?= number_format($episode->avg_score, 1) ?>)</li>
			<?php endforeach ?>
		</ol>
	<?php else: ?>
		<p>-</p>
	<?php endif ?>

	<h3>Poddar</h3>

	<?php if ( count($podcasts) ): ?>
		<ol>
			<?php foreach ( $podcasts as $podcast ): ?>
				<li><a href="/"><?= $podcast->name ?></a> (<?= number_format($podcast->avg_score, 1) ?>)</li>
			<?php endforeach ?>
		</ol>
	<?php else: ?>
		<p>-</p>
	<?php endif ?>
</div>
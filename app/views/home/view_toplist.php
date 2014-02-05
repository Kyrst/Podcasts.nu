<h1>Topplista</h1>

<div>
	<div class="col-lg-8">
		<h2>Mest spelade</h2>

		<h3>Denna vecka</h3>
		<ol>
			<?php foreach ( $stats['most_played']['this_week'] as $episode ): ?>
				<li><?= $episode->title ?> (<?= $episode->num_listens ?>)</li>
			<?php endforeach ?>
		</ol>

		<h3>Denna månad</h3>
		<ol>
			<?php foreach ( $stats['most_played']['this_month'] as $episode ): ?>
				<li><?= $episode->title ?> (<?= $episode->num_listens ?>)</li>
			<?php endforeach ?>
		</ol>

		<h3>Totalt</h3>
		<ol>
			<?php foreach ( $stats['most_played']['total'] as $episode ): ?>
				<li><?= $episode->title ?> (<?= $episode->num_listens ?>)</li>
			<?php endforeach ?>
		</ol>
	</div>

	<div class="col-lg-4">
		<h2>Högst Betyg</h2>

		<h3>Avsnitt</h3>

		<ol>
			<?php foreach ( $stats['highest_score_episodes'] as $episode ): ?>
				<li><?= $episode->title ?> (<?= number_format($episode->avg_score, 1) ?>)</li>
			<?php endforeach ?>
		</ol>

		<h3>Poddar</h3>

		<ol>
			<?php foreach ( $stats['highest_score_podcasts'] as $podcast ): ?>
				<li><?= $podcast->name ?> (<?= number_format($podcast->avg_score, 1) ?>)</li>
			<?php endforeach ?>
		</ol>
	</div>
</div>
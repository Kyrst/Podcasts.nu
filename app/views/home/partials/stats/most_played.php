<h3>Denna vecka</h3>

<?php if ( count($most_played_this_week) ): ?>
	<ol>
		<?php foreach ( $most_played_this_week as $episode ): ?>
			<li><a href="/"><?= $episode->title ?></a> (<?= $episode->num_listens ?>)</li>
		<?php endforeach ?>
	</ol>
<?php else: ?>
	<p>-</p>
<?php endif ?>

<h3>Denna månad</h3>

<?php if ( count($most_played_this_week) ): ?>
	<ol>
		<?php foreach ( $most_played_this_month as $episode ): ?>
			<li><a href="/"><?= $episode->title ?></a> (<?= $episode->num_listens ?>)</li>
		<?php endforeach ?>
	</ol>
<?php else: ?>
	<p>-</p>
<?php endif ?>

<h3>Totalt</h3>

<?php if ( count($most_played_this_week) ): ?>
	<ol>
		<?php foreach ( $most_played_total as $episode ): ?>
			<li><a href="/"><?= $episode->title ?></a> (<?= $episode->num_listens ?>)</li>
		<?php endforeach ?>
	</ol>
<?php else: ?>
	<p>-</p>
<?php endif ?>
<h3>Denna vecka</h3>

<?php if ( count($most_commented_this_week) ): ?>
	<ol>
		<?php foreach ( $most_commented_this_week as $episode ): ?>
			<li><a href="<?= Episode::getLinkStatic($episode->podcast_slug, $episode->slug, 'avsnitt') ?>"><?= $episode->title ?></a> (<?= $episode->num_comments ?>)</li>
		<?php endforeach ?>
	</ol>
<?php else: ?>
	<p>-</p>
<?php endif ?>

<h3>Denna månad</h3>

<?php if ( count($most_commented_this_month) ): ?>
	<ol>
		<?php foreach ( $most_commented_this_month as $episode ): ?>
			<li><a href="/"><?= $episode->title ?></a> (<?= $episode->num_comments ?>)</li>
		<?php endforeach ?>
	</ol>
<?php else: ?>
	<p>-</p>
<?php endif ?>

<h3>Totalt</h3>

<?php if ( count($most_commented_total) ): ?>
	<ol>
		<?php foreach ( $most_commented_total as $episode ): ?>
			<li><a href="/"><?= $episode->title ?></a> (<?= $episode->num_comments ?>)</li>
		<?php endforeach ?>
	</ol>
<?php else: ?>
	<p>-</p>
<?php endif ?>
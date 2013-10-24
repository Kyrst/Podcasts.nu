<ul class="breadcrumb">
	<li><a href="<?= URL::route('home', array(), ''); ?>">Podcasts.nu</a></li>
	<li><a href="<?= URL::route('avsnitt', array(), ''); ?>">Avsnitt</a></li>
	<?php if ( $podcast ): ?><li><?= $podcast->name; ?></li><?php endif; ?>
</ul>

<h1>Avsnitt</h1>

<?php if ( $num_episodes > 0 ): ?>
	<div id="episodes">
		<?php foreach ( $episodes as $episode ): ?>
			<div class="episode">
				<h2><a href="<?= $episode->getLink('avsnitt'); ?>"><?= $episode->podcast->name, ' - ', $episode->title; ?></a></h2>

				<img src="<?= $episode->podcast->getImage('standard', false, true); ?>" width="250" height="" alt="...">

				<p><?= $episode->created_at; ?></p>
			</div>
		<?php endforeach; ?>
	</div>
<?php else: ?>
	<p>Inga avsnitt.</p>
<?php endif; ?>
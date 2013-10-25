<ul class="breadcrumb">
	<li><a href="<?= URL::route('home', array(), ''); ?>">Podcasts.nu</a></li>
	<li><a href="<?= URL::route('avsnitt', array(), ''); ?>">Avsnitt</a></li>
	<li><a href="<?= $episode->podcast->getLink('poddar'); ?>"><?= $episode->podcast->name; ?></a></li>
	<li><?= $episode->title; ?></li>
</ul>

<h1><?= $episode->title; ?></h1>

<p class="created"><?= $episode->created_at; ?></p>

<?php if ( $episode->haveMedia() ): ?>
	<?= $episode->printPlayButton(); ?>
<?php endif; ?>

<h2>Kommentarer</h2>
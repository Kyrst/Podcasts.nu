<ul class="breadcrumb">
	<li><a href="<?= URL::route('home') ?>">Podcasts.nu</a></li>
	<li><a href="<?= URL::route('poddsnacks') ?>">Poddsnack</a></li>
	<li><?= $podtalk->title ?></li>
</ul>

<h1><?= $podtalk->title ?></h1>

<?= $podtalk->get_image('small') ?>

<p><?= $podtalk->description ?></p>

<?= $podtalk->body ?>
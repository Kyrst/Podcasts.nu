<ul class="breadcrumb">
	<li><a href="<?= URL::route('home', array(), ''); ?>">Podcasts.nu</a></li>
	<li><a href="<?= URL::route('nyheter', array(), ''); ?>">Nyheter</a></li>
	<li><?= $news_item->title; ?></li>
</ul>

<h1><?= $news_item->title; ?></h1>

<?= $news_item->content; ?>
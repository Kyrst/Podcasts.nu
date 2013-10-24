<ul class="breadcrumb">
	<li><a href="<?= URL::route('home', array(), ''); ?>">Podcasts.nu</a></li>
	<li>Nyheter</li>
</ul>

<h1>Nyheter</h1>

<?php foreach ( $news_items as $news_item ): ?>
	<div class="row">
		<strong><?= $news_item->title; ?></strong> - <em><?= date('Y-m-d', strtotime($news_item->created_at)); ?></em>
		<?= Str::limit($news_item->content, 50); ?>
		<a href="<?= URL::to('nyheter/' . date('Y-m-d', strtotime($news_item->created_at)) . '/' . $news_item->slug); ?>">Läs mer...</a>
	</div>
<?php endforeach; ?>
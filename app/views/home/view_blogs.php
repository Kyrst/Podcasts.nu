<ul class="breadcrumb">
    <li><a href="<?= URL::route('home', array(), ''); ?>">Podcasts.nu</a></li>
    <li>Bloggar</li>
</ul>

<!-- Presentation av bloggare. Bild och länk -->
<h1>Bloggar</h1>

<?php foreach ( $blogs as $blog ): ?>
	<div class="artist col-lg-4 col-md-4 col-sm-4 col-xs-4">
		<div class="thumbnail">
			<div class="caption">
				<h3><a href="<?= $blog->getLink() ?>"><?= $blog->name ?></a></h3>
				<p>Info om bloggen</p>
			</div>
		</div>
	</div>
<?php endforeach ?>

<div class="clear"></div>

<!-- En container per inlägg -->
<div class="container">
    <h1>Senaste inläggen</h1>

	<?php foreach ( $latest_blog_items as $blog_item ): ?>
		<div class="media">
			<div class="media-body">
				<h4 class="media-heading"><a href="<?= $blog_item->getLink() ?>"><?= $blog_item->title ?> - <?= $blog_item->blog->name ?></a></h4>
				<p class="added"><?= $blog_item->created_at ?></p>
			</div>
		</div>
    <?php endforeach ?>
</div>
<!--  -->


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
    <h1>Senaste 10 inläggen nedanför</h1>
    <h1>...</h1>
    <h1>...</h1>
    <h1>...</h1>
    <!-- Miniatyr av bloggaren, med namn -->
    <div class="media">
        <img class="media-object" src="">
        </a>
        <div class="media-body">
            <h4 class="media-heading">Daniel Myrberg</h4>
        </div>
    </div>
    <!-- -->
</div>
<!--  -->


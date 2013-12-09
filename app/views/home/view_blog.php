<ul class="breadcrumb">
    <li><a href="<?= URL::route('home', array(), ''); ?>">Podcasts.nu</a></li>
    <li><a href="<?= URL::route('bloggar', array(), ''); ?>">Bloggar</a></li>
    <li><?= $blog->name ?></li>
</ul>

<div class="artist col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="thumbnail">
        <div class="caption">
            <h3><?= $blog->name ?></h3>
            <p><?= nl2br($blog->description) ?></p>
        </div>
    </div>
</div>

<?php foreach ( $blog->items as $blog_item ): ?>
	<div class="panel panel-danger" style="margin-top:20px">
		<div class="panel-heading">
			<h3 class="panel-title"><a href="<?= $blog_item->getLink() ?>"><?= $blog_item->title ?></a></h3>
		</div>
		<div class="panel-body">
			<div id="comments_container">
			</div>

			<p><?= nl2br($blog_item->body) ?></p>
		</div>
	</div>
<?php endforeach ?>
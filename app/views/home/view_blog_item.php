<ul class="breadcrumb">
    <li><a href="<?= URL::route('home', array(), ''); ?>">Podcasts.nu</a></li>
    <li><a href="<?= URL::route('bloggar', array(), ''); ?>">Bloggar</a></li>
    <li><a href="<?= $blog_item->blog->getLink() ?>"><?= $blog_item->blog->name ?></a></li>
    <li><?= $blog_item->title ?></li>
</ul>

<div class="panel panel-danger" style="margin-top:20px">
	<div class="panel-heading">
		<h1 class="panel-title"><a href="<?= $blog_item->getLink() ?>"><?= $blog_item->title ?></a></h1>
	</div>

	<div class="panel-body">
		<div id="comments_container">
		</div>

		<p><?= nl2br($blog_item->body) ?></p>
	</div>
</div>
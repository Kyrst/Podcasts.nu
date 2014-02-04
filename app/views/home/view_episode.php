<ul class="breadcrumb">
	<li><a href="<?= URL::route('home', array(), ''); ?>">Podcasts.nu</a></li>
	<li><a href="<?= URL::route('avsnitt', array(), ''); ?>">Avsnitt</a></li>
	<li><a href="<?= $episode->podcast->getLink('poddar'); ?>"><?= $episode->podcast->name; ?></a></li>
	<li><?= $episode->title; ?></li>
</ul>
<h1><?= $episode->podcast->name; ?></h1>
<h1><?= $episode->title; ?></h1>


<p class="created"><?= $episode->created_at; ?></p>


<?php if ( $episode->haveMedia() ): ?>
	<?= $episode->printPlayButton(); ?>
<?php endif; ?>

<div class="panel panel-danger" style="margin-top:20px">
	<div class="panel-heading">
		<h3 class="panel-title">Kommentarer</h3>
	</div>
	<div class="panel-body">
		<div id="comments_container">
			<?= $comments_html ?>
		</div>

		<h3>Kommentera</h3>

		<?php if ( $user ): ?>
			<form action="<?= Request::url() ?>" method="post" id="comment_form">
				<div class="form-group">
					<label>Namn</label>
					<input type="text" id="name" class="form-control" value="<?= $user->getDisplayName() ?>" disabled>
				</div>

				<div class="form-group">
					<textarea name="comment" id="comment" class="form-control"></textarea>
				</div>

				<div class="form-group">
					<button type="submit" id="comment_button" class="btn btn-primary">Kommentera</button>
				</div>
			</form>
		<?php else: ?>
			<p>Du måste logga in för att kommentera.</p>
		<?php endif ?>
	</div>
</div>
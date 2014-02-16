<ul class="breadcrumb">
	<li><a href="<?= URL::route('home', array(), ''); ?>">Podcasts.nu</a></li>
	<li><a href="<?= URL::route('avsnitt', array(), ''); ?>">Avsnitt</a></li>
	<li><a href="<?= $episode->podcast->getLink('poddar'); ?>"><?= $episode->podcast->name; ?></a></li>
	<li><?= $episode->title; ?></li>
</ul>
<?php if ( $episode->haveMedia() ): ?>
    <?= $episode->printPlayButton(); ?>
<?php endif; ?>
<h1><?= $episode->title; ?> (<?= $episode->podcast->name ?>)</h1>

<p class="pub-date">(<?=date('Y-m-d H:i:s', $episode->pub_date) ?>)</p>

<div style="margin-bottom:15px">
	<?= $episode->print_rater() ?>
</div>


<?php if ( $user !== NULL ): ?>
    <?php $episode_status = $user->get_episode_status($episode->id) ?>

    <?php if ( $episode_status === '' ): ?>
        <a href="<?= $episode->getLink() ?>" class="label label-danger">Lyssna nu</a>
    <?php else: ?>
        <?php if ( $episode_status === 'Lyssnad' ): ?>
            <span class="label label-success">Lyssnad</span>
        <?php elseif ( $episode_status === 'Påbörjad' ): ?>
            <span class="label label-warning">Påbörjad</span>
        <?php endif ?>
    <?php endif ?>
<?php endif ?>

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
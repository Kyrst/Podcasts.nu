<style>
	.comment
	{
		margin-bottom: 12px;
		padding-bottom: 12px;
		border-bottom: 1px solid #CCC;
	}

	.comment.last
	{
		margin-bottom: 0;
		padding-bottom: 0;
		border-bottom: none;
	}

	.comment-author
	{
		float: left;
		width: 160px;
		margin-right: 15px;
	}

	.comment-content
	{
		float: left;
		width: auto;
	}

	.comment-content .time
	{
		font-size: .8em;
	}
</style>

<?php if ( $num_comments > 0 ): ?>
	<?php foreach ( $comments as $i => $comment ): ?>
		<div id="comment_<?= $comment->id ?>" class="comment clearfix<?php if ( $i === ($num_comments - 1) ): ?> last<?php endif ?>">
			<div class="comment-author">
				<?= $comment->user->getDisplayName() ?>

				<?= $comment->user->get_avatar_image('avsnitt_kommentar') ?>
			</div>

			<div class="comment-content">
				<span class="time"><?= $comment->created_at ?></span>

				<p><?= $comment->comment ?></p>
			</div>
		</div>
	<?php endforeach ?>
<?php else: ?>
	<p>Inga kommentarer.</p>
<?php endif ?>
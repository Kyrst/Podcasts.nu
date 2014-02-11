<?php foreach ( $episodes as $episode ): ?>
	<div class="media">
		<a href="/" class="pull-left">
			<img src="<?= $episode->podcast->getImage('standard', false, true) ?>" width="60" height="" alt="...">
		</a>

		<div class="media-body">
			<h3 class="episode-head"><?php if ( $_podcast === NULL ): ?><a href="<?= $episode->podcast->getLink('poddar') ?>"><?= $episode->podcast->name ?></a> - <?php endif ?><a href="<?= $episode->getLink('avsnitt'); ?>"><?= $episode->title; ?></a> </h3>
			<p class="pub-date">(<?=date('Y-m-d H:i:s', $episode->pub_date) ?>)</p>
			<div class="clear"></div>

			<?php if ( $user !== NULL ): ?>
				<p class="episode-status"><?= $user->get_episode_status($episode->id) ?></p>
			<?php endif ?>

			<div class="rater"><?= $episode->print_rater() ?></div>
		</div>
	</div>
<?php endforeach; ?>
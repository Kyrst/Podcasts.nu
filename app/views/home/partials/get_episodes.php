<?php foreach ( $episodes as $episode ): ?>
	<div class="media">
		<?php if ( $episode->podcast !== NULL ): ?>
			<a href="/" class="pull-left">
				<img src="<?= $episode->podcast->getImage('standard', false, true) ?>" width="60" height="" alt="...">
			</a>

			<div class="media-body">
				<h3 class="episode-head"><?php if ( $_podcast === NULL ): ?><a href="<?= $episode->podcast->getLink('poddar') ?>"><?= $episode->podcast->name ?></a> - <?php endif ?><a href="<?= $episode->getLink('avsnitt'); ?>"><?= $episode->title; ?></a> </h3>
				<p class="pub-date">(<?=date('Y-m-d H:i:s', $episode->pub_date) ?>)</p>
                <div class="clear"></div>

                <?php if ( $user !== NULL ): ?>
                	<?php $episode_status = $user->get_episode_status($episode->episode_id) ?>

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

                <div class="rater"><?= $episode->print_rater() ?></div>
            </div>
		<?php else: ?>
			Avsnittet saknar podd.
		<?php endif ?>
	</div>
<?php endforeach; ?>
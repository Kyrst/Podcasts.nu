<?php if ( $num_episodes > 0 ): ?>
	<div id="episodes">
		<?php foreach ( $episodes as $episode ): ?>

			<div class="media">
				<?php if ( $episode->podcast !== NULL ): ?>

					<div class="media-body">
						<h3 class="episode-head"><?php endif ?><a href="<?= $episode->getLink('avsnitt'); ?>"><?= $episode->title; ?></a></h3>
						<p class="pub-date">(<?=date('Y-m-d H:i:s', $episode->pub_date) ?>)</p>
						<div class="clear"></div>

						<?= $episode->print_rater() ?>

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
					</div>
					<?php if ( $user !== NULL && $user->is_admin() ): ?>
						<div class="btn-group" style="margin-top:8px">
							<button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
								Admin <span class="caret"></span>
							</button>
							<ul class="dropdown-menu" role="menu">
								<li><a href="<?= URL::to('admin/avsnitt/' . $episode->id) ?>">Ändra</a></li>
								<li><a href="<?= URL::to('admin/avsnitt/ta-bort/' . $episode->id) ?>">Ta bort</a></li>
							</ul>
						</div>
					<?php endif ?>
			</div>

		<?php endforeach; ?>
	</div>
<?php else: ?>
	<p>Inga avsnitt.</p>
<?php endif; ?>
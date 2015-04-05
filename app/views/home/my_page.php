<div class="row">
	<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
		<h1><?= $user->first_name; ?></h1>

		<?= $user->get_avatar_image('installningar') ?>

		<div class="clear"></div>

		<a href="<?= URL::route('installningar') ?>" class="btn btn-primary btn-xs" style="margin-top:4px">Inställningar</a>
	</div>

	<div class="my_page col-lg-10 col-md-10 col-sm-10 col-xs-12">
		<!-- Historik -->
		<!--<h2>Historik</h2>

		<?//php if ( count($history) > 0 ): ?>
			<table class="table">
				<?//php foreach ( $history as $history_item ): ?>
					<tr>
						<td style="width:150px"><p class="table-p"><?= date('Y-m-d H:i', $history_item['timestamp']) ?></p></td>
						<td><p class="table-p"><?= $history_item['message'] ?></p></td>
					</tr>
				<?//php endforeach ?>
			</table>
		<?//php else: ?>
			<p class="table-p">Ingen historik.</p>
		<?//php endif ?>-->

		<!-- Ej lyssnat klart -->
		<?php if ( $num_user_listens > 0 ): ?>
			<div id="not_done_container" class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<h2>Ej lyssnat klart</h2>

				<table id="not_done_table" class="table col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<tr>
						<th></th>
						<th>Avsnitt</th>
						<th>Position</th>
						<th></th>
					</tr>

					<?php foreach ( $user_listens as $user_listen ): ?>
						<tr id="not_done_row_<?= $user_listen->episode_id ?>" class="not-done-row">
							<td>
								<?php if ( $user_listen->episode->haveMedia() ): ?>
									<?= $user_listen->episode->printPlayButton() ?>
								<?php endif ?>
							</td>
							<td><p class="table-p"</p><a href="<?= $user_listen->episode->getLink() ?>"><?= $user_listen->episode->getTitle() ?></a></p></td>
							<td><p class="table-p"><span class="current-position-<?= $user_listen->episode->id ?>"><?= User_Listen::format_seconds($user_listen->current_position) ?></span> / <?= User_Listen::format_seconds($user_listen->episode->duration) ?></p></td>
							<td>
								<a href="javascript:" data-episode_id="<?= $user_listen->episode->id ?>" class="btn btn-default btn-sm mark-as-done">Markera som färdiglyssnad</a>
							</td>
						</tr>
					<?php endforeach ?>
				</table>
			</div>
		<?php endif ?>

		<!-- Prenumerationer -->
		<h2>Prenumerationer</h2>

		<?php if ( $user->podcasts->count() > 0 ): ?>
			<table id="my_subscriptions" class="panel panel-default">
				<tr>
					<th><p class="table-heading">Podcast</p></th>
					<th><p class="table-heading">Senaste avsnittet</p></th>
					<th><p class="table-heading">Senaste lyssnade avsnitt</p></th>
					<th><p class="table-heading">Lyssnade avsnitt</p></th>
					<th></th>
				</tr>

				<?php foreach ( $user->podcasts as $podcast ): ?>
					<?php
					$latest_episode = $podcast->get_latest_epsiode();
					//$latest_listened_episode = $user->get_latest_listened_episode($podcast->id);
					?>

					<tr>
						<td><p class="table-p"><a href="<?= $podcast->getLink('avsnitt') ?>"><?= $podcast->name ?></a></p></td>
						<td><p class="table-p"><?= $latest_episode !== NULL ? $latest_episode->title : '/' ?></p></td>
						<td><p class="table-p"><?//= '[kommer]' //$latest_listened_episode !== NULL ? $latest_listened_episode->title : '/' ?></p></td>
						<!--<td><p class="table-p"><?//= $podcast->episodes->count() > 0 ? $podcast->get_num_listens($user->id) . ' av ' . $podcast->episodes->count() : '-' ?></p></td>-->
						<td><p class="table-p"><a href="javascript:" data-id="<?= $podcast->id ?>" class="stop-subscribe btn btn-default btn-sm">Sluta prenumerera</a></p></td>
					</tr>
				<?php endforeach ?>
			</table>
		<?php else: ?>
			<p>Inga prenumerationer.</p>
		<?php endif ?>
	</div>
</div>
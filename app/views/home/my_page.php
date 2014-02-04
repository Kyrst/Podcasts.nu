<div class="row">
	<div class="col-lg-2">
		<h1><?= $user->first_name; ?></h1>

		<img src="<?= $user->getAvatar(); ?>" alt="" class="img-rounded">
	</div>

	<div class="col-md-10">
		<!-- Historik -->
		<h2>Historik</h2>

		<?php if ( count($history) > 0 ): ?>
			<table class="table">
				<?php foreach ( $history as $history_item ): ?>
					<tr>
						<td style="width:150px"><?= date('Y-m-d H:i', $history_item['timestamp']) ?></td>
						<td><?= $history_item['message'] ?></td>
					</tr>
				<?php endforeach ?>
			</table>
		<?php else: ?>
			<p>Ingen historik.</p>
		<?php endif ?>

		<!-- Ej lyssnat klart -->
		<?php if ( $num_user_listens > 0 ): ?>
			<h2>Ej lyssnat klart</h2>

			<table class="table">
				<?php foreach ( $user_listens as $user_listen ): ?>
					<tr>
						<td><?= var_dump($user_listen->title) ?></td>
					</tr>
				<?php endforeach ?>
			</table>
		<?php endif ?>

		<!-- Prenumerationer -->
		<h2>Prenumerationer</h2>

		<?php if ( $user->podcasts->count() > 0 ): ?>
			<table id="my_subscriptions" class="table">
				<tr>
					<th>Podcast</th>
					<th>Senaste avsnittet</th>
					<th>Senaste avsnittet du lyssnade på</th>
					<th>Lyssnade avsnitt</th>
					<th></th>
				</tr>

				<?php foreach ( $user->podcasts as $podcast ): ?>
					<?php
					$latest_episode = $podcast->get_latest_epsiode();
					//$latest_listened_episode = $user->get_latest_listened_episode($podcast->id);
					?>

					<tr>
						<td><a href="<?= $podcast->getLink('avsnitt') ?>"><?= $podcast->name ?></a></td>
						<td><?= $latest_episode !== NULL ? $latest_episode->title : '/' ?></td>
						<td><?= '[kommer]' //$latest_listened_episode !== NULL ? $latest_listened_episode->title : '/' ?></td>
						<td><?= $podcast->episodes->count() > 0 ? $podcast->get_num_listens($user->id) . ' av ' . $podcast->episodes->count() : '-' ?></td>
						<td><a href="javascript:" data-id="<?= $podcast->id ?>" class="stop-subscribe btn btn-default btn-sm">Sluta prenumerera</a></td>
					</tr>
				<?php endforeach ?>
			</table>
		<?php else: ?>
			<p>Inga prenumerationer.</p>
		<?php endif ?>
	</div>
</div>
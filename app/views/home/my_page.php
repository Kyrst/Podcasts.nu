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
					/*$latest_episode = $podcast->get_latest_epsiode();
					$latest_listened_episode = $user->get_latest_listened_episode($podcast->id);
					?>

					<tr>
						<td><a href="<?= $podcast->getLink() ?>"><?= $podcast->name ?></a></td>
						<td><?= $latest_episode !== NULL ? $latest_episode->title : '/' ?></td>
						<td><?= $latest_listened_episode !== NULL ? $latest_listened_episode->title : '/' ?></td>
						<td><?= $podcast->episodes->count() > 0 ? round($podcast->get_num_listens($user->id) / $podcast->episodes->count() * 100) . '%' : '-' ?></td>
						<td><a href="javascript:" data-id="<?= $podcast->id ?>" class="stop-subscribe">Sluta prenumerera</a></td>
					</tr>*/ ?>
				<?php endforeach ?>
			</table>
		<?php else: ?>
			<p>Inga prenumerationer.</p>
		<?php endif ?>
	</div>
</div>
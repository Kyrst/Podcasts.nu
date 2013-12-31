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

		<p>Inga prenumerationer.</p>
	</div>
</div>
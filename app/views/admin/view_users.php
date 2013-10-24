<h1>Användare</h1>

<table class="table table-bordered">
	<tr>
		<th>ID</th>
	</tr>

	<?php foreach ( $users as $user ): ?>
		<tr>
			<td><?= $user->id; ?></td>
		</tr>
	<?php endforeach; ?>
</table>

	<?php if (isset($users) && count((array)$users)) { ?>
	<table class="table table-striped" id="data-table">
		<thead>
			<tr>
				<th>User</th>
				<th>Commits</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($users as $user): ?>
			<tr>
				<td><?= $user->name ?></td>
                <td><?= $user->commits ?></td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	<?php } else { ?>
		<p class="lead">No Commits within this period</p>
	<?php } ?>

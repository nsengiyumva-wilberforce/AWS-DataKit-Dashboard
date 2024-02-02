	<?php if (isset($data->data_rows)) { ?>
	<table class="table table-striped" id="data-table">
		<thead>
			<tr style="font-size: small;">
				<th rowspan="2">Location</th>
				<th rowspan="2">Entries</th>
				<?php foreach ($data->main_header as $th): ?>
				<th style="min-width: 150px;" colspan="<?= $th->colspan ?>"><?= $th->title ?></th>
				<?php endforeach; ?>
			</tr>
			<tr style="font-size: small;">
				<!-- <th>Location</th>
				<th>Entries</th> -->
				<?php foreach ($data->sub_header as $th): ?>
				<th style="max-width: 120px; min-width: 50px;" ><?= $th ?></th>
				<?php endforeach; ?>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($data->data_rows as $row): ?>
		<tr>
			<td><?= $row->name ?></td>
			<td><?= number_format($row->entries) ?></td>
			<?php foreach ($row->aggregate as $qn): ?>
				<?php foreach ($qn as $ans): ?>
					<td><?= number_format($ans) ?></td>
				<?php endforeach; ?>
			<?php endforeach; ?>
		</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
	<?php } else { ?>
		<p class="lead">No Entries within this period</p>
	<?php } ?>

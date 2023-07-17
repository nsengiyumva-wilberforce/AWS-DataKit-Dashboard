  <?php // echo json_encode($entries); ?>



	<!-- <table class="table" id="data-table" style="overflow-x:auto;"> -->
	<?php if (isset($entries->headers)) { ?>
	<table class="table table-striped" id="data-table">
		<thead>
			<tr>
				<th>Entry ID</th>
				<?php $cols = 1; ?>
				<?php foreach ($entries->headers as $theader): ?>
				<th style="min-width: 150px;"><?= $theader ?></th>
				<?php $cols++; ?>
				<?php endforeach; ?>
			</tr>
		</thead>
		<tbody>
			<?php $keys = array_keys((array)$entries->headers); ?>
			<?php foreach ($entries->entries as $entry): ?>
			<?php if($entries->entry_data=="baseline"){ ?>
			<?php $response = (array) $entry->responses[0];?>
			<tr>
				<td style="white-space: nowrap;"><a href="<?= base_url('entry/'.$entry->response_id) ?>"><?= $entry->response_id ?></a></td>
				<?php if (isset($entry->responses)) { ?>
					<?php foreach ($keys as $key): ?>
					<td style="white-space: nowrap;">
						<?php if (isset($response[$key])): ?>
							<?php if (gettype($response[$key]) == 'array') { ?>
								<?=  implode(', ', $response[$key]) ?>
							<?php } else { ?>
								<?= $response[$key] ?>
							<?php } ?>
						<?php endif; ?>
					</td>
					<?php endforeach; ?>
				<?php } else { ?>
					<?php foreach ($keys as $key): ?>
						<td></td>
					<?php endforeach; ?>
				<?php } ?>
			</tr>
			<?php } else { ?>
			<?php foreach($entry->responses as $followup){
			$response = (array)$followup[0];
			?>
			                        <tr>
                                <td style="white-space: nowrap;"><a href="<?= base_url('entry/'.$entry->response_id) ?>"><?= $entry->response_id ?></a></td>
                                <?php if (isset($entry->responses)) { ?>
                                        <?php foreach ($keys as $key): ?>
                                        <td style="white-space: nowrap;">
                                                <?php if (isset($response[$key])): ?>
                                                        <?php if (gettype($response[$key]) == 'array') { ?>
                                                                <?=  implode(', ', $response[$key]) ?>
                                                        <?php } else { ?>
                                                                <?= $response[$key] ?>
                                                        <?php } ?>
                                                <?php endif; ?>
                                        </td>
                                        <?php endforeach; ?>
                                <?php } else { ?>
                                        <?php foreach ($keys as $key): ?>
                                                <td></td>
                                        <?php endforeach; ?>
                                <?php } ?>
                        </tr>
			<?php } ?>
			<?php } ?>
			<?php endforeach; ?>
		</tbody>
	</table>
	<?php } else { ?>
		<p class="lead">No Entries within this period</p>
	<?php } ?>

<?php // echo json_encode($entries); ?>



<!-- <table class="table" id="data-table" style="overflow-x:auto;"> -->
<?php if (isset($entries->headers)) { ?>
	<table class="table table-striped" id="data-table">
		<thead>
			<tr>
				<th>Entry ID</th>
				<?php $cols = 1; ?>
				<?php foreach ($entries->headers as $theader):
					if ($cols == 3) {
						 ?>
						<th style="min-width: 150px;">
							<?= "Region" ?>
						</th>
						<?php
						$cols++;
					}
					?>
					<th style="min-width: 150px;">
						<?= $theader ?>
					</th>
					<?php $cols++; ?>
				<?php endforeach; ?>
			</tr>
		</thead>
		<tbody>
			<?php $keys = array_keys((array) $entries->headers); ?>
			<?php foreach ($entries->entries as $entry): ?>
				<?php $response = (array) $entry->responses; ?>
				<tr>
					<td style="white-space: nowrap;"><a href="<?= base_url('entry/' . $entry->response_id) ?>">
							<?= $entry->response_id ?>
						</a></td>
					<?php if (isset($entry->responses)) { ?>
						<?php $cols = 1; ?>
						<?php foreach ($keys as $key): 
							if ($cols == 3) {
								?>
								<td style="white-space: nowrap;">
									<?= $entries->region ?>
								</td>
								<?php
								$cols++;
							}
							?>
							<td style="white-space: nowrap;">
								<?php if (isset($response[$key])): ?>
									<?php if (gettype($response[$key]) == 'array') { ?>
										<?= implode(', ', $response[$key]) ?>
									<?php } else { ?>
										<?= $response[$key] ?>
									<?php } ?>
								<?php endif; ?>
							</td>
							<?php $cols++; ?>
						<?php endforeach; ?>
					<?php } else { ?>
						<?php foreach ($keys as $key): ?>
							<td></td>
						<?php endforeach; ?>
					<?php } ?>
				</tr>


			<?php endforeach; ?>
		</tbody>
	</table>
<?php } else { ?>
	<p class="lead">No Entries within this period</p>
<?php } ?>
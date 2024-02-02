	<?php if (isset($entries)) { ?>
		<table id="datatable-entries" class="table">
				<thead>
					<tr>
						<th scope="col">Title</th>
						<th scope="col">Location</th>
						<th scope="col">Created By</th>
						<th scope="col">Followed Up By</th>
						<th scope="col">Last Modified</th>
						<th scope="col">Action</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($entries as $entry): ?>
				<?php if (!isset($entry->responses[0]->rejection_status)) { ?>
					<tr id="<?= 'entry-' . $entry->response_id ?>">
					<?php } else {
					if (($entry->responses[0]->rejection_status) == "rejected") {
						?>

						<tr id="<?= 'entry-' . $entry->response_id ?>" class="table-danger">
						<?php } else { ?>
						<tr id="<?= 'entry-' . $entry->response_id ?>" class="table-success">
						<?php } ?>

					<?php } ?>
						<td><a href="<?= base_url('entry/'.$entry->response_id) ?>"><i data-feather="file-text"></i> <?= $entry->title ?></a></td>
						<td>
							<?php if (isset($entry->village)) {
								echo $entry->village;
							} elseif (isset($entry->parish)) {
								echo $entry->parish;
							} elseif (isset($entry->sub_county)) {
								echo $entry->sub_county;
							} echo ', '.$entry->district; ?>
						</td>
						<td><?= $entry->creator_id ?></td>
						<td><?= $entry->last_follower??"" ?></td>
					<td data-order="<?= strtotime($entry->updated_at ?? $entry->created_at) ?>">
							<span data-toggle="Last modified on <?= date('M j, Y', strtotime($entry->updated_at ?? $entry->created_at)) ?>" title="Last modified on <?= date('M j, Y', strtotime($entry->updated_at ?? $entry->created_at)) ?>"><?= date('M j, Y', strtotime($entry->updated_at ?? $entry->created_at)) ?></span>
						</td>

						<td>
							<nav class="nav d-inline-flex">
								<a class="nav-link py-0" data-toggle="View" title="View" href="<?= base_url('entry/'.$entry->response_id) ?>"><i data-feather="eye"></i></a>

								<!-- <a class="nav-link py-0" data-toggle="Edit" title="Edit" href="<?= base_url('entry/'.$entry->response_id.'/edit') ?>"><i data-feather="edit-2"></i></a> -->
								<?php if ($this->session->permissions->delete_response): ?>
								<a class="nav-link py-0 confirm-tr-delete" data-toggle="Delete" title="Delete" href="<?= base_url('entry/'.$entry->response_id.'/delete') ?>"><i data-feather="trash"></i></a>
								<?php endif; ?>
							</nav>
						</td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
	<?php } else { ?>
		<p class="lead">No Entries from this Region</p>
	<?php } ?>

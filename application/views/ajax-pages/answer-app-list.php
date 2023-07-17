<div class="answer-values">
	<div class="form-group">
		<select name="answer_values[]" class="form-control">
			<option value="">----</option>
			<?php foreach ($app_list as $list): ?>
			<option value="<?= $list ?>"><?= ucfirst(str_replace('_', ' ', str_replace('app_', '', $list))) ?></option>
			<?php endforeach; ?>
		</select>
	</div>
</div>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
	<h1 class="h2"><?= $form->title ?></h1>
	<?php if (count($form->question_list)): ?>
	<div class="btn-toolbar mb-2 mb-md-0">
		<a href="<?= base_url('form/'.$form->form_id.'/edit') ?>" class="btn btn-sm btn-outline-secondary">
			<i data-feather="calendar"></i> Edit Form
		</a>
	</div>
	<?php endif; ?>
</div>

<div class="mb-5">

	<?php $i = 1; ?>
	<?php foreach ($form->question_list as $question): ?>
		<?php $question->question_number = $i; ?>
		<?php $question->form_id = $form->form_id; ?>
		<?php $this->load->view('ajax-pages/question-card-view', $question); ?>
		<?php $i++; ?>
	<?php endforeach; ?>

	<?php if (!count($form->question_list)): ?>
	<div class="text-center">		
		<p class="lead">This form has no questions.</p>
		<a href="<?= base_url('form/'.$form->form_id.'/edit') ?>" class="btn btn-lg btn-primary">
			Get Started
		</a>
	</div>
	<?php endif; ?>

</div>












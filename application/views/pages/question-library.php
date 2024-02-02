<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
	<h1 class="h2">Question Library</h1>
	<?php if (count($question_list)): ?>
	<div class="btn-toolbar mb-2 mb-md-0">
		<!-- <a href="<?= base_url('ajax/new-question') ?>" class="btn btn-sm btn-outline-secondary">
			<i data-feather="calendar"></i> Add Question
		</a> -->
	</div>
	<?php endif; ?>
</div>

<div class="mb-5">
    <div id="form-builder">
        <?php $i = 1; ?>
        <?php foreach ($question_list as $question): ?>
            <?php $question->question_number = $i; ?>
            <?php $this->load->view('ajax-pages/library-question-card-view', $question); ?>
            <?php $i++; ?>
        <?php endforeach; ?>
    </div>

	<?php if (!count($question_list)) { ?>
	<div class="text-center">		
		<p class="lead">Library has no questions.</p>
		<a href="<?= base_url('ajax/new-question') ?>" class="btn btn-lg btn-primary">
			Get Started
		</a>
	</div>
	<?php } else { ?>
        <div id="library-question-wrapper">

        </div>
        <a href="<?= base_url('ajax/new_library_question') ?>" id="new-library-question" class="btn btn-primary my-4 ajax-link" data-target="#library-question-wrapper" data-action="add-element">New Question</a>
	<?php } ?>
<!-- add-element, remove-element replace-element -->
</div>












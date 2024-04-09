<div class="form-group">
        <label>Answer to attach condition to</label>
        <select name="answer" id="select-answer" class="form-control" required>
            <option value=""></option>
            <?php foreach($question->answer_values as $answer): ?>
            <option value="<?= $answer ?>"><?= $answer ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <label>Action</label>
        <div>
            <div class="form-check form-check-inline">
                <input class="form-check-input logic-action" type="radio" name="action" id="inlineRadio1" value="hide">
                <label class="form-check-label" for="inlineRadio1">Hide</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input logic-action" type="radio" name="action" id="inlineRadio2" value="prefill">
                <label class="form-check-label" for="inlineRadio2">Prefill</label>
            </div>
        </div>
    </div>



    <div class="option-qns hide-option">
        <div class="form-group">
            <label>Select Questions to Hide</label>
            <select multiple class="form-control" name="question_ids[]" ids="select-questions" id="tokenfield-forms">
            <option></option>
            <?php foreach($question_list as $qn): ?>
            <option value="<?= $qn->question_id ?>"><?= $qn->question ?></option>
            <?php endforeach; ?>
            </select>
        </div>
    </div>

    <div class="option-qns prefill-option">
        <div class="row prefill-pair" id="default-prefill-pair">
            <div class="form-group col-6">
                <label>Select Questions to Prefill</label>
                <select class="form-control" name="prefill_question_ids[]">
                <option></option>
                <?php foreach($question_list as $qn): ?>
                <option value="<?= $qn->question_id ?>"><?= $qn->question ?></option>
                <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group col-4">
                <label>Answer</label>
                <select name="prefill_question_answers[]" class="form-control">
                    <option value=""></option>
                </select>
            </div>    
        </div>
        <div class="prefill-list-wrapper"></div>
        <a href="javascript:;" class="btn btn-success btn-sm" id="add-prefill-question-answer">Add Prefill</a>
    </div>


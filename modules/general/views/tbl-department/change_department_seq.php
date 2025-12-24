<?php

use app\components\ActiveForm;
use kartik\sortable\Sortable;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

$form = ActiveForm::begin([
            'id' => 'department-sequence-form',
            'validateOnBlur' => false,
            'validateOnChange' => false,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
            'action' => Url::to(['change-department-seq']),
        ]);

$this->title = Yii::t('app', 'Available Departments');
?>

<?php echo $form->errorSummary($model); ?>

<div class="row">
    <div class="col-sm-6">
        <div class="panel panel-default panel-main">
            <div class="panel-heading"><?= $this->title ?></div>
            <div class="sequence-well sequence-box-well">
                <input type="text" id="search-available" placeholder="Search available departments" class="form-control mb-2 sequence-form-control">
                <?=
                Sortable::widget([
                    'type' => Sortable::TYPE_LIST,
                    'items' => array_map(function($department) {
                                return ['content' => Html::encode($department->department), 'options' => ['data-id' => $department->department_id]];
                            }, $available),
                    'options' => ['id' => 'available-list', 'class' => 'list-group border-none'],
                    'itemOptions' => ['class' => 'list-group-item'],
                    'pluginOptions' => [
                        'connectWith' => '#selected-list',
                    ],
                ]);
                ?>
            </div>
        </div>
    </div>

    <div class="col-sm-6">
        <div class="panel panel-default panel-main">
            <div class="panel-heading"><?= Yii::t('app', 'Selected Departments') ?></div>
            <div class="sequence-well sequence-box-well">
                <input type="text" id="search-selected" placeholder="Search selected departments" class="form-control mb-2 sequence-form-control">
                <?=
                Sortable::widget([
                    'type' => Sortable::TYPE_LIST,
                    'items' => array_map(function($department) {
                                $input = Html::hiddenInput('seq_no[' . $department->department_id . ']', $department->seq_no, [
                                            'class' => 'seq-no-input qty-validate',
                                            'data-id' => $department->department_id,
                                ]);
                                $seqDisplay = Html::textInput('seq_display[' . $department->department_id . ']', $department->seq_no, [
                                            'class' => 'seq-display-input qty-validate',
                                            'data-id' => $department->department_id,
                                ]);
                                return ['content' => $input . $seqDisplay . Html::encode($department->department), 'options' => ['data-id' => $department->department_id]];
                            }, $selected),
                    'options' => ['id' => 'selected-list', 'class' => 'list-group border-none'],
                    'itemOptions' => ['class' => 'list-group-item'],
                    'pluginOptions' => [
                        'connectWith' => '#available-list',
                    ],
                ]);
                ?>
            </div>
        </div>
    </div>

    <div class="col-sm-12 padding_top_20 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
        <div class="form-group">
            <?= Yii::$app->controls->save(Yii::$app->label->button('create'), $model); ?>
            <?= Yii::$app->controls->cancel($model); ?>
        </div>
    </div>
</div>

<?php ActiveForm::end(); ?>

<?php
$script = "
$('#available-list, #selected-list').sortable({
    connectWith: '.list-group',
    placeholder: 'ui-state-highlight',
    update: function(event, ui) {
        var movedItem = ui.item;
        var targetList = $(this);
        if (targetList.attr('id') === 'available-list') {
            movedItem.find('.seq-no-input').remove();
            movedItem.find('.seq-display-input').remove();
        } else if (targetList.attr('id') === 'selected-list') {
            movedItem.find('.seq-no-input').remove();
            movedItem.find('.seq-display-input').remove();
            var deptId = movedItem.data('id');
            var input = $('<input>', {
                type: 'hidden',
                name: 'seq_no[' + deptId + ']',
                class: 'seq-no-input',
                'data-id': deptId,
                value: ''
            });
            var seqDisplay = $('<input>', {
                type: 'text',
                name: 'seq_display[' + deptId + ']',
                class: 'seq-display-input',
                'data-id': deptId,
                value: ''
            });
            movedItem.prepend(input).prepend(seqDisplay);
            var item = movedItem;
            var index = item.index();
            var newSeq;
            if (index === 0) {
                newSeq = 1;
            } else {
                var prevSeq = parseInt(item.prev().find('.seq-no-input').val()) || 0;
                newSeq = prevSeq + 1;
            }
            item.find('.seq-no-input').val(newSeq);
            item.find('.seq-display-input').val(newSeq);
        }
    }
}).disableSelection();

$('#selected-list').on('input', '.seq-display-input', function() {
    var input = $(this);
    var val = input.val();
    var hiddenInput = input.closest('li').find('.seq-no-input');
    hiddenInput.val(val);
});

$('#search-available').on('input', function() {
    var query = $(this).val().toLowerCase();
    $('#available-list li').each(function() {
        var text = $(this).text().toLowerCase();
        $(this).toggle(text.indexOf(query) > -1);
    });
});

$('#search-selected').on('input', function() {
    var query = $(this).val().toLowerCase();
    $('#selected-list li').each(function() {
        var text = $(this).text().toLowerCase();
        $(this).toggle(text.indexOf(query) > -1);
    });
});
";
$this->registerJs($script, View::POS_END);
?>

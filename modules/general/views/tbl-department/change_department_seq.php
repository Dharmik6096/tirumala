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

$this->title = Yii::t('app', 'Change Department Sequence');
?>

<?php echo $form->errorSummary($model); ?>

<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-default panel-main">
            <div class="panel-heading"><?= $this->title ?></div>
            <div class="sequence-well sequence-box-well">
                <?=
                Sortable::widget([
                    'type' => Sortable::TYPE_LIST,
                    'items' => array_map(function($department) {
                                return ['content' => Html::encode($department->department), 'options' => ['data-id' => $department->department_id]];
                            }, $departments),
                    'options' => ['id' => 'department-lists', 'class' => 'list-group border-none', 'style' => 'min-height: 370px;'],
                    'itemOptions' => ['class' => 'list-group-item'],
                ]);
                ?>
            </div>
        </div>
    </div>

    <div class="col-sm-2 padding_top_20 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
        <div class="form-group">
            <?= Yii::$app->controls->save(Yii::$app->label->button('create'), $model); ?>
            <?= Yii::$app->controls->cancel($model); ?>
        </div>
    </div>
</div>
<?= Html::hiddenInput('order', '', ['id' => 'order-input']) ?>
<?php ActiveForm::end(); ?>

<?php
$script = "
$('#department-sequence-form').on('beforeSubmit', function() {
    var order = [];
    $('#department-lists li').each(function() {
        order.push($(this).data('id'));
    });
    $('#order-input').val(order.join(','));
    return true;
});
";
$this->registerJs($script, View::POS_END);
?>

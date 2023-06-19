<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use yii\web\View;

$value = (!$model->isNewRecord) ? $model->formula_description : '';
$selected = Yii::$app->session->get('Unions');
$model->union_code = !empty($selected) ? $selected : $model->union_code;
?>

<?php $form = ActiveForm::begin(); ?>
<?php echo $form->errorSummary($model); ?>
<div class="row">
    <div class="col-sm-2" id="union">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union'); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->dropdown('milk_type_code', $model, $form, '', 'Milk Type'); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->dropdown('rate_type_code', $model, $form, '', 'Rate Type'); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->controls->date($model, $form, 'wef_date', '', FALSE, date('d-m-Y')); ?>
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'formula')->textInput() ?>
    </div>
    <!-- <div class="clearfix"></div> -->
    <div class="col-sm-2 padding_top_20 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
        <div class="form-group">
            <?= Yii::$app->controls->save(Yii::$app->label->button('create'), $model); ?>
            <?= Yii::$app->controls->reset(); ?>
            <?= Yii::$app->controls->cancel($model); ?>
        </div>
    </div>
</div>

<?php ActiveForm::end(); ?>

<?php
$script = "
    $('button[type=\'reset\']').click(function(){
        $('.extra-capacity').css('display','none');
        $('#formula_description').val('');
        $('#formula').val('');
        $('#hdn_screen').val('');
        $('#w0')[0].reset();
        var input = document.querySelector('.screen > span');
        var inputVal = input.innerHTML;
        input.innerHTML = '';
        $('#finalformula > div').text(input.innerHTML);
    });
";
$this->registerJs($script, View::POS_END, 'extra-select');

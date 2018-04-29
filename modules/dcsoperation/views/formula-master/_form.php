<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\web\View;

$value = (!$model->isNewRecord) ? $model->formula_description : '';
$selected = Yii::$app->session->get('Unions');
$model->union_code = !empty($selected) ? $selected : $model->union_code;
?>

<?php $form = ActiveForm::begin(); ?>
<?php echo $form->errorSummary($model); ?>
<div class="row">
    <div class="col-sm-3" id="union">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union'); ?>
    </div>
    <div class="col-sm-3">
        <?= Yii::$app->dropdown->dropdown('milk_type_code', $model, $form, '', 'Milk Type'); ?>
    </div>
    <div class="col-sm-3">
        <?= Yii::$app->dropdown->dropdown('rate_type_code', $model, $form, '', 'Rate Type'); ?>
    </div>
    <div class="col-sm-3">
        <?= Yii::$app->controls->date($model, $form, 'wef_date', '', FALSE, date('d-m-Y')); ?>
    </div>
    <div class="clearfix"></div>
    <div class="col-sm-3">
        <?= Yii::$app->controls->active($model, $form); ?>
    </div>
    <div class="clearfix"></div>
    <div class="col-sm-12">
        <div id="calculator">
            <!-- Screen and clear key -->
            <div class="top">
                <div class="screen"><span class="formula"><?= $value ?></span></div>
                <?= Html::activeHiddenInput($model, 'formula_description', ['id' => 'formula_description']); ?>
                <?= Html::activeHiddenInput($model, 'formula', ['id' => 'formula']); ?>
                <input id="hdn_screen" type="hidden">
                <!--<span class="bkspc">Backspace</span>-->
                <span class="bkspc">B</span>
                <span class="clear">C</span>
            </div>
            <div class="keys">
                <span>CLR</span>
                <span>FAT</span>
                <span>FATKG</span>
                <span>SNF</span>
                <span>SNFKG</span>
                <span>TS</span>
                <span>TSKG</span>
                <span class="operator">+</span>
                <span>7</span>
                <span>8</span>
                <span>9</span>
                <span class="operator">-</span>
                <span>4</span>
                <span>5</span>
                <span>6</span>
                <span class="operator">*</span>
                <span>1</span>
                <span>2</span>
                <span>3</span>
                <span class="operator">/</span>
                <span>0</span>
                <span>(</span>
                <span>)</span>
                <span>.</span>
            </div>
            <div id="finalformula">
                <strong>Formula:</strong>
                <div></div>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="col-sm-12 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
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

<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\web\View;

$title = Yii::$app->label->title($type, 'General Formula');
$button = Yii::$app->label->button($type);
$this->title = Yii::t('app', $title);
?>


<?php
$form = ActiveForm::begin(['options' => [
                'field-class' => 'form-group col-sm-3',
                'id' => 'form-general-formula'
            ], 'validateOnBlur' => FALSE,
            
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
            'fieldConfig' => [
        ]]);
?>
<div class="panel-body">
    <div class="panel-subheading">
        <?php echo $form->errorSummary($model); ?>
        <div class="row">
            <div class="col-sm-2">
                <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', FALSE); ?>
            </div>
            <?= $form->field($model, 'formula_name', ['options' => ['class' => 'form-group col-sm-2']])->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'formula', ['options' => ['class' => 'form-group col-sm-2']])->textInput(['maxlength' => true, 'readonly' => true]) ?>
        </div>

    </div>

    <div id="calculator">
        <!-- Screen and clear key -->
        <div class="top">
            <div class="screen"><span class="formula"><?= $model->formula ?></span></div>
            <?= Html::activeHiddenInput($model, 'formula_description', ['id' => 'formula']); ?>
            <span class="bkspc">Back</span>
            <span class="clear">C</span>
        </div>
        <div class="keys">
            <?php
            foreach ($keyword_data as $keywords) {
                echo "<span>" . $keywords->keyword . "</span>";
            }
            ?>
        </div>
        <div class="keys">
            <span>[val7]</span>
            <span>[val8]</span>
            <span>[val9]</span>
            <span class="operator">+</span>
            <span>[val4]</span>
            <span>[val5]</span>
            <span>[val6]</span>
            <span class="operator">-</span>
            <span>[val1]</span>
            <span>[val2]</span>
            <span>[val3]</span>
            <span class="operator">*</span>
            <span>(</span>
            <span>[val0]</span>
            <span>)</span>
            <span class="operator">/</span>
        </div>
        <div id="finalformula">
            <strong>Formula:</strong>
            <div><?= $model->formula ?></div>
        </div>
    </div>

</div>        
<div class="panel-footer shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
    <?= Yii::$app->controls->save($button, $model); ?>
    <?= Yii::$app->controls->reset(); ?>
    <?= Yii::$app->controls->cancel($model, 'index'); ?>
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
$this->registerJs($script, View::POS_END, 'general-formula-master');

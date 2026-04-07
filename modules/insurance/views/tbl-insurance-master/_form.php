<?php

use app\components\ActiveForm;

$readonly = $type == 'create' ? FALSE : TRUE;
$form = ActiveForm::begin([
            'options' => [],
            'validateOnBlur' => FALSE,
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
        ]);
?>
<?php echo $form->errorSummary($model); ?>
<div class="row">
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union', $readonly); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->controls->date($model, $form, 'insurance_start_date', '', FALSE); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->controls->date($model, $form, 'insurance_end_date', '', FALSE); ?>
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'member_min_age')->textInput(['type' => 'number', 'min' => 1]) ?>
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'member_max_age')->textInput(['type' => 'number', 'min' => 1]) ?>
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'insurance_description')->textarea() ?>
    </div>
    <div class="col-sm-2 padding_top_20 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
        <div class="form-group">
            <?= Yii::$app->controls->save(Yii::$app->label->button($type), $model); ?>
            <?= Yii::$app->controls->reset(); ?>
            <?= Yii::$app->controls->cancel($model); ?>
        </div>
    </div>
</div>

<?php ActiveForm::end(); ?>
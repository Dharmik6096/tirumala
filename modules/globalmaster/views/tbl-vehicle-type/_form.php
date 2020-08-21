<?php

use yii\bootstrap\ActiveForm;

?>
<?php $form = ActiveForm::begin([
            'validateOnBlur' => false,
            
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
        ]); ?>
<?php echo $form->errorSummary($model); ?>
<div class="row">
    <div class="col-sm-2">
        <?= $form->field($model, 'vehicle_type_name')->textInput() ?>
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'local_name')->textInput() ?>
    </div>
    <div class="col-sm-2 mt15">
        <?= Yii::$app->controls->active($model, $form); ?>
    </div>
    <!-- <div class="clearfix"></div> -->
    <div class="col-sm-2 padding_top_20 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
        <div class="form-group">         
            <?= Yii::$app->controls->save(Yii::$app->label->button($type), $model); ?>
            <?= Yii::$app->controls->reset(); ?>
            <?= Yii::$app->controls->cancel($model); ?>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>
   

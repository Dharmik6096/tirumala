<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\web\View;

$readonly = $type == 'create' ? FALSE : TRUE;
?>

<?php
$form = ActiveForm::begin([
            'validateOnBlur' => false,
            
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
        <?= Yii::$app->dropdown->depend_dropdown('transporter', $model, $form, 'tblkmwiserate-union_code', 'form-group col-sm-4', $model->getAttributeLabel('transporter_code'), '', $readonly); ?>
    </div>
    <div class="col-sm-2"> 
        <?= Yii::$app->dropdown->depend_dropdown('transport_vehicle', $model, $form, 'tblkmwiserate-transporter_code', 'form-group col-sm-4', $model->getAttributeLabel('vehicle_code'), '', $readonly); ?>
    </div>

    <!-- <div class="clearfix"></div> -->

    <div class="col-sm-2 number-validate">
        <?= $form->field($model, 'from_km')->textInput() ?>
    </div>
    <div class="col-sm-2 number-validate">
        <?= $form->field($model, 'to_km')->textInput() ?>
    </div>
    <div class="col-sm-2 number-validate">
        <?= $form->field($model, 'rate')->textInput() ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->controls->date($model, $form, 'wef_date', '', FALSE, '', $readonly); ?>
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



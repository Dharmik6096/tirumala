<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\web\View;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;

$readonly = $type == 'create' ? FALSE : TRUE;
$list = array('0' => 'No', '1' => 'Yes');
$disable = ($type == 'create') ? '' : ' disabled';
?>

<?php
$form = ActiveForm::begin([
            'options' => ['id' => 'bmc-form'],
            'validateOnBlur' => FALSE,
            'validateOnEnter' => TRUE,
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
        ]);
?>
<?php echo $form->errorSummary($model); ?>
<div class="row">
    <div class="col-md-3">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union', $readonly); ?>
    </div>
    <div class="col-md-3">
        <?= Yii::$app->dropdown->dropdownStatic('place_type', $model, $form, 'form-group', 'From Type', $readonly, 'from_type') ?> 
    </div>
    <div class="col-md-3">
        <?= Yii::$app->dropdown->places($model, $form, 'tbllocationwisekmdetail-union_code,tbllocationwisekmdetail-from_type', 'from_dest', $model->getAttributeLabel('from_dest'), FALSE, $readonly); ?> 
    </div>
    <div class="col-md-3">
        <?= Yii::$app->dropdown->dropdownStatic('place_type', $model, $form, 'form-group', 'To Type', $readonly, 'to_type') ?> 
    </div>
    <div class="col-md-3">
        <?= Yii::$app->dropdown->places($model, $form, 'tbllocationwisekmdetail-union_code,tbllocationwisekmdetail-to_type', 'to_dest', $model->getAttributeLabel('to_dest'), FALSE, $readonly); ?> 
    </div>
    <div class="col-sm-3">
        <?= Yii::$app->controls->date($model, $form, 'wef_date', '', '', false, $readonly, true); ?>
    </div>  
    <div class="col-md-3 number-validate">
        <?= $form->field($model, 'total_kms')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="clearfix"></div>
    <div class="col-sm-12 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
        <div class="form-group">
            <?= Yii::$app->controls->save(Yii::$app->label->button($type), $model); ?>
            <?= Yii::$app->controls->reset(); ?>
            <?= Yii::$app->controls->cancel($model); ?>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>


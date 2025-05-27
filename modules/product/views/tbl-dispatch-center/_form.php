<?php

use yii\helpers\Html;
use app\components\ActiveForm;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model app\modules\product\models\TblProduct */
/* @var $form yii\widgets\ActiveForm */

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

<?= $form->errorSummary($model); ?>
<div class="row">
    <div class="col-sm-2" id="union">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union'); ?>
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'dispatch_center_name')->textInput() ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->product_group($model, $form, 'tbldispatchcenter-union_code', 'dispatch_center_type_code', $model->getAttributeLabel('dispatch_center_type_code'), TRUE); ?>

        <!--<? Yii::$app->dropdown->depend_dropdown('product_group', $model, $form, 'tbldispatchcenter-union_code', 'form-group  padding-right-5 padding-left-0', $model->getAttributeLabel('dispatch_center_type_code'), 'dispatch_center_type_code', false, 0, [], TRUE); ?>-->
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
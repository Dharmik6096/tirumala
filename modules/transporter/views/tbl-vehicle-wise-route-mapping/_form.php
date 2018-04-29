<?php

use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\organisation\models\TblCollectionPoint */
/* @var $form yii\widgets\ActiveForm */
$title = Yii::$app->label->title($type, 'Vehicle Route');
$button = Yii::$app->label->button($type);
//$milkType = $model->getMilkTypes();
$this->title = Yii::t('app', $title);
?>

<?php
$form = ActiveForm::begin([
            'validateOnBlur' => false,
            'validateOnEnter' => TRUE,
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
            'fieldConfig' => [
        ]]);
?>
<h5 class="panel-subtitle"></h5>
<?php echo $form->errorSummary($model); ?>
<div class="row">
    <div class="col-sm-3">
        <?= Yii::$app->dropdown->vehicle($model, $form, 'vehicle_code', 'Vehicle');  ?>
    </div>
    <div class="col-sm-3">
       <?= Yii::$app->dropdown->dropdown('route_code',$model, $form,'form-group col-sm-2 padding-right-5 padding-left-0','Route',false,'route_code');  ?>
    </div>
    <div class="col-sm-3">
        <?= Yii::$app->controls->date($model, $form, 'wef_date', '', false); ?>
    </div>
    
    <div class="col-sm-3 mt25">
        <?= Yii::$app->controls->active($model, $form); ?>
    </div>
    <div class="clearfix"></div>
    <div class="col-sm-12 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
        <div class="form-group">
            <?= Yii::$app->controls->save($button, $model); ?>
            <?= Yii::$app->controls->reset(); ?>
            <?= Yii::$app->controls->cancel($model); ?>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>
<?php

use yii\bootstrap5\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\organisation\models\TblCollectionPoint */
/* @var $form yii\widgets\ActiveForm */
$title = Yii::$app->label->title($type, 'collection point');
$button = Yii::$app->label->button($type);
$milkType = $model->getMilkTypes();
$this->title = Yii::t('app', $title);
?>

<?php
$form = ActiveForm::begin([
            'validateOnBlur' => false,
            
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
            'fieldConfig' => [
        ]]);
?>
<h5 class="panel-subtitle"><?php echo Yii::t('app', $title); ?></h5>

<?php echo $form->errorSummary($model); ?>
<div class="row">
    <div class="col-sm-3">
        <?php Yii::$app->dropdown->federation($model, $form, 'federation_code', 'Federation'); ?>
    </div>
    <div class="col-sm-3">
        <?= Yii::$app->dropdown->union($model, $form, 'tblcollectionpoint-federation_code', 'union_code', 'Union'); ?>
    </div>
    <div class="col-sm-3">
        <?= Yii::$app->dropdown->depend_dropdown('dcs', $model, $form, 'tblcollectionpoint-union_code', '', 'Society'); ?>        
    </div>
    <div class="col-sm-3">
        <?= Yii::$app->dropdown->depend_dropdown('sub-center', $model, $form, 'tblcollectionpoint-dcs_code', '', 'Sub Center'); ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($model, 'milk_type_code')->listBox($milkType['value'], ['multiple' => 'multiple', 'size' => '10', 'options' => $milkType['selected']]); ?>
    </div>
    <?php //Yii::$app->dropdown->dropdown('milk_type_code', $model, $form, 'form-group col-sm-3','Milk Type'); ?>

    <?php //Yii::$app->dropdown->dropdown('milk_type_dcs', $model, $form, 'form-group col-sm-3','Milk Type'); ?>
    <div class="col-sm-3 mt25">
        <?= Yii::$app->controls->checkTemplateBootstrap5($model, $form); ?>
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
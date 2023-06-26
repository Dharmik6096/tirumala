<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\dcsoperation\models\TblHeadLoad */
/* @var $form yii\widgets\ActiveForm */
$title = Yii::$app->label->title($type, 'Head Load');
$this->title = Yii::t('app', $title);
?>

<?php
$form = ActiveForm::begin(['options' => [

                'field-class' => 'form-group col-sm-3'
            ], 'validateOnBlur' => FALSE,
            
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
            'fieldConfig' => [
            //'labelOptions' => [ 'class' => false],
        ]]);
?>

<h5 class="panel-subtitle"><?php echo Yii::t('app', $title); ?></h5>
<?php echo $form->errorSummary($model); ?>
<div class="row">
    <div class="col-sm-4">
        <?= Yii::$app->dropdown->dropdown('criteria_type_code', $model, $form, 'Criteria Type'); ?>        
    </div>
    <div class="clearfix"></div>
    <div class="col-sm-4">
        <?= $form->field($model, 'criteria_description')->textArea() ?>        
    </div>
    <div class="col-sm-12">
        <?= Yii::$app->controls->checkTemplateBootstrap5($model, $form); ?>
    </div>
    <div class="clearfix"></div>
    <div class="col-sm-12 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
        <div class="form-group">
            <?= Yii::$app->controls->save('Next', $model); ?>
            <?= Yii::$app->controls->reset(); ?>
            <?= Yii::$app->controls->cancel($model, 'index'); ?>
        </div>
    </div>
</div>

<?php ActiveForm::end(); ?>

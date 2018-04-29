<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\hardwareconfigutation\models\TblUnionConfig */
/* @var $form yii\widgets\ActiveForm */
$title = Yii::$app->label->title($type, 'general union configuration');
$button = Yii::$app->label->button($type);
$this->title = Yii::t('app', $title);
?>

    <?php $form = ActiveForm::begin(['options' => [

                'field-class' => 'form-group col-sm-3'
            ],'validateOnBlur' => FALSE,
        'validateOnEnter'=>TRUE,
        'validateOnChange'=>FALSE,
        'enableClientValidation'=>true,
        'validateOnSubmit'=>true,
        'fieldConfig' => [
                //'labelOptions' => [ 'class' => false],
    ]]); ?>

<div class="panel-body">
        <div class="panel-subheading">
            <h5 class="panel-subtitle"><?php echo Yii::t('app', $title); ?></h5>

            <?php echo $form->errorSummary($model); ?>
            <div class="row">
                    <?= $form->field($model, 'perc_disp_recp_milk', ['options' => ['class' => 'form-group col-sm-3']])->textInput() ?>

                    <?= $form->field($model, 'min_member_age', ['options' => ['class' => 'form-group col-sm-3']])->textInput(['maxlength' => 3]) ?>

                    <?= $form->field($model, 'manual_days_collection', ['options' => ['class' => 'form-group col-sm-3']])->textInput(['maxlength' => 5]) ?>

                    <?= $form->field($model, 'audit_response_time', ['options' => ['class' => 'form-group col-sm-3']])->textInput(['maxlength' => 5]) ?>
                    <div class="clearfix"></div>
                    <?= $form->field($model, 'auto_audit_resolution', ['options' => ['class' => 'form-group col-sm-3']])->textInput() ?>

                    <?= $form->field($model, 'range_end', ['options' => ['class' => 'form-group col-sm-3']])->textInput() ?>
                
                    <?= Yii::$app->controls->active($model, $form); ?>
            </div>

        </div>
    </div>      
    <div class="panel-footer shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
        <?= Yii::$app->controls->save($button,$model);  ?>
        <?= Yii::$app->controls->reset(); ?>
        <?= Yii::$app->controls->cancel($model,'index'); ?>
    </div>

    <?php ActiveForm::end(); ?>


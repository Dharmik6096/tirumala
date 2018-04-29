<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\components\GeneralFunctions;

/* @var $this yii\web\View */
/* @var $model app\models\TblVillageMiscellaneous */
/* @var $form yii\widgets\ActiveForm */

$title = Yii::$app->label->title($type, 'village miscellaneous');
$button = Yii::$app->label->button($type);

$this->title = Yii::t('app', $title);
?>

<div class="panel-subheading">
    <h5 class="panel-subtitle"><?php echo $title; ?></h5>
     <?php
    $form = ActiveForm::begin(['options' => [

                    'class' => 'save-form'
                ],
                'validateOnBlur' => false,
                'validateOnEnter' => TRUE,
                'validateOnChange' => FALSE,
                'enableClientValidation' => true,
                'validateOnSubmit' => true,
                'fieldConfig' => [
                //'labelOptions' => [ 'class' => false],
    ]]);
    ?>
    <?php echo $form->errorSummary($model); ?>
    <div class="row">
        <div class="col-sm-3">Village Code: <?php echo Yii::$app->getRequest()->getQueryParam('id'); ?></div>
        <div class="col-sm-3">Village Name: <?php echo Yii::$app->getRequest()->getQueryParam('name'); ?></div>
        <div class="col-sm-12"><hr></div>
    </div>
    <div class="row">
        <?= $form->field($model, 'miscellaneous_code', ['options' => ['class' => 'form-group col-sm-3']])->dropDownList($miscellaneous, ['prompt' => 'Select Miscellaneous']); ?>
        <?= Yii::$app->controls->active($model, $form); ?>
    </div>
    <div class="row">
        <?= $form->field($model, 'description', [ 'options' => ['class' => 'form-group col-sm-6',]])->textarea(['rows' => 3]) ?>
        <?= $form->field($model, 'local_description', [ 'options' => ['class' => 'form-group col-sm-6',]])->textarea(['rows' => 3, 'class' => 'form-control ' . Yii::$app->session->get('FontName')]) ?>
    </div>
    <div class="row">
        <div class="col-sm-12 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="true">
            <?= Html::button($button, ['class' => 'btn btn-default apply-shortcut', 'shortcut_key' => $model->isNewRecord ? 'ctrl+alt+s' : 'ctrl+alt+u', 'button' => 'save']) ?>
            <?= Html::resetButton('reset', ['class' => 'btn btn-default apply-shortcut', 'shortcut_key' => 'ctrl+alt+r']) ?>
            <?php // if (!$model->isNewRecord) { ?>
                <?php echo Html::a('cancel',Yii::$app->request->referrer,['class'=>'btn btn-default apply-shortcut', 'shortcut_key' => 'ctrl+alt+c']); ?>
            <?php // } ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>

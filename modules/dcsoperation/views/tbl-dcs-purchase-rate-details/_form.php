<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\dcsoperation\models\TblDcsPurchaseRateAuto */
/* @var $form yii\widgets\ActiveForm */
$title = Yii::$app->label->title($type, 'purchase rate');
$button = Yii::$app->label->button($type);
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
<div class="panel-body">
    <div class="panel-subheading">
        <h5 class="panel-subtitle"> <?php echo Yii::t('app', $title); ?></h5>

        <?php echo $form->errorSummary($model); ?>
        <div class="row">
            
            <?= Yii::$app->dropdown->dropdown('milk_type_code', $model, $form, 'form-group col-sm-3', 'Milk Type'); ?>
            
            <?= Yii::$app->dropdown->dropdown('milk_quality_type_code', $model, $form, 'form-group col-sm-3', 'Milk Quality Type'); ?>
            
            <?= $form->field($model, 'fat', ['options' => ['class' => 'form-group col-sm-2']])->textInput() ?>

            <?= $form->field($model, 'fat_to', ['options' => ['class' => 'form-group col-sm-2']])->textInput(['maxlength' => true]) ?>

            <?php if ($purchaseModel->rate_type == 1) { ?>

                <?= $form->field($model, 'snf', ['options' => ['class' => 'form-group col-sm-2']])->textInput() ?>

                <?= $form->field($model, 'snf_to', ['options' => ['class' => 'form-group col-sm-2']])->textInput(['maxlength' => true]) ?>

            <?php } ?>

            <?= Html::activeHiddenInput($model, 'rate_type', ['value' => $purchaseModel->rate_type]) ?>

            <?= Html::activeHiddenInput($model, 'fat_value') ?>

            <?= Html::activeHiddenInput($model, 'snf_value') ?>

            <?= Html::activeHiddenInput($model, 'formula') ?>

            <div class="clearfix"></div>

            <div class="col-sm-12">
                <?=
                Html::a(Yii::t('app', 'Generate'), [ 'tbl-dcs-purchase-rate-auto/create', 'id' => $_GET['id']], ['class' => 'btn btn-primary', 'data-method' => 'POST',
                    'data-params' => ['type' => 'generate']
                ])
                ?>
                <?=
                Html::a(Yii::t('app', 'Calculate'), 'javascript:void(0)', ['class' => 'btn btn-primary calculate-rate'])
                ?>
            </div>
        </div>
    </div>
    <?php
    if (!empty($modelAtteributes)) {
        echo $this->render('calculation', ['form' => $form, 'modelAtteributes' => $modelAtteributes, 'purchaseModel' => $purchaseModel]);
    }
    ?>
</div>
<div class="panel-footer shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
    <?= Yii::$app->controls->save($button, $model); ?>
    <?= Yii::$app->controls->reset(); ?>
    <?= Yii::$app->controls->cancel($model, 'index'); ?>
</div>

<?php ActiveForm::end(); ?>

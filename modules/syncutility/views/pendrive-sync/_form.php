<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;
use yii\web\View;

$title = Yii::$app->label->title($type, 'Export Sync Data');
$button = Yii::$app->label->button($type);

$this->title = Yii::t('app', $title);
?>


<?php
$form = ActiveForm::begin(['options' => [
                'class' => 'save-form',
                'field-class' => 'col-sm-12'
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
    <div class="col-sm-3">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', $model->getAttributeLabel('union_code')); ?>
    </div>
    <div class="col-sm-3">
        <?= Yii::$app->dropdown->union_dcs('dcs', $model, $form, 'tblpendriveimportexport-union_code', '', $model->getAttributeLabel('dcs_code')); ?>            
    </div>
    <div class="col-sm-12 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
        <div class="form-group">
            <?= Yii::$app->controls->save($button, $model); ?>
            <?= Yii::$app->controls->reset(); ?>
            <?= Yii::$app->controls->cancel($model, 'index'); ?>
        </div>
    </div>
</div>

<?php ActiveForm::end(); ?>

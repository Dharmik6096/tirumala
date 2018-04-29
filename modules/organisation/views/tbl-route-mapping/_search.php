<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\organisation\models\TblPlantSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<?php
$form = ActiveForm::begin([
            'action' => ['index'],
            'method' => 'get',
        ]);
?>
<?php if (Yii::$app->session->get('organizations_type') !== 'UNION' || (!empty(Yii::$app->session->get('Unions')) && count(explode(',', Yii::$app->session->get('Unions')))>1)){ ?>
    <div class="col-sm-3">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code'); ?>
    </div>
<?php } ?>
<div class="col-sm-3">
    <?= Yii::$app->controls->unit_conversion($model,[1,2],1,'Select capacity unit'); ?>
</div>
<?php //$form->field($model, 'federation_code', ['options' => ['class' => 'form-group col-sm-2 padding-right-0']])->dropDownList(\app\components\GeneralFunctions::getActiveFederation(), ['prompt' => 'Select Federation'])->label(false);  ?>
<div class="col-sm-2">
    <?= Yii::$app->controls->search(); ?>
</div>
<?php ActiveForm::end(); ?>
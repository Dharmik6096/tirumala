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

<div class="col-sm-3">
    <?= Yii::$app->dropdown->vehicle($model, $form, 'vehicle_code'); ?>
</div>
<div class="col-sm-3">
    <?= Yii::$app->dropdown->dropdown('transporter_payment_head_code',$model, $form,'form-group col-sm-2 padding-right-5 padding-left-0',false,false,'transporter_payment_head_code');  ?>
</div>
<?php //$form->field($model, 'federation_code', ['options' => ['class' => 'form-group col-sm-2 padding-right-0']])->dropDownList(\app\components\GeneralFunctions::getActiveFederation(), ['prompt' => 'Select Federation'])->label(false);  ?>
<div class="col-sm-2">
    <?= Yii::$app->controls->search(); ?>
</div>
<?php ActiveForm::end(); ?>
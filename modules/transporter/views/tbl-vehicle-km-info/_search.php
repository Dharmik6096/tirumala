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
    <?= Yii::$app->dropdown->dropdown('transporter_code', $model, $form, 'form-group col-sm-2 padding-right-5 padding-left-0', false, false, 'transporter_code'); ?>
</div>
<div class='col-sm-3'>
    <?= Yii::$app->dropdown->vehicletransporter($model, $form, 'tblvehiclekminfosearch-transporter_code', 'vehicle_code'); ?>
</div>
<div class="col-sm-2">
    <?= Yii::$app->controls->search(); ?>
</div>
<?php ActiveForm::end(); ?>
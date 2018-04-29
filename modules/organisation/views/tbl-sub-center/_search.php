<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\organisation\models\TblSubCenterSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<?php
$form = ActiveForm::begin([
            'action' => ['index'],
            'method' => 'get',
        ]);
?>
<div class="col-sm-3">
<?php Yii::$app->dropdown->federation($model, $form, 'federation_code', false); ?>
</div>
<div class="col-sm-3">
<?= Yii::$app->dropdown->union($model, $form, 'tblsubcentersearch-federation_code', 'union_code', false); ?>
</div>
<div class="col-sm-3">
<?= Yii::$app->dropdown->depend_dropdown('dcs', $model, $form, 'tblsubcentersearch-union_code'); ?>    
</div>
<div class="col-sm-2">
<?= Yii::$app->controls->search(); ?>
</div>
<?php ActiveForm::end(); ?>
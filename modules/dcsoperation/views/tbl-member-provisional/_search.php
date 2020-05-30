<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\collection\models\TblMemberProvisionalSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="grid-search search-filter">

    <?php
    $form = ActiveForm::begin([
                'action' => ['bulk-approval'],
                'method' => 'get',
    ]);
    ?>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', $model->getAttributeLabel('union_code')); ?>
    </div>
    <div class="col-sm-3">
        <?= Yii::$app->dropdown->union_dcs('dcs', $model, $form, 'tblmemberprovisional-union_code', '', false); ?>            
    </div>
   
    <div class="col-sm-2">
        <?= Yii::$app->controls->search(); ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>
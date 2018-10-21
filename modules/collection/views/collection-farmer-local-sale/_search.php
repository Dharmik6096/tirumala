<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\collection\models\CollectionFarmerLocalSaleSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="collection-farmer-local-sale-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'farmerid') ?>

    <?= $form->field($model, 'vlccid') ?>

    <?= $form->field($model, 'routeid') ?>

    <?= $form->field($model, 'bmcid') ?>

    <?= $form->field($model, 'sampleno') ?>

    <?php // echo $form->field($model, 'qty') ?>

    <?php // echo $form->field($model, 'fat') ?>

    <?php // echo $form->field($model, 'snf') ?>

    <?php // echo $form->field($model, 'water') ?>

    <?php // echo $form->field($model, 'clr') ?>

    <?php // echo $form->field($model, 'rtpl') ?>

    <?php // echo $form->field($model, 'amount') ?>

    <?php // echo $form->field($model, 'rateid') ?>

    <?php // echo $form->field($model, 'dtdate') ?>

    <?php // echo $form->field($model, 'shift') ?>

    <?php // echo $form->field($model, 'qtyauto') ?>

    <?php // echo $form->field($model, 'qltyauto') ?>

    <?php // echo $form->field($model, 'qtytime') ?>

    <?php // echo $form->field($model, 'qltytime') ?>

    <?php // echo $form->field($model, 'kgltrconst') ?>

    <?php // echo $form->field($model, 'ltrkgconst') ?>

    <?php // echo $form->field($model, 'qtymode') ?>

    <?php // echo $form->field($model, 'qtydecimals') ?>

    <?php // echo $form->field($model, 'qltydecimals') ?>

    <?php // echo $form->field($model, 'milktype') ?>

    <?php // echo $form->field($model, 'milkqtype') ?>

    <?php // echo $form->field($model, 'createddate') ?>

    <?php // echo $form->field($model, 'createdby') ?>

    <?php // echo $form->field($model, 'modifieddate') ?>

    <?php // echo $form->field($model, 'modifiedby') ?>

    <?php // echo $form->field($model, 'lastsynchronized') ?>

    <?php // echo $form->field($model, 'syncdirection') ?>

    <?php // echo $form->field($model, 'usbflag') ?>

    <?php // echo $form->field($model, 'paymentid') ?>

    <?php // echo $form->field($model, 'StdRate') ?>

    <?php // echo $form->field($model, 'RateType') ?>

    <?php // echo $form->field($model, 'KgFatRate') ?>

    <?php // echo $form->field($model, 'KgSnfRate') ?>

    <?php // echo $form->field($model, 'RateRecalType') ?>

    <?php // echo $form->field($model, 'farmerstatus') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

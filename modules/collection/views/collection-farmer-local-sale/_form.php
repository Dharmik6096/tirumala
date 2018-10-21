<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\collection\models\CollectionFarmerLocalSale */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="collection-farmer-local-sale-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'farmerid')->textInput() ?>

    <?= $form->field($model, 'vlccid')->textInput() ?>

    <?= $form->field($model, 'routeid')->textInput() ?>

    <?= $form->field($model, 'bmcid')->textInput() ?>

    <?= $form->field($model, 'sampleno')->textInput() ?>

    <?= $form->field($model, 'qty')->textInput() ?>

    <?= $form->field($model, 'fat')->textInput() ?>

    <?= $form->field($model, 'snf')->textInput() ?>

    <?= $form->field($model, 'water')->textInput() ?>

    <?= $form->field($model, 'clr')->textInput() ?>

    <?= $form->field($model, 'rtpl')->textInput() ?>

    <?= $form->field($model, 'amount')->textInput() ?>

    <?= $form->field($model, 'rateid')->textInput() ?>

    <?= $form->field($model, 'dtdate')->textInput() ?>

    <?= $form->field($model, 'shift')->textInput() ?>

    <?= $form->field($model, 'qtyauto')->textInput() ?>

    <?= $form->field($model, 'qltyauto')->textInput() ?>

    <?= $form->field($model, 'qtytime')->textInput() ?>

    <?= $form->field($model, 'qltytime')->textInput() ?>

    <?= $form->field($model, 'kgltrconst')->textInput() ?>

    <?= $form->field($model, 'ltrkgconst')->textInput() ?>

    <?= $form->field($model, 'qtymode')->textInput() ?>

    <?= $form->field($model, 'qtydecimals')->textInput() ?>

    <?= $form->field($model, 'qltydecimals')->textInput() ?>

    <?= $form->field($model, 'milktype')->textInput() ?>

    <?= $form->field($model, 'milkqtype')->textInput() ?>

    <?= $form->field($model, 'createddate')->textInput() ?>

    <?= $form->field($model, 'createdby')->textInput() ?>

    <?= $form->field($model, 'modifieddate')->textInput() ?>

    <?= $form->field($model, 'modifiedby')->textInput() ?>

    <?= $form->field($model, 'lastsynchronized')->textInput() ?>

    <?= $form->field($model, 'syncdirection')->textInput() ?>

    <?= $form->field($model, 'usbflag')->textInput() ?>

    <?= $form->field($model, 'paymentid')->textInput() ?>

    <?= $form->field($model, 'StdRate')->textInput() ?>

    <?= $form->field($model, 'RateType')->textInput() ?>

    <?= $form->field($model, 'KgFatRate')->textInput() ?>

    <?= $form->field($model, 'KgSnfRate')->textInput() ?>

    <?= $form->field($model, 'RateRecalType')->textInput() ?>

    <?= $form->field($model, 'farmerstatus')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

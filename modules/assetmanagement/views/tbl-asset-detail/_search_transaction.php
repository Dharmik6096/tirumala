<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\View;

$action = !empty($datefilter) ? 'in-asset-transation' : 'out-asset-transation';
$selectedData = $dataProvider->getModels();
$disableClass = !empty($selectedData) ? TRUE : FALSE;
?>

<div class="tbl-asset-transaction-search">
    <?php
    $form = ActiveForm::begin([
                'method' => 'get',
                'action' => [$action],
    ]);
    ?>   
    <?= Html::activeHiddenInput($model, 'is_search', ['value' => 1]) ?>

   
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', FALSE, FALSE); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->depend_dropdown('union_asset', $model, $form, 'tblassettransactionsearch-union_code', '', false, 'asset_code', FALSE); ?>
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'serial_number')->textInput(['placeholder' => 'Serial No.', 'readonly' => FALSE])->label(FALSE) ?>
    </div>

        <div class="col-sm-2">
            <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        </div>

    <?php ActiveForm::end(); ?>
</div>
<?php

use yii\helpers\Html;
use app\components\ActiveForm;
use yii\web\View;

?>
<div class="tbl-asset-transfer-search checkboxFilterList">
    <?php
    $form = ActiveForm::begin([
        'method' => 'get',
    ]);
    ?>
    <div class="row">
        <div class="col-sm-2">
            <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union', FALSE); ?>
        </div>
        <div class="col-sm-2">
            <?= Yii::$app->dropdown->union_plant($model, $form, 'tblassettransactionsearch-union_code', 'to_plant', $model->getAttributeLabel('to_plant'), FALSE, '', FALSE); ?>
        </div>
        <div class="col-sm-2">
            <?= Yii::$app->dropdown->plant_mcc($model, $form, 'tblassettransactionsearch-to_plant', 'to_mcc', $model->getAttributeLabel('to_mcc'), FALSE, '', FALSE); ?>
        </div>
        <div class="col-sm-2">
            <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'tblassettransactionsearch-to_mcc', 'to_bmc', $model->getAttributeLabel('to_bmc'), FALSE, '', '', FALSE); ?>
        </div>
        <div class="col-sm-2 ">
            <?= Yii::$app->dropdown->bmc_society($model, $form, 'tblassettransactionsearch-to_bmc', 'to_dcs', $model->getAttributeLabel('to_dcs'), FALSE, '', FALSE, TRUE); ?>
        </div>
        <div class="col-sm-1 mt20">
            <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
<?php

use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\collection\models\TblMilkCollectionSearch */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="search-filter large-search">
    <?php $form = ActiveForm::begin(['method' => 'get']); ?>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', Yii::t('app', 'Union')) ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->union_plant($model, $form, 'tblvsppaymentconfigsearch-union_code', 'plant_code', Yii::t('app', 'Plant')) ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->plant_mcc($model, $form, 'tblvsppaymentconfigsearch-plant_code', 'mcc_plant_code', Yii::t('app', 'MCC')) ?>
    </div>  
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'tblvsppaymentconfigsearch-mcc_plant_code', 'bmc_code', Yii::t('app', 'BMC')) ?>
    </div>  
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->bmc_society($model, $form, 'tblvsppaymentconfigsearch-bmc_code', 'dcs_code', Yii::t('app', 'Society')) ?>
    </div>
    <div class="col-sm-3 mt23">
        <?= Yii::$app->controls->search() ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>

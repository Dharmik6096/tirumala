

<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\collection\models\TblMilkCollectionSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="search-filter large-search">

    <?php
    $form = ActiveForm::begin([
//                'action' => ['get-temp-data'],
                'method' => 'get',
    ]);
    ?>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', $model->getAttributeLabel('union_code')); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->union_plant($model, $form, 'tblindentmastersearch-union_code', 'plant_code', $model->getAttributeLabel('plant_code'), FALSE, ''); ?>
    </div>
    <div class="col-sm-2 ">
        <?= Yii::$app->dropdown->plant_mcc($model, $form, 'tblindentmastersearch-plant_code', 'mcc_plant_code', $model->getAttributeLabel('mcc_plant_code'), FALSE, ''); ?>
    </div>  
    <div class="col-sm-2 ">
        <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'tblindentmastersearch-mcc_plant_code', 'bmc_code', Yii::t('app', 'BMC'), FALSE); ?>
    </div>  
    <div class="col-sm-2 height_65">
        <?= Yii::$app->dropdown->all_routes($model, $form, 'tblindentmastersearch-plant_code,tblindentmastersearch-mcc_plant_code,tblindentmastersearch-bmc_code', 'route_code', $model->getAttributeLabel('route_code'), FALSE); ?>
    </div>
    <div class="col-sm-2 height_65">
        <?php echo Yii::$app->dropdown->route_dcs($model, $form, 'tblindentmastersearch-route_code', 'dcs_code', Yii::t('app', 'DCS'), false, false); ?>
        <?php // Yii::$app->dropdown->bmc_society($model, $form, 'tblproductrequisitionsearch-bmc_code', 'dcs_code', Yii::t('app', 'DCS')); ?>
    </div>
    <div class="col-sm-3 mt23">
        <?= Yii::$app->controls->search(); ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
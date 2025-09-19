<?php

use app\components\ActiveForm;
?>

<div class="search-filter large-search">

    <?php
    $form = ActiveForm::begin([
                'method' => 'get',
    ]);
    ?>

    <div class="col-sm-2">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', Yii::t('app', 'Union')); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->union_plant($model, $form, 'tblmemberraterepushlogsearch-union_code', 'plant_code', Yii::t('app', 'Plant'), FALSE, ''); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->plant_mcc($model, $form, 'tblmemberraterepushlogsearch-plant_code', 'mcc_plant_code', Yii::t('app', 'MCC'), FALSE, ''); ?>
    </div>  
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'tblmemberraterepushlogsearch-mcc_plant_code', 'bmc_code', Yii::t('app', 'BMC'), FALSE); ?>
    </div>  
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->dropdownStatic('dpu_type', $model, $form, '', $model->getAttributeLabel('dpu_type'), true, 'dpu_type'); ?>
    </div>
    <div class="col-sm-3 mt23">
        <?= Yii::$app->controls->search(); ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
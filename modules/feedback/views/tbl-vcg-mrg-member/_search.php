<?php
use yii\widgets\ActiveForm;
?>
<div class="search-filter large-search">
    <?php
    $form = ActiveForm::begin([
        'method' => 'get',
    ]);
    ?>
    <div class="row">
        <div class="col-sm-2">
            <?= Yii::$app->dropdown->federation_union($model, $form, 'f_union_code', $model->getAttributeLabel('union_code')); ?>
        </div>
        <div class="col-sm-2">
            <?= Yii::$app->dropdown->union_plant($model, $form, 'tblvcgmrgmembersearch-f_union_code', 'f_plant_code', $model->getAttributeLabel('plant_code'), FALSE, ''); ?>
        </div>
        <div class="col-sm-2 ">
            <?= Yii::$app->dropdown->plant_mcc($model, $form, 'tblvcgmrgmembersearch-f_plant_code', 'mcc_plant_code', $model->getAttributeLabel('mcc_plant_code'), FALSE, ''); ?>
        </div>  
        <div class="col-sm-2 ">
            <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'tblvcgmrgmembersearch-mcc_plant_code', 'bmc_code', Yii::t('app', 'BMC'), FALSE); ?>
        </div>  
        <div class="col-sm-2">
            <?= Yii::$app->dropdown->all_routes($model, $form, 'tblvcgmrgmembersearch-plant_code,tblvcgmrgmembersearch-mcc_plant_code,tblvcgmrgmembersearch-bmc_code', 'route_code', $model->getAttributeLabel('route_code'), FALSE); ?>
        </div>
        <div class="col-sm-2">
            <?php echo Yii::$app->dropdown->route_dcs($model, $form, 'tblvcgmrgmembersearch-route_code', 'dcs_code', Yii::t('app', 'DCS'), false, false); ?>
        </div>
        <div class="col-sm-2">
            <?= Yii::$app->controls->date($model, $form, 'from_date', 'form-group col-sm-2 padding-left-5 padding-right-5', false, false, false, TRUE); ?>
        </div>
        <div class="col-sm-2">
            <?= Yii::$app->controls->date($model, $form, 'to_date', 'form-group col-sm-2 padding-left-5 padding-right-5', false, false, false, TRUE); ?>
        </div>
        <div class="col-sm-3 mt18">
            <?= Yii::$app->controls->search(); ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
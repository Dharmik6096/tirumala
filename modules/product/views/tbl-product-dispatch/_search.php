
<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$form = ActiveForm::begin([
            'options' => [
                'field-class' => 'form-group col-sm-2 padding-right-5 padding-left-5',
                'federation_code' => 'form-group col-sm-2 padding-right-5'
            ],
            'action' => ['create'],
            'method' => 'get',
        ]);
?>
<div class="row">
    <div class="col-sm-2 height_65" id="union">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union'); ?>
    </div>
    <!--    <div class="col-sm-2 height_65">
    <?= Yii::$app->dropdown->union_plant($model, $form, 'tblproductrequisitionsearch-union_code', 'plant_code', $model->getAttributeLabel('plant_code')); ?>
        </div> 
        <div class="col-sm-2">
    <?= Yii::$app->dropdown->plant_mcc($model, $form, 'tblproductrequisitionsearch-plant_code', 'mcc_plant_code', $model->getAttributeLabel('mcc_plant_code')); ?>
        </div>      
        <div class="col-sm-2 height_65">
    <?= Yii::$app->dropdown->dropdownStatic('requisition_type', $model, $form, 'form-group', $model->getAttributeLabel('vendor_type'), false, 'vendor_type', false); ?>
    <?php // Yii::$app->dropdown->customer_type($model, $form, 'tblproductrequisitionsearch-bmc_code', 'vendor_type', TRUE, FALSE); ?>
        </div>
        <div class="col-sm-2 height_65">
    <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'tblproductrequisitionsearch-mcc_plant_code', 'bmc_code', $model->getAttributeLabel('bmc_code')); ?>
        </div>
    
        <div class="col-sm-2 height_65">
    <?= Yii::$app->dropdown->all_routes($model, $form, 'tblproductrequisitionsearch-plant_code,tblproductrequisitionsearch-mcc_plant_code,tblproductrequisitionsearch-bmc_code', 'route_code', $model->getAttributeLabel('route_code'), FALSE); ?>
        </div>
        <div class="col-sm-2 height_65">
    <?php echo Yii::$app->dropdown->route_dcs($model, $form, 'tblproductrequisitionsearch-route_code', 'dcs_code', Yii::t('app', 'DCS'), false, false); ?>
    <?php // Yii::$app->dropdown->bmc_society($model, $form, 'tblproductrequisitionsearch-bmc_code', 'dcs_code', Yii::t('app', 'DCS')); ?>
        </div>-->

    <div class="col-sm-2 height_65">
        <?= Yii::$app->dropdown->dropdown('dispatch_center', $model, $form, 'form-group col-sm-2 padding-right-5', $model->getAttributeLabel('dispatch_center')); ?> 
    </div>
    <div class="clearfix"></div>
    <div class="form-group col-sm-2 mt0 padding-left-5">
        <?= Yii::$app->controls->search(); ?>
    </div>
</div>

<?php ActiveForm::end(); ?>

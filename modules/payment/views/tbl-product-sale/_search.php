<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\collection\models\TblMilkCollectionSearch */
/* @var $form yii\widgets\ActiveForm */

$message = !empty($message) ? $message : 'Product Sale';
//$this->title = Yii::$app->label->title($message);
$type = !empty($type) ? $type : '';
?>

<div class="search-filter large-search">
    <?php
    $form = ActiveForm::begin([
                'method' => 'get',
    ]);
    ?>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->federation_union($searchModel, $form, 'union_code', 'Union'); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->union_plant($searchModel, $form, 'tblproductsalesearch-union_code', 'plant_code', $searchModel->getAttributeLabel('plant_code')); ?>
    </div> 
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->plant_mcc($searchModel, $form, 'tblproductsalesearch-plant_code', 'mcc_plant_code', $searchModel->getAttributeLabel('mcc_plant_code')); ?>
    </div>      
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->mcc_bmc($searchModel, $form, 'tblproductsalesearch-mcc_plant_code', 'bmc_code', $searchModel->getAttributeLabel('bmc_code')); ?>
    </div>

    <div class="col-sm-2">
        <?php
        if (in_array($type, ['memberBulkDelete', 'memberBulkDeleteApproval'])) {
            echo Html::activeHiddenInput($searchModel, 'customer_type');
            echo Yii::$app->dropdown->bmc_society($searchModel, $form, 'tblproductsalesearch-bmc_code', 'dcs_code', $searchModel->getAttributeLabel('dcs_code'));
        } else {
            $where = json_encode(['is_product_sale' => 1]);
            $notInArr = json_encode(['Member']);
            echo Html::hiddenInput('customer_type_depends', $where, ['id' => 'customer_type_depends']);
            echo Html::hiddenInput('customer_type_depends_not_in', $notInArr, ['id' => 'customer_type_depends_not_in']);
            echo Yii::$app->dropdown->customerType($searchModel, $form, 'tblproductsalesearch-union_code,customer_type_depends,customer_type_depends_not_in', 'customer_type', $searchModel->getAttributeLabel('customer_type'), FALSE, FALSE);
        }
        ?>
        <?php // Yii::$app->dropdown->customer_type($searchModel, $form, 'tblproductsalesearch-bmc_code', 'customer_type', TRUE, FALSE);   ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->controls->date($searchModel, $form, 'from_date', '', true); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->controls->date($searchModel, $form, 'to_date', '', true); ?>
    </div>
    <div class="col-sm-3 mt23">
        <?= Yii::$app->controls->search(); ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
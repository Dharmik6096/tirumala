<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model app\modules\collection\models\TblMilkCollectionSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="search-filter large-search">

    <?php
    $form = ActiveForm::begin([
                'method' => 'get',
    ]);
    ?>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', $model->getAttributeLabel('union_code')); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->union_plant($model, $form, 'tblindentmastersearch-union_code', 'plant_code', $model->getAttributeLabel('plant_code'), FALSE, ''); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->plant_mcc($model, $form, 'tblindentmastersearch-plant_code', 'mcc_plant_code', $model->getAttributeLabel('mcc_plant_code'), FALSE, ''); ?>
    </div>  
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'tblindentmastersearch-mcc_plant_code', 'bmc_code', Yii::t('app', 'BMC'), FALSE); ?>
    </div>  
    <div class="col-lg-4 ml35">
        <h5 class="panel-heading mb15"><?= Yii::t('app', 'Product Information') ?></h5>
        <div id="product-detail">
            <table class="table tab-bordered">
                <thead>
                    <tr>
                        <th>Product Code</th>
                        <th>Product Name</th>
                        <th>Product Total Stock</th>         
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="col-sm-3 height_65">
        <?= Yii::$app->dropdown->all_routes($model, $form, 'tblindentmastersearch-plant_code,tblindentmastersearch-mcc_plant_code,tblindentmastersearch-bmc_code', 'route_code', $model->getAttributeLabel('route_code'), FALSE); ?>
    </div>
    <div class="col-sm-3">
        <?= Yii::$app->dropdown->route_dcs($model, $form, 'tblindentmastersearch-route_code', 'dcs_code', Yii::t('app', 'DCS'), false, false); ?>
    </div>
    <div class="clearfix"></div>
    <div class="col-sm-3">
        <?= Yii::$app->dropdown->product_group_code($model, $form, 'tblindentmastersearch-union_code', 'product_group_code', 'Product Group', TRUE); ?>
    </div>
    <div class="col-sm-3 filldata">
        <?= Yii::$app->dropdown->product($model, $form, 'tblindentmastersearch-union_code,tblindentmastersearch-product_group_code', 'product_code', 'Product', TRUE, '', '', false, 'DependOnProduct'); ?>
    </div>
    <div class="clearfix"></div>
    <div class="col-sm-3 mt23">
        <?= Yii::$app->controls->search(); ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
<?php
$script = "
$(document).on('change','.filldata', function() {
        var product_code = $('#tblindentmastersearch-product_code').val();
       if(product_code !='' ){
            $('#product-detail').html('');         
            BindData(product_code);            
        }      
    });
   
    function BindData(product_code){
        $.ajax({
            type: 'get',
            url: '" . Url::to(['product-detail']) . "',
            data: {'product_code':product_code},             
            success: function(data) {
                $('#product-detail').html(data);
            }
        });
    }
    ";
$this->registerJs($script, View::POS_END, 'to-date-from-date');
?>
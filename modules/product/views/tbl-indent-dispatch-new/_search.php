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
    $isIndentApprovalCreditLimitCheck = Yii::$app->general->getUnionConfiguration(Yii::$app->session->get('Unions'), 'is_indent_approval_credit_limit_check', 'PORTAL') == 1 ? TRUE : FALSE;
    $form = ActiveForm::begin([
                'method' => 'get',
    ]);
    ?>
    <div class="row">
        <div class="col-sm-6">
            <div class="col-sm-4">
                <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', $model->getAttributeLabel('union_code')); ?>
            </div>
            <div class="col-sm-4">
                <?= Yii::$app->dropdown->union_plant($model, $form, 'tblindentmastersearch-union_code', 'plant_code', $model->getAttributeLabel('plant_code'), FALSE, ''); ?>
            </div>
            <div class="col-sm-4">
                <?= Yii::$app->dropdown->plant_mcc($model, $form, 'tblindentmastersearch-plant_code', 'mcc_plant_code', $model->getAttributeLabel('mcc_plant_code'), FALSE, ''); ?>
            </div>  
            <div class="col-sm-4">
                <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'tblindentmastersearch-mcc_plant_code', 'bmc_code', Yii::t('app', 'BMC'), FALSE); ?>
            </div>
            <div class="col-sm-2">
                <?= Yii::$app->dropdown->dropdownStatic('indent_customer_type', $model, $form, 'form-group padding-right-5', Yii::t('app', 'Customer Type'), false) ?>
            </div> 
            <div class="col-sm-4 hide_section">
                <?= Yii::$app->dropdown->all_routes($model, $form, 'tblindentmastersearch-plant_code,tblindentmastersearch-mcc_plant_code,tblindentmastersearch-bmc_code', 'route_code', $model->getAttributeLabel('route_code'), FALSE); ?>
            </div>
            <div class="col-sm-4 hide_section">
                <?= Yii::$app->dropdown->route_dcs($model, $form, 'tblindentmastersearch-route_code', 'dcs_code', Yii::t('app', 'DCS'), false, false); ?>
            </div>
            <div class="col-sm-2 hide_section">
                <?= Yii::$app->dropdown->customer_code($model, $form, 'tblindentmastersearch-bmc_code,tblindentmastersearch-customer_type', 'customer_code', Yii::t('app', 'Customer'), FALSE); ?>
            </div>
            <?php
            if(!$isIndentApprovalCreditLimitCheck){ ?>
                <div class="col-sm-4 hide_section">
                    <?= Yii::$app->dropdown->dropdownStatic('indent_group_by', $model, $form, 'form-group padding-right-5', Yii::t('app', 'Group By'), false, 'group_by') ?>
                </div>
            <?php
            } else {
                echo Html::activeHiddenInput($model, 'group_by', ['value' => 1]);
            } ?>
            <div class="col-sm-4">
                <?= Yii::$app->dropdown->dropdown('product_group_code', $model, $form, 'form-group padding-right-5 col-sm-12', $model->getAttributeLabel('product_group_code'), false, '', false, true, true); ?>
            </div>
            <div class="col-sm-4 filldata">
                <?= Yii::$app->dropdown->depend_dropdown('product_depend_group', $model, $form, 'tblindentmastersearch-product_group_code', 'form-group padding-right-5 col-sm-12', $model->getAttributeLabel('product_code'), 'product_code', false, 0, [], TRUE, Yii::t('app', 'Select Product'), FALSE, TRUE, TRUE); ?>
            </div>
            <div class="col-sm-3 mt23">
                <?= Yii::$app->controls->search(); ?>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="col-lg-12 ml35 view-grid text-wrap">
                <h5 class="panel-heading mb15"><?= Yii::t('app', 'Product Information') ?></h5>
                <div id="product-detail">
                    <table class="table tab-bordered">
                        <thead>
                            <tr>
                                <th>Product Code</th>
                                <th>Product Name</th>
                                <th>Product Remaining Stock</th>         
                                <th>Product Total Stock</th>         
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                            foreach ($stock_detail as $data) { ?>
                                <tr id="<?= $data['product_code'] ?>">
                                    <td><?= $data['product_code'] ?></td>
                                    <td><?= $data['product_name'] ?></td>
                                    <td class="remaining_stock"><?= $data['total_stock'] ?></td>       
                                    <td class="total_stock"><?= $data['total_stock'] ?></td>       
                                </tr>
                            <?php
                            } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
<?php
$script = '
    $(document).ready(function() {
        $(".hide_section").hide();
        hideShowManage();
        $("#tblindentmastersearch-customer_type").on("change", function() {
            hideShowManage();
        });
    });
    function hideShowManage() {
        $(".hide_section").hide();
        var customerType = $("#tblindentmastersearch-customer_type").val();
        if(customerType == "BULKVEN"){
            $(".field-tblindentmastersearch-customer_code").parent("div").show();
            $("#tblindentmastersearch-group_by").val("0").trigger("change");
        } else if(customerType == "DCS") {
            $(".field-tblindentmastersearch-dcs_code").parent("div").show();
            $(".field-tblindentmastersearch-group_by").parent("div").show();
            $(".field-tblindentmastersearch-route_code").parent("div").show();
        }
    }
';
+$this->registerJs($script, View::POS_END, 'indent-dispatch-new-serach');
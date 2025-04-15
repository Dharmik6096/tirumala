<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model app\modules\collection\models\TblMilkCollectionSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="search-filter large-search">

    <?php
    $isIndentApprovalCreditLimitCheck = Yii::$app->general->getUnionConfiguration(Yii::$app->session->get('Unions'), 'is_indent_approval_credit_limit_check', 'PORTAL') == 1 ? TRUE : FALSE;
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
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->dropdownStatic('indent_customer_type', $model, $form, 'form-group padding-right-5', Yii::t('app', 'Customer Type'), false) ?>
    </div>  
    <div class="col-sm-2 hide_section">
        <?= Yii::$app->dropdown->bmc_society($model, $form, 'tblindentmastersearch-bmc_code', 'dcs_code', Yii::t('app', 'Society'), FALSE); ?>
    </div>
    <div class="col-sm-2 hide_section">
        <?= Yii::$app->dropdown->customer_code($model, $form, 'tblindentmastersearch-bmc_code,tblindentmastersearch-customer_type', 'customer_code', Yii::t('app', 'Customer'), FALSE); ?>
    </div>
    <div class="col-sm-2">
        <?php 
        $where = json_encode(['billing_lock_member' => 0]);
        echo Html::hiddenInput('customer_type', 'DCS', ['id' => 'customer_type']);
        echo Html::hiddenInput('applicable_for', 'BMC', ['id' => 'applicable_for']);
        echo Html::hiddenInput('data_lock_bmc', $where, ['id' => 'data_lock_bmc']);
        echo Yii::$app->dropdown->paymentCycle($model, $form, 'tblindentmastersearch-union_code,tblindentmastersearch-bmc_code,customer_type,applicable_for,data_lock_bmc', 'payment_cycle_code', $model->getAttributeLabel('payment_cycle_code'), FALSE, FALSE); ?>
    </div>
    <?php
    if(!$isIndentApprovalCreditLimitCheck){ ?>
        <div class="col-sm-2 hide_section">
            <?= Yii::$app->dropdown->dropdownStatic('indent_group_by', $model, $form, 'form-group padding-right-5', Yii::t('app', 'Group By'), false, 'group_by') ?>
        </div>
    <?php
    } else {
        echo Html::activeHiddenInput($model, 'group_by', ['value' => 1]);
    } ?>
    <div class="col-sm-2">
        <?= Yii::$app->controls->date($model, $form, 'from_date', 'form-group col-sm-2 padding-left-5 padding-right-5', false, false, false, TRUE); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->controls->date($model, $form, 'to_date', 'form-group col-sm-2 padding-left-5 padding-right-5', false, false, false, TRUE); ?>
    </div>
    <div class="col-sm-3 mt23">
        <?= Yii::$app->controls->search(); ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
<?php
$script = '
    $(document).ready(function() {
        hideShowManage();
        $(".field-tblindentmastersearch-from_date").addClass("disabled");
        $(".field-tblindentmastersearch-to_date").addClass("disabled");
        $("#tblindentmastersearch-payment_cycle_code").on("change", function() {
            var dateRange = $("#tblindentmastersearch-payment_cycle_code option:selected").text();
            if (dateRange !== "Select Payment Cycle") {
                var dates = dateRange.split(" to ");
                if (dates.length === 2) {
                    var dates = dateRange.split(" to ");
                    var fromDate = dates[0];
                    var toDate = dates[1];
                    $("#tblindentmastersearch-from_date").val(fromDate);
                    $("#tblindentmastersearch-to_date").val(toDate);
                }
            }
        });

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
        }
    }
';
$this->registerJs($script, View::POS_END, 'indent-approval-serach');
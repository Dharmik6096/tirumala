<?php

use app\components\ActiveForm;
use yii\web\View;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use webvimark\modules\UserManagement\components\GhostHtml;
use yii\data\ArrayDataProvider;

$this->title = Yii::t('app', 'Indent Approval');
?> 
<div class=" no-effect">
    <?php
    $isIndentApprovalCreditLimitCheck = Yii::$app->general->getUnionConfiguration(Yii::$app->session->get('Unions'), 'is_indent_approval_credit_limit_check', 'PORTAL') == 1 ? TRUE : FALSE;
    $form = ActiveForm::begin([
                'id' => 'indent-approval',
    ]);
    ?>
    <div class="">
        <?php echo Html::hiddenInput('operation', 'operation', ['class' => 'set_operation']); ?>
        <?php
        $attribute = [
            [
                'class' => 'kartik\grid\CheckboxColumn',
                'rowSelectedClass' => GridView::TYPE_SUCCESS,
                'headerOptions' => ['class' => 'skip-export'],
                'contentOptions' => ['class' => 'skip-export'],
                'checkboxOptions' => function($model, $key, $index) {
                    $member_code = !empty($model['member_code']) ? $model['member_code'] : 0;
                    $id = $model['dcs_code'] . $member_code . $model['product_code'];
                    return ['class' => 'checkbox group-checkbox parent-checkbox', 'id' => $id, 'value' => ''];
                }
            ],
            [
                'class' => 'kartik\grid\ExpandRowColumn',
                'expandIcon' => '<span class="fa fa-plus"></span>',
                'collapseIcon' => '<span class="fa fa-minus"></span>',
                'expandTitle' => 'View Details',
                'expandAllTitle' => 'View All Details',
                'collapseTitle' => 'Hide Details',
                'collapseAllTitle' => 'Hide All Details',
                'value' => function () {
                    return GridView::ROW_EXPANDED;
                },
                'detail' => function ($model) use ($form, $dataProvider, $searchModel, $indentMaster, $visible) {
                    $dataProvider = new ArrayDataProvider([
                        'allModels' => $model['member_array'],
                        'pagination' => false,
                    ]);
                    return Yii::$app->controller->renderPartial('_approval_grid_new', ['model' => $model, 'form' => $form, 'dataProvider' => $dataProvider, 'searchModel' => $searchModel, 'indentMaster' => $indentMaster, 'visible' => $visible]);
                },
                'headerOptions' => ['class' => 'kartik-sheet-style'],
                'detailRowCssClass' => 'child-grid',
                // 'expandOneOnly' => true,
            ],
            ['attribute' => 'dcs_code', 'label' => Yii::t('app', 'DCS'), 'filter' => FALSE],
            ['attribute' => 'dcs_ref_code', 'label' => Yii::t('app', 'DCS') . ' Ref Code', 'filter' => FALSE],
            ['attribute' => 'dcs_name', 'label' => Yii::t('app', 'DCS Name'), 'filter' => FALSE],
            ['attribute' => 'warehouse_code', 'filter' => FALSE, 'visible' => ($visible ? TRUE : FALSE)],
            ['attribute' => 'product_name', 'label' => Yii::t('app', 'Product'), 'filter' => FALSE],
            ['attribute' => 'qty', 'filter' => FALSE],
            ['attribute' => 'rate', 'filter' => FALSE, 'visible' => ($visible ? TRUE : FALSE)],
            ['attribute' => 'user_name', 'label' => Yii::t('app', 'User Name'), 'filter' => FALSE],
            ['attribute' => 'login_type', 'label' => Yii::t('app', 'Login Type'), 'filter' => FALSE],
            ['attribute' => 'department', 'label' => Yii::t('app', 'Department'), 'filter' => FALSE],
            [
                'attribute' => 'credit_amount', 
                'label' => Yii::t('app', 'Credit Amount'), 
                'filter' => FALSE, 
                'visible' => $isIndentApprovalCreditLimitCheck ? true : false, 
                'contentOptions' => function($model) {
                    $member_code = !empty($model['member_code']) ? $model['member_code'] : 0;
                    $id = $model['dcs_code'] . $member_code . $model['product_code'];
                    return ['class' => 'credit_amount_' . $id, 'data-id' => $id];
                },
            ],
        ];

        $grid_option = [
            'id' => 'indent-approval-grid',
            'attributes' => $attribute,
            'active_column' => false,
            'showPageSummary' => false,
            'default_sorting' => FALSE,
        ];

        Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option, ['indent-approval']);
        ?>
        <div class="panel-footer">
            <?php
            if (!empty($dataProvider->getModels())) {
                echo Html::button(Yii::t('app', 'Approve'), ['class' => 'btn-login btn btn-primary submit', 'id' => 'approve', 'value' => 'approve', 'name' => 'approve']);
                echo Html::button(Yii::t('app', 'Reject'), ['class' => 'btn-login btn btn-primary submit', 'id' => 'reject', 'value' => 'reject', 'name' => 'reject']);
            }
            ?>
            <?= Yii::$app->controls->custombutton('Cancel', 'index-other', '' , 'btn-login'); ?> 
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
<div id="AppInformation"></div>

<?php
$script = '
    var config = ' . ($isIndentApprovalCreditLimitCheck ? 'true' : 'false') . ';
    $(".kv-panel-before").hide();

    $(document).ready(function() {
        $(".integer-input").on("input", function() {
            $(this).val($(this).val().replace(/[^\d]/, ""));
        });
    });

    $(document).on("click", ".parent-checkbox", function() {
        var id = $(this).attr("id");
        $("." + id).prop("checked", this.checked);
    });
    $(document).on("click", ".child-checkbox", function() {
        var id = $(this).data("id");
        var allChecked = $("." + id).filter(":checked").length === $("." + id).length;
        $("#" + id).prop("checked", allChecked);
    });

    $(document).on("blur",".approve_qty", function() {
        var tr_key = $(this).closest("tr").attr("data-id");
        rejectqty(tr_key);
        amount(tr_key);
    });

    function rejectqty(check_key){
        var qty = $("#tblindentmaster-" + check_key + "-qty").val();
        var approve_qty = $("#tblindentmaster-" + check_key + "-approve_qty").val();
        var rejected_qty = parseFloat(qty)-parseFloat(approve_qty);
        if(!isNaN(rejected_qty) && rejected_qty >= 0){
            rejected_qty=rejected_qty.toFixed(2);
            $("#tblindentmaster-" + check_key +"-rejected_qty").val(rejected_qty);                     
        } else {
            bootbox.alert("<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span> Please Approve qty Must be less then Requested qty.</span></div></div>");
            rejected_qty = $("#tblindentmaster-" + check_key + "-rejected_qty").val();
            $("#tblindentmaster-" + check_key + "-approve_qty").val(qty - rejected_qty);
            return false;
        }
    }
    
    function amount(check_key){
        var rate = $("#tblindentmaster-" + check_key + "-rate").val() || 0;
        var approve_qty = $("#tblindentmaster-" + check_key + "-approve_qty").val() || 0;
        var amount = parseFloat(rate)* parseFloat(approve_qty);
        if(!isNaN(amount)){
            amount=amount.toFixed(2);
            $("#tblindentmaster-" + check_key +"-amount").val(amount);
            if(config){
                var id = $("#tblindentmaster-" + check_key + "-approve_qty").data("id");
                checkCreditLimit(id);                     
            }
        }
    }

    function checkCreditLimit(cls){
        var id = $("."+cls).data("id");
        var creditAmount = $(".credit_amount_"+id).text();
        var amount = 0;
        $(".cls-" + cls).each(function() {
            var currentValue = parseFloat($(this).val()) || 0;
            amount += currentValue;
        });
        if(creditAmount != "Not Applicable" && creditAmount < amount) {
            bootbox.alert("<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>Approve quantity cannot exceed the available credit amount limit.</span></div></div>");
            return false;
        }
        return true;
    }
    
    $(".submit").click(function() {
        var id= $(this).attr("value");
        $(".set_operation").val(id);
        var len = $(".child-checkbox:checked").length;
        if(len == 0){
            bootbox.alert("<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span> Please select at least one Record.</span></div></div>");
            return false;
        } else {
            if(config){
                var allLimitsPassed = true;
                $(".cls-amount").each(function() {
                    var id = $(this).attr("data-id");
                    if($("#tblindentmaster-" + id + "-process_approval_code").prop("checked")){
                        if (!checkCreditLimit(id)) {
                            allLimitsPassed = false;
                            return false;
                        }
                    }
                });
                if (!allLimitsPassed) {
                    return false;
                }
            }
            $(".parent-checkbox").prop("disabled", true);
            $("#indent-approval").submit();
        }
    });
         
     $(document).on("click",".view-verification",function(e){
        $("#pageloader").show();
        $("#loadercontent").show();
        var code= $(this).attr("data-val");
        var type= $(this).attr("data-name");
        $.ajax({
            type: "post",
            url: "' . Url::to(['/organisation/tbl-dcs/view-verification']) . '" ,
            data:{"code":code,"type":type},
            success: function(data) {     
                $("#AppInformation").html(data);
                $("#AppInformationModal").modal("toggle"); 
                $("#loadercontent").hide();
                $("#pageloader").hide();
            },    
            error: function(data) {    
                $("#loadercontent").hide();
                $("#pageloader").hide();
            }
        });
    });
  ';
$this->registerJs($script, View::POS_END, 'contact-verification');

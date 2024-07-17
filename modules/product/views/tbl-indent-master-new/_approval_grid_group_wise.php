<?php

use yii\bootstrap\ActiveForm;
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
            // ['class' => 'kartik\grid\CheckboxColumn',
            //     'rowSelectedClass' => GridView::TYPE_SUCCESS,
            //     'headerOptions' => ['class' => 'skip-export'], 'contentOptions' => ['class' => 'skip-export'],
            //     'checkboxOptions' => function($model, $key) {
            //         $disabled = $model['allow_edit'] == '1' ? FALSE : TRUE;
            //         return ['class' => 'checkbox', 'disabled' => $disabled, 'id' => 'tblindentmaster-' . $key . '-process_approval_code', 'value' => $model['process_approval_code']];
            //     }],
            ['attribute' => 'dcs_code', 'label' => Yii::t('app', 'DCS'), 'filter' => FALSE],
            ['attribute' => 'dcs_ref_code', 'label' => Yii::t('app', 'DCS') . ' Ref Code', 'filter' => FALSE],
            ['attribute' => 'dcs_name', 'label' => Yii::t('app', 'DCS Name'), 'filter' => FALSE],
            ['attribute' => 'warehouse_code', 'filter' => FALSE, 'visible' => ($visible ? TRUE : FALSE)],
            ['attribute' => 'product_name', 'label' => Yii::t('app', 'Product'), 'filter' => FALSE],
            ['attribute' => 'qty', 'filter' => FALSE],
            ['attribute' => 'rate', 'filter' => FALSE, 'visible' => ($visible ? TRUE : FALSE)],
            ['attribute' => 'user_name', 'label' => Yii::t('app', 'User Name'), 'filter' => FALSE],
            ['attribute' => 'login_type', 'label' => Yii::t('app', 'Login Type'), 'filter' => FALSE],
            [
                'attribute' => 'credit_amount', 
                'label' => Yii::t('app', 'Credit Amount'), 
                'filter' => FALSE, 
                'visible' => $isIndentApprovalCreditLimitCheck ? true : false, 
                'contentOptions' => function($model) {
                    $id = $model['dcs_code'].$model['member_code'].$model['product_code'];
                    return ['class' => 'credit_amount_' . $id];
                },
                'value' => function(){
                    return 13000;
                }
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
                echo Html::button(Yii::t('app', 'Approve'), ['class' => 'btn btn-primary submit', 'id' => 'approve', 'value' => 'approve', 'name' => 'approve']);
                echo Html::button(Yii::t('app', 'Reject'), ['class' => 'btn btn-primary submit', 'id' => 'reject', 'value' => 'reject', 'name' => 'reject']);
            }
            ?>
            <?= Yii::$app->controls->custombutton('Cancel', 'indent-approval'); ?> 
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
<div id="AppInformation"></div>

<?php
$script = '
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
        var tr_key = $(this).closest("tr").attr("data-key");
        rejectqty(tr_key);
        amount(tr_key);
    });

    function rejectqty(tr_key){   
        var check_key = $("#tblindentmaster-" + tr_key + "-process_approval_code").val();
        var qty = $("#tblindentmaster-" + check_key + "-qty").val();
        var approve_qty = $("#tblindentmaster-" + check_key + "-approve_qty").val();
        var rejected_qty = parseFloat(qty)-parseFloat(approve_qty);
       // var total_qty = parseFloat(approve_qty)+parseFloat(rejected_qty);
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
    
    function amount(tr_key){   
        var check_key = $("#tblindentmaster-" + tr_key + "-process_approval_code").val();
        var rate = $("#tblindentmaster-" + check_key + "-rate").val();
        var approve_qty = $("#tblindentmaster-" + check_key + "-approve_qty").val(); 
        if(rate == "" || isNaN(rate)){
            rate = 0;
        }
        if(approve_qty == "" || isNaN(approve_qty)){
            approve_qty = 0;
        }

        var id = $("#tblindentmaster-" + check_key + "-approve_qty").data("id");
        var creditLimit = $(".credit_amount_"+id).text();

        var amount = parseFloat(rate)* parseFloat(approve_qty);
        if(!isNaN(amount)){
            amount=amount.toFixed(2);
            $("#tblindentmaster-" + check_key +"-amount").val(amount);
            checkCreditLimit(id);                     
        }
    }

    function checkCreditLimit(cls){
        var id = $("."+cls).data("id");
        var creditAmount = $(".credit_amount_"+id).text();
        var amount = 0;
        $("." + cls).each(function() {
            var currentValue = parseFloat($(this).val()) || 0;
            console.log(currentValue);
            amount += currentValue;
            // amount -= currentValue;
            //  if(amount < currentValue){
            //     amount = 0;
            // }
            // amount -= currentValue;
        });
        alert(amount);
        if(creditAmount < amount) {
            bootbox.alert("<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>Approve quantity cannot exceed the available credit amount limit.</span></div></div>");
            return false;
        } 
    }
    
    $(".submit").click(function() {
      var id= $(this).attr("value");
      $(".set_operation").val(id);
        var len = $(".child-checkbox:checked").length;
            if(len == 0){
                bootbox.alert("<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span> Please select at least one Record.</span></div></div>");
                return false;
            } else {
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

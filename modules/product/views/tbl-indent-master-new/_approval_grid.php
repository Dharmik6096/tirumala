<?php

use app\components\ActiveForm;
use yii\web\View;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use webvimark\modules\UserManagement\components\GhostHtml;

$this->title = Yii::t('app', 'Indent Approval');
?> 
<div class=" no-effect">
    <?php
    $form = ActiveForm::begin([
                'id' => 'indent-approval',
    ]);
    ?>
    <div class="">
        <?php echo Html::hiddenInput('operation', 'operation', ['class' => 'set_operation']); ?>
        <?php
        $attribute = [
            ['class' => 'kartik\grid\CheckboxColumn',
                'rowSelectedClass' => GridView::TYPE_SUCCESS,
                'headerOptions' => ['class' => 'skip-export'], 'contentOptions' => ['class' => 'skip-export'],
                'checkboxOptions' => function($model, $key) {
                    $disabled = $model['allow_edit'] == '1' ? FALSE : TRUE;
                    return ['class' => 'checkbox', 'disabled' => $disabled, 'id' => 'tblindentmaster-' . $key . '-process_approval_code', 'value' => $model['process_approval_code']];
                }],
            ['attribute' => 'dcs_ref_code', 'label' => Yii::t('app', 'DCS') . ' Ref Code', 'filter' => FALSE],
            ['attribute' => 'dcs_name', 'label' => Yii::t('app', 'DCS Name'), 'filter' => FALSE],
            ['attribute' => 'code', 'label' => 'Member Code', 'filter' => FALSE, 'visible' => ($visible ? FALSE : TRUE)],
            ['attribute' => 'ref_code', 'label' => 'Ref Code.', 'filter' => FALSE],
            ['attribute' => 'name', 'filter' => FALSE],
            ['attribute' => 'product_name', 'filter' => FALSE],
            ['attribute' => 'qty', 'filter' => FALSE],
            ['attribute' => 'indent_date', 'value' => function($model) {
                    return Yii::$app->controls->view_date($model['indent_date']);
                }, 'filter' => FALSE],
            ['attribute' => 'approve_qty', 'filter' => FALSE,
                'format' => 'raw',
                'value' => function ($model) use ($form, $indentMaster) {
                    $level = $indentMaster->getApprovalLevel($model['indent_code']);
                    if (empty($level)) {
                        $disabled = $model['allow_edit'] == '1' ? FALSE : TRUE;
                        echo Html::activeHiddenInput($indentMaster, '[' . $model['process_approval_code'] . ']qty', ['value' => $model['qty']]);
                        echo Html::activeHiddenInput($indentMaster, '[' . $model['process_approval_code'] . ']rate', ['value' => $model['rate']]);
                        return $form->field($indentMaster, '[' . $model['process_approval_code'] . ']approve_qty')->textInput(['value' => $model['qty'], 'class' => 'form-control number-validate approve_qty', 'disabled' => $disabled])->label(FALSE);
                    } else {
                        return $model['approve_qty'];
                    }
                },
            ],
            ['attribute' => 'rejected_qty', 'filter' => FALSE,
                'format' => 'raw',
                'value' => function ($model) use ($form, $indentMaster) {
                    $disabled = $model['allow_edit'] == '1' ? FALSE : TRUE;
                    if ($model['rejected_qty'] == "") {
                        $model['rejected_qty'] = 0;
                    }
                    return $form->field($indentMaster, '[' . $model['process_approval_code'] . ']rejected_qty')->textInput(['value' => $model['rejected_qty'], 'class' => 'form-control number-validate', 'disabled' => $disabled, 'readonly' => TRUE])->label(FALSE);
                },
            ],
            ['attribute' => 'store_location_name', 'filter' => FALSE, 'visible' => ($visible ? TRUE : FALSE)],
            ['attribute' => 'rate', 'filter' => FALSE, 'visible' => ($visible ? TRUE : FALSE)],
            ['attribute' => 'amount', 'filter' => FALSE, 'visible' => ($visible ? TRUE : FALSE),
                'format' => 'raw',
                'value' => function ($model) use ($form, $indentMaster) {
                    return $form->field($indentMaster, '[' . $model['process_approval_code'] . ']amount')->textInput(['value' => $model['amount'], 'class' => 'form-control number-validate', 'readonly' => TRUE])->label(FALSE);
                },
            ],
//                ['attribute' => 'amount', 'filter' => FALSE, 'visible' => ($visible ? TRUE : FALSE)],
            ['attribute' => 'approve_remarks', 'filter' => FALSE,
                'format' => 'raw',
                'value' => function ($model) use ($form, $indentMaster) {
                    $level = $indentMaster->getApprovalLevel($model['indent_code']);
                    if (empty($level)) {
                        $disabled = $model['allow_edit'] == '1' ? FALSE : TRUE;
                        return $form->field($indentMaster, '[' . $model['process_approval_code'] . ']approve_remarks')->textInput(['value' => $model['approve_remarks'], 'class' => 'form-control', 'disabled' => $disabled])->label(FALSE);
                    } else {
                        return $model['approve_remarks'];
                    }
                },
            ],
            ['attribute' => 'user_name', 'label' => Yii::t('app', 'User Name'), 'filter' => FALSE],
            ['attribute' => 'department', 'label' => Yii::t('app', 'Department'), 'filter' => FALSE],
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
                echo Html::button(Yii::t('app', 'Approve'), ['class' => 'btn btn-primary submit btn-login', 'id' => 'approve', 'value' => 'approve', 'name' => 'approve']);
                echo Html::button(Yii::t('app', 'Reject'), ['class' => 'btn btn-primary submit btn-login ms-1', 'id' => 'reject', 'value' => 'reject', 'name' => 'reject']);
            }
            ?>
            <?= Yii::$app->controls->custombutton('Cancel', 'index-other','','btn-login'); ?> 
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
<div id="AppInformation"></div>

<?php
$script = '
    $(".kv-panel-before").hide();

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
        var amount = parseFloat(rate)* parseFloat(approve_qty);
        if(!isNaN(amount)){
            amount=amount.toFixed(2);
            $("#tblindentmaster-" + check_key +"-amount").val(amount);                     
        }
    }
    
    $(".submit").click(function() {
      var id= $(this).attr("value");
      $(".set_operation").val(id);
        var len = $("input[class=\"checkbox kv-row-checkbox\"]:checked").length;
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

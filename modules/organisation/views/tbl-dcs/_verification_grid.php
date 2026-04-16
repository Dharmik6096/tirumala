<?php

use app\components\ActiveForm;
use yii\web\View;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use app\modules\usermanagement\components\GhostHtml;

$this->title = Yii::t('app', 'Bank Verification');
$kyc_config = Yii::$app->general->getUnionConfiguration(Yii::$app->session->get('Unions'), 'ekyc_required', 'PORTAL');
?>
<div class=" no-effect">
    <?php
    $form = ActiveForm::begin([
                'id' => 'bulk-verification',
    ]);
    ?>
    <div class="">
        <?php echo Html::hiddenInput('operation', 'operation', ['class' => 'set_operation']); ?>
        <?php
        $attribute = [
                ['class' => 'kartik\grid\CheckboxColumn',
                'rowSelectedClass' => GridView::TYPE_SUCCESS,
                'headerOptions' => ['class' => 'skip-export'], 'contentOptions' => ['class' => 'skip-export'],
                'checkboxOptions' => function($model) {
                    return ['class' => 'checkbox', 'value' => $model['code'] . '###' . $model['verify_for']];
                }],
                ['attribute' => 'verify_for'],
                ['attribute' => 'code'],
                ['attribute' => 'parent_ref_code'],
                ['attribute' => 'name', 'value' => 'name'],
                ['attribute' => 'ex_code'],
                ['attribute' => 'ref_code', 'label' => 'Ref Code.', 'filter' => FALSE],
                ['attribute' => 'bank_name', 'filter' => FALSE],
                ['attribute' => 'branch_name', 'filter' => FALSE],
                ['attribute' => 'bank_account_no', 'filter' => FALSE],
                ['attribute' => 'ifsc', 'filter' => FALSE],
                ['attribute' => 'beneficiary_name', 'filter' => FALSE],
                ['attribute' => 'aadhaar_no',
                'value' => function($model) {
                    return Yii::$app->general->decryptData($model['aadhaar_no']) !== FALSE ? Yii::$app->general->decryptData($model['aadhaar_no']) : $model['aadhaar_no'];
                }
                , 'filter' => FALSE],
                ['attribute' => 'remarks',
                'format' => 'raw',
                'contentOptions' => ['class' => 'no_padding_input hide_help_block mb-3-imp'],
                'value' => function ($model, $key, $index) use ($form, $searchModel) {
                    return $form->field($searchModel, '[' . $model['code'] . '@@' . $model['verify_for'] . ']remark')->textInput()->label(FALSE);
                }, 'filter' => false
            ],
        ];

        $grid_option = [
            'id' => 'bulk-verification',
            'attributes' => $attribute,
            'active_column' => false,
            'showPageSummary' => false,
            'default_sorting' => FALSE,
            'actions' => [
                'update' => function ($url, $model) {
                    $id = $model['code'];
                    $type = $model['verify_for'];
                    $class = '';
                    $url = ['/organisation/tbl-dcs/view-verification'];
                    $options = ['data-bs-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'View', 'class' => 'view-verification' . $class, 'data-val' => $id, 'data-name' => $type];
                    return GhostHtml::a_alert('<i class="fa fa-eye"></i>', $url, $options);
                },
                'get-attachments' => function ($url, $model) {
                    $id = $model['code'];
                    $type = $model['verify_for'];
                    $class = '';
                    $url = ['/organisation/tbl-dcs/import-attachements', 'id' => $id];
                    $options = ['data-bs-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Attachments', 'class' => 'get-attachments' . $class, 'data-val' => $id, 'data-name' => $type];
                    return GhostHtml::a_alert('<i class="fa fa-image"></i>', $url, $options);
                },
                'kyc-verification' => function ($url, $model) {
                    $ekyc_config = Yii::$app->general->getUnionConfiguration(Yii::$app->session->get('Unions'), 'ekyc_required', 'PORTAL');
                    if (empty($ekyc_config)) {
                        return false;
                    }
                    $id = $model['code'];
                    $type = $model['verify_for'];
                    $kycStatus = $model['is_kyc_verified'];
                    $colorClass = ($kycStatus == 1) ? 'green' : (($kycStatus == 0 || $kycStatus == '') ? 'gray' : 'red');
                    $iconClass = 'fa fa-university ' . $colorClass;
                    $class = ($kycStatus == 0 || $kycStatus == '') ? '' : ' link-disable';
                    $url = ['/organisation/tbl-dcs/kyc-verification'];
                    $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'KYC Verification', 'class' => 'kyc-verification' . $class, 'data-val' => $id, 'data-name' => $type, 'data-bank_account_no' => $model['bank_account_no'], 'data-ifsc' => $model['ifsc']];
                    return GhostHtml::a_alert('<i class="' . $iconClass . '"></i>', $url, $options);
                },
            ]
        ];

        Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option, ['bank-verification']);
        ?>
        <div class="panel-footer">
            <?php
            if (!empty($dataProvider->getModels()) && $kyc_config != 1) {
                echo Html::button(Yii::t('app', 'Verify'), ['class' => 'btn-login btn btn-primary submit mr-2', 'id' => 'verify', 'value' => 'verify', 'name' => 'verify']);
                echo Html::button(Yii::t('app', 'Reject'), ['class' => 'btn-login btn btn-primary submit', 'id' => 'reject', 'value' => 'reject', 'name' => 'reject']);
            }
            ?>
            <?= Yii::$app->controls->custombutton('Cancel', 'master-verification', '', 'btn-login'); ?> 
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
<div id="AppInformation"></div>
<div id="AttachmentView"></div>
<?php
$script = '
    $(".kv-panel-before").hide();
    $(".submit").click(function() {
      var id= $(this).attr("value");
      $(".set_operation").val(id);
        var len = $("input[class=\"checkbox kv-row-checkbox\"]:checked").length;
            if(len == 0){
                bootbox.alert("<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span> Please select at least one Record.</span></div></div>");
                return false;
            } else {
            $("#bulk-verification").submit();
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
    $(document).on("click",".get-attachments",function(e){
        $("#pageloader").show();
        $("#loadercontent").show();
        var code= $(this).attr("data-val");
        var type= $(this).attr("data-name");
        $.ajax({
            type: "post",
            url: "' . Url::to(['/organisation/tbl-dcs/get-attachments']) . '" ,
            data:{"code":code,"type":type},
            success: function(data) {     
                $("#AttachmentView").html(data);
                $("#AttachmentViewModal").modal("toggle"); 
                $("#loadercontent").hide();
                $("#pageloader").hide();
            },    
            error: function(data) {    
                $("#loadercontent").hide();
                $("#pageloader").hide();
            }
        });
    });
    
    $(document).on("click",".kyc-verification",function(e){
        $("#pageloader").show();
        $("#loadercontent").show();
        var code= $(this).attr("data-val");
        var type= $(this).attr("data-name");
        var bankAccountNo = $(this).attr("data-bank_account_no");
        var ifsc = $(this).attr("data-ifsc");
        $.ajax({
            type: "get",
            url: "' . Url::to(['/organisation/tbl-dcs/kyc-verification']) . '" ,
            data:{"code":code,"type":type,"bank_account_no":bankAccountNo,"ifsc":ifsc},
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
$this->registerJs($script, View::POS_END, 'master-verification');

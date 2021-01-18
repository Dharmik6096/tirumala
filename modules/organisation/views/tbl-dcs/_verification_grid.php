<?php

use yii\bootstrap\ActiveForm;
use yii\web\View;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use webvimark\modules\UserManagement\components\GhostHtml;

$this->title = Yii::t('app', 'Master Verification');
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
                    return ['class' => 'checkbox', 'value' => $model['code'] . '###' . $model['verifie_for']];
                }],
            ['attribute' => 'verifie_for'],
            ['attribute' => 'code'],
            ['attribute' => 'name', 'value' => 'name'],
            ['attribute' => 'ex_code'],
            ['attribute' => 'ref_code', 'label' => 'Ref Code.', 'filter' => FALSE],
            ['attribute' => 'bank_code', 'filter' => FALSE],
            ['attribute' => 'branch_code', 'filter' => FALSE],
            ['attribute' => 'bank_account_no', 'filter' => FALSE],
            ['attribute' => 'ifsc', 'filter' => FALSE],
            ['attribute' => 'beneficiary_name', 'filter' => FALSE],
            ['attribute' => 'aadhaar_no',
                'value' => function($model) {
                    return Yii::$app->general->decryptData($model['aadhaar_no']) !== FALSE ? Yii::$app->general->decryptData($model['aadhaar_no']) : $model['aadhaar_no'];
                }
                , 'filter' => FALSE],
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
                    $type = $model['verifie_for'];
                    $class = '';
                    $url = ['/organisation/tbl-dcs/view-verification'];
                    $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'View', 'class' => 'view-verification' . $class, 'data-val' => $id, 'data-name' => $type];
                    return GhostHtml::a_alert('<i class="fa fa-eye"></i>', $url, $options);
                },
                'get-attachments' => function ($url, $model) {
                    $id = $model['code'];
                    $type = $model['verifie_for'];
                    $class = '';
                    $url = ['/organisation/tbl-dcs/import-attachements', 'id' => $id];
                    $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Attachments', 'class' => 'get-attachments' . $class, 'data-val' => $id, 'data-name' => $type];
                    return GhostHtml::a_alert('<i class="fa fa-image"></i>', $url, $options);
                },
            ]
        ];

        Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
        ?>
        <div class="panel-footer">
            <?php
            if (!empty($dataProvider->getModels())) {
                echo Html::button(Yii::t('app', 'Verify'), ['class' => 'btn btn-primary submit', 'id' => 'verify', 'value' => 'verify', 'name' => 'verify']);
                echo Html::button(Yii::t('app', 'Reject'), ['class' => 'btn btn-primary submit', 'id' => 'reject', 'value' => 'reject', 'name' => 'reject']);
            }
            ?>
            <?= Yii::$app->controls->custombutton('Cancel', 'master-verification'); ?> 
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
      ';
$this->registerJs($script, View::POS_END, 'master-verification');

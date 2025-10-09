<?php
$this->title = 'Upload Documents';

use app\components\ActiveForm;
use yii\helpers\Html;
use demogorgorn\ajax\AjaxSubmitButton;
use yii\web\JsExpression;
use yii\helpers\Url;

$urls = '';
if ($master_type == 'member') {
    $urls = ['/dcsoperation/tbl-member/member-document-upload', 'id' => $model->member_code];
} else if ($master_type == 'dcs') {
    $urls = ['/organisation/tbl-dcs/dcs-document-upload', 'id' => $model->dcs_code];
} else if ($master_type == 'bmc') {
    $urls = ['/organisation/tbl-dcs-bmc/bmc-document-upload', 'id' => $model->bmc_code];
} else if ($master_type == 'mcc') {
    $urls = ['/organisation/tbl-mcc-plant/mcc-document-upload', 'id' => $model->mcc_plant_code];
} else if ($master_type == 'transporter') {
    $urls = ['/transporter/tbl-transporter/transporter-document-upload', 'id' => $model->transporter_code];
} else if ($master_type == 'vehicle') {
    $urls = ['/transporter/tbl-vehicle-master/vehicle-document-upload', 'id' => $model->vehicle_code];
} else if ($master_type == 'provisional_customer') {
    $urls = ['/organisation/tbl-customer-master-provisional/document-upload', 'id' => $model->customer_provisional_code];
} else if ($master_type == 'provisional_dcs') {
    $urls = ['/organisation/tbl-dcs-provisional/document-upload', 'id' => $model->dcs_provisional_code];
} else if ($master_type == 'customer') {
    $urls = ['/organisation/tbl-customer-master/customer-document-upload', 'id' => $model->customer_code];
} else {
    $urls = ['/organisation/tbl-plant/plant-document-upload', 'id' => $model->plant_code];
}
?>

<div class="panel-heading">
    <ul class="progressbar">
        <li><?= $this->title ?></li>
    </ul>
</div>
<?php
$message_flage = true;
if (!empty($doc_model)) {
    foreach ($doc_model as $doc) {
        if ($doc->is_mandate == 1 && !isset($doc->attachment_code) && $message_flage) {
            $message_flage = false;
            ?>
            <div class="alert alert-danger warning-single-box">* Mandate Document Upload Pending.</div>
            <?php
        }
    }
}
?>
<div class="panel-body">
    <?php
    if ($master_type == 'member') {
        $code = $model['member_code'];
        $ex_code = $model['ex_member_code'];
        $ref_code = $model['ref_code'];
        $name = $model['member_name'];
    } else if ($master_type == 'dcs') {
        $code = $model['dcs_code'];
        $ex_code = $model['dcs_code_ex'];
        $ref_code = $model['ref_code'];
        $name = $model['dcs_name'];
    } else if ($master_type == 'bmc') {
        $code = $model['bmc_code'];
        $ex_code = $model['bmc_code_ex'];
        $ref_code = $model['ref_code'];
        $name = $model['bmc_name'];
    } else if ($master_type == 'mcc') {
        $code = $model['mcc_plant_code'];
        $ex_code = $model['mcc_plant_code_ex'];
        $ref_code = $model['ref_code'];
        $name = $model['name'];
    } else if ($master_type == 'transporter') {
        $code = $model['transporter_code'];
        $name = $model['transporter_name'];
    } else if ($master_type == 'vehicle') {
        $code = $model['vehicle_code'];
        $wef_date = $model['wef_date'];
        $billing_method = $model['billing_method'];
        $parsing_no = $model['parsing_no'];
    } else if ($master_type == 'provisional_customer') {
        $code = $model['customer_provisional_code'];
        $ex_code = $model['customer_code_ex'];
        $ref_code = $model['ref_code'];
        $name = $model['customer_name'];
    } else if ($master_type == 'provisional_dcs') {
        $code = $model['dcs_provisional_code'];
        $ex_code = $model['dcs_code_ex'];
        $ref_code = $model['ref_code'];
        $name = $model['dcs_name'];
    } else if ($master_type == 'customer') {
        $code = $model['customer_code'];
        $ex_code = $model['customer_code_ex'];
        $ref_code = $model['ref_code'];
        $name = $model['customer_name'];
    } else {
        $code = $model['plant_code'];
        $ex_code = $model['plant_code_ex'];
        $ref_code = $model['ref_code'];
        $name = $model['name'];
    }
    ?>
    <table class="table table-bordered table-striped table-language">
        <thead>
            <tr>
                <?php if (isset($code)) {
                    ?>
                    <th><?= Yii::t('app', 'Code') ?></th>
                    <td><?= $code; ?></td>
                <?php }
                ?>
                <?php if (isset($name)) {
                    ?>
                    <th><?= Yii::t('app', 'Name') ?></th>
                    <td><?= $name; ?></td>
                <?php }
                ?>
                <?php if (isset($parsing_no)) {
                    ?>
                    <th><?= Yii::t('app', 'Parsing No.') ?></th>
                    <td><?= $parsing_no; ?></td>
                <?php }
                ?>
            </tr>
            <tr>
                <?php if (isset($ex_code)) {
                    ?>
                    <th><?= Yii::t('app', 'Ex Code') ?></th>
                    <td><?= $ex_code; ?></td>
                <?php }
                ?>
                <?php if (isset($ref_code)) {
                    ?>
                    <th><?= Yii::t('app', 'Ref Code') ?></th>
                    <td><?= $ref_code; ?></td>
                <?php }
                ?>
            </tr>
            <tr>
                <?php if (isset($wef_date)) {
                    ?>
                    <th><?= Yii::t('app', 'WEF Date') ?></th>
                    <td><?= $wef_date; ?></td>
                <?php }
                ?>
                <?php if (isset($billing_method)) {
                    ?>
                    <th><?= Yii::t('app', 'Billing Method') ?></th>
                    <td><?= $billing_method; ?></td>
                <?php }
                ?>
            </tr>
        </thead>
        <tbody>
            <tr>

            </tr>
        </tbody>
    </table>

    <div class="col-md-12 padding_10_0 theme-box">
        <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 margin-bottom-10 clearfix">
            <h4 class="theme-box-heading"><?php echo Yii::t('app', 'Document List') ?></h4>
        </div>
        <div class="form-grid">
            <?php
            if (!empty($doc_model)) {
                $form = ActiveForm::begin([
                            'options' => ['id' => 'create-document-form',
                                'enctype' => 'multipart/form-data'
                            ],
                            'validateOnBlur' => false,
                            'validateOnChange' => FALSE,
                            'enableClientValidation' => true,
                            'validateOnSubmit' => true,
                            'fieldConfig' => [
                ]]);
                ?>
                <?= $form->errorSummary($doc_model) ?>

            <div class="row d-block">
                    <div class="col-sm-12">
                        <table class="table table-bordered table-striped table-main table-language table-rate">
                            <tbody>
                                <?php foreach ($doc_model as $key => $doc) { ?>
                                    <tr>
                                        <td width='60%'><?= $doc->doc_name; ?></td>
                                        <td width='40%' class="hide_help_block">
                                            <?php
                                            $accept = !empty($doc->attachment_type) ? $doc->attachment_type : 'application/pdf,image/jpeg,.docx';
                                            echo $form->field($doc, '[' . $key . ']file_name')->fileInput(['accept' => $accept])->label(FALSE);
                                            ?>

                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-sm-12 shortcut-main mt10" shortcut="true" display_shortcut="false" hilight_shortcut="false">
                        <div class="form-group">
                            <?php if ($master_type == 'provisional_dcs' || $master_type == 'provisional_customer') { ?>
                                <?= Html::hiddenInput('operation', 'operation', ['class' => 'set_operation']); ?>
                                <?= Html::button(Yii::t('app', 'Re-Route'), ['class' => 'btn btn-login  btn-primary apply-shortcut', 'data-bs-toggle' => 'modal', 'data-bs-target' => '#ProvisionalModal']) ?>
                            <?php } ?>
                            <?php
                            AjaxSubmitButton::begin([
                                'label' => Yii::t('app', 'Save'),
                                'useWithActiveForm' => 'create-document-form',
                                'ajaxOptions' => [
                                    'type' => 'POST',
                                    'url' => Url::to($urls),
                                    'processData' => false,
                                    'contentType' => false,
                                    'data' => new JsExpression("(function(){
                                        var mainFormData = new FormData($('#create-document-form')[0]);
                                        var remarks = \$('[name\$=\"[remarks]\"]').val();
                                        if (remarks != '') {
                                            mainFormData.append('remarks', remarks);
                                        }
                                        return mainFormData;
                                    })()"),
                                    'beforeSend' => new JsExpression("function(data){
                                                $('#loadercontent').show();
                                                $('#pageloader').show();
                                                }"),
                                    'success' => new JsExpression('function(data){
                                                                var data=$.parseJSON(data);
                                                                $("#loadercontent").hide();
                                                                $("#pageloader").hide();
                                                                if (data.status == "success"){ 
                                                                    $("#loadercontent").hide();
                                                                    $("#pageloader").hide();
                                                                    window.location=data.msg;                                                                     
                                                                }else{                                                       
                                                                    $("#loadercontent").hide();
                                                                    $("#pageloader").hide();                                                                   
                                                                    bootbox.alert("<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>"+data.msg+"</span></div></div>");
                                                                }
                                                 }'),
                                ],
                                'options' => ['class' => 'btn-login btn btn-default btn-raised saveBtn',
                                    'type' => 'submit'],
                            ]);
                            AjaxSubmitButton::end();
                            ?>
                            <?= Yii::$app->controls->reset(); ?>
                            <?= Yii::$app->controls->custombutton('cancel', 'index','','btn-login'); ?>
                        </div>  
                    </div>
                </div>
                <?php
                ActiveForm::end();
            }
            ?>
        </div>
    </div>
</div>
<?php if ($master_type == 'provisional_dcs' || $master_type == 'provisional_customer') { ?>
    <?=
    $this->render('@app/modules/document/views/tbl-attachment/_reroute', [
        'model' => $model,
    ])
    ?>
<?php } ?>


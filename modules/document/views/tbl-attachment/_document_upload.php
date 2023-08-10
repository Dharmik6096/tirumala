<?php
$this->title = 'Upload Documents';

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use demogorgorn\ajax\AjaxSubmitButton;
use yii\web\JsExpression;
use yii\helpers\Url;

$urls = '';
if ($master_type == 'member') {
    $urls = ['/dcsoperation/tbl-member/member-document-upload', 'id' => $model->member_code];
}
?>

<div class="panel-heading">
    <ul class="progressbar">
        <li>Upload Documents</li>
    </ul>
</div>
<?php
//$message_flage = true;
//if (!empty($doc_model)) {
//    $attachement_codes = array_column($attach_doc_mappings, 'doc_id');
//    foreach ($doc_model as $doc) {
//        if ($doc->is_mandate == 1 && $message_flage && !in_array($doc->doc_id, $attachement_codes)) {
//            $message_flage = false;
//            ?>
            <!--<div class="alert alert-danger warning-single-box">* Mandate Document Upload Pending.</div>-->
            <?php
//        }
//    } 
//}
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
                <th><?= Yii::t('app', 'Code') ?></th>
                <td><?= $code; ?></td>
                <th><?= Yii::t('app', 'Ex Code') ?></th>
                <td><?= $ex_code; ?></td>
            </tr>
            <tr>
                <th><?= Yii::t('app', 'Ref Code') ?></th>
                <td><?= $ref_code; ?></td>
                <th><?= Yii::t('app', 'Name') ?></th>
                <td><?= $name; ?></td>
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

                <div class="row">
                    <div class="col-sm-12">
                        <table class="table table-bordered table-striped table-main table-language table-rate">
                            <tbody>
                                <?php foreach ($doc_model as $key => $doc) { ?>
                                    <tr>
                                        <td width='60%'><?= $doc->doc_name; ?></td>
                                        <td width='40%' class="hide_help_block">
                                            <?= Html::activeHiddenInput($doc, '[' . $key . ']attachment_code'); ?>
                                            <?= Html::activeHiddenInput($doc, '[' . $key . ']mapping_id'); ?>
                                            <?= Html::activeHiddenInput($doc, '[' . $key . ']doc_id'); ?>
                                            <?php
                                            $accept = !empty($doc->doc_ext) ? $doc->doc_ext : 'application/pdf,image/jpeg,.docx';
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
                            <?php
                            AjaxSubmitButton::begin([
                                'label' => Yii::t('app', 'Save'),
                                'useWithActiveForm' => 'create-document-form',
                                'ajaxOptions' => [
                                    'type' => 'POST',
                                    'url' => Url::to($urls),
                                    'processData' => false,
                                    'contentType' => false,
                                    'data' => new JsExpression("new FormData($('#create-document-form')[0])"),
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
                                'options' => ['class' => 'btn btn-default btn-raised',
                                    'type' => 'submit'],
                            ]);
                            AjaxSubmitButton::end();
                            ?>
                            <?= Yii::$app->controls->reset(); ?>
                            <?= Yii::$app->controls->custombutton('cancel', 'index'); ?>
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


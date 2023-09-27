<?php
$this->title = 'Upload Documents';

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use kartik\detail\DetailView;
use demogorgorn\ajax\AjaxSubmitButton;
use yii\web\JsExpression;
use yii\helpers\Url;
?>

<div class="panel panel-default panel-main hide-grid-settings">
    <div class="panel-heading">
        <ul class="progressbar">
            <li class="inactive"><?= Yii::t('app', 'dcs_code') ?> Provisional No. <?= $model->dcs_provisional_code ?>  > </li>
            <li>  Upload Documents</li>
        </ul>
    </div>
    <div class="panel-body">
        <div class="table-responsive">

            <?php
            $attributes = [
                [
                    'columns' => [
                        [
                            'attribute' => 'dcs_provisional_code',
                            'valueColOptions' => ['style' => 'width:15%']
                        ],
//                            [
//                            'attribute' => 'dcs_code',
//                            'valueColOptions' => ['style' => 'width:15%']
//                        ],
                        [
                            'attribute' => 'ref_code',
                            'valueColOptions' => ['style' => 'width:15%']
                        ],
                    ],
                ],
                [
                    'columns' => [
                        [
                            'attribute' => 'dcs_code_ex',
                            'valueColOptions' => ['style' => 'width:15%']
                        ],
                        [
                            'attribute' => 'dcs_name',
                            'valueColOptions' => ['style' => 'width:15%']
                        ],
                    ],
                ],
            ];

            echo DetailView::widget([
                'model' => $model,
                'attributes' => $attributes,
                'mode' => 'view',
                'bordered' => true,
                'striped' => false,
                'responsive' => true,
                'hAlign' => 'left',
                'vAlign' => 'top',
                'deleteOptions' => [// your ajax delete parameters
                    'params' => ['id' => 1000, 'kvdelete' => true],
                ],
                'container' => ['id' => 'kv-demo'],
            ]);
            ?>
        </div>

        <div class="col-md-12 padding_10_0 theme-box">
            <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 margin-bottom-10 clearfix">
                <?php if (!empty($doc_model)) { ?>
                    <h4 class="theme-box-heading"><?php echo Yii::t('app', 'Document List') ?></h4>
                <?php } ?>
            </div>
            <div class="form-grid">
                <?php
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

                if (!empty($doc_model)) {
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
                        <?php
                    }
                    ?>
                    <div class="col-sm-12 shortcut-main mt10" shortcut="true" display_shortcut="false" hilight_shortcut="false">
                        <div class="form-group">
                            <?php
                            AjaxSubmitButton::begin([
                                'label' => Yii::t('app', 'Save'),
                                'useWithActiveForm' => 'create-document-form',
                                'ajaxOptions' => [
                                    'type' => 'POST',
                                    'url' => Url::to(['document-upload', 'id' => $model->dcs_provisional_code]),
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
                ?>
            </div>
        </div>
    </div>
</div>


<?php
$this->title = 'Upload Documents';

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use kartik\detail\DetailView;
use demogorgorn\ajax\AjaxSubmitButton;
use yii\web\JsExpression;
use yii\helpers\Url;
use yii\web\View;

$configValue = Yii::$app->general->getUnionConfiguration($model->union_code, 'workflow_require', 'PORTAL');
?>

<div class="panel panel-default panel-main hide-grid-settings">
    <div class="panel-heading">
        <ul class="progressbar">
            <li class="inactive">Member Provisional No. <?= $model->provisional_member_code ?>  > </li>
            <li>  Upload Documents > </li>
            <li>  <?php echo $model->application_no; ?></li>
        </ul>
    </div>
    <div class="panel-body">
        <div class="table-responsive">

            <?php
            $attributes = [
                    [
                    'columns' => [
                            [
                            'attribute' => 'provisional_member_code',
                            'valueColOptions' => ['style' => 'width:15%']
                        ],
                            [
                            'attribute' => 'member_code',
                            'valueColOptions' => ['style' => 'width:15%']
                        ],
                            [
                            'attribute' => 'ref_code',
                            'valueColOptions' => ['style' => 'width:15%']
                        ],
                    ],
                ],
                    [
                    'columns' => [
                            [
                            'attribute' => 'ex_member_code',
                            'valueColOptions' => ['style' => 'width:15%']
                        ],
                            [
                            'attribute' => 'pro_ex_member_code',
                            'valueColOptions' => ['style' => 'width:15%']
                        ],
                            [
                            'attribute' => 'member_name',
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
                                                <?php
                                                $accept = !empty($doc->attachment_type) ? $doc->attachment_type : 'application/pdf,image/jpeg,.docx';
                                                echo $form->field($doc, '[' . $key . ']file_name')->fileInput(['accept' => $accept])->label(FALSE);
                                                ?>

                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                            <div class="col-sm-4 mt15">
                                <?= $form->field($model, 'is_operator_aggre', ['checkboxTemplate' => "<div class='checkbox'>{input}{beginLabel}{labelTitle}{endLabel}</div>{error}{hint}",])->checkbox(); ?>
                            </div>
                        </div>
                        <div class="col-sm-12 shortcut-main mt10" shortcut="true" display_shortcut="false" hilight_shortcut="false">
                            <div class="form-group">
                                <?php
                                echo Html::hiddenInput('request_button', 'save', ['id' => 'request_button']);
                                if ($configValue == 1) {
                                    ?>
                                    <?= Html::hiddenInput('operation', 'operation', ['class' => 'set_operation']); ?>
                                    <?= Html::button(Yii::t('app', 'Re-Route'), ['class' => 'btn btn-primary apply-shortcut', 'data-toggle' => 'modal', 'data-target' => '#ProvisionalModal',]) ?>
                                    <?php
                                }
                                AjaxSubmitButton::begin([
                                    'label' => Yii::t('app', 'Save'),
                                    'id' => 'request_approve',
                                    'useWithActiveForm' => 'create-document-form',
                                    'ajaxOptions' => [
                                        'type' => 'POST',
                                        'url' => Url::to(['document-upload', 'id' => $model->provisional_member_code]),
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
                                    'options' => ['class' => 'btn btn-default btn-raised saveBtn',
                                        'type' => 'submit'],
                                ]);
                                AjaxSubmitButton::end();
                                if ($configValue == 0) {
                                    echo Html::button(Yii::t('app', 'Save & Approve'), ['class' => 'btn btn-primary apply-shortcut', 'name' => 'submitBtn', 'value' => 'approve', 'id' => 'approve']);
                                }
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
        <div class="clearfix"></div>
        <div class="row">
            <div class="col-md-12 view-subtitle padding_10_0 theme-box ">
                <div class="col-sm-12">
                    <?=
                    $this->render('/../../document/views/tbl-attachment/_attachment_grid', [
                        'dataProvider' => $dataProvider,
                        'attachment' => $attachment,
                    ])
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php if ($configValue == 1) { ?>
    <?=
    $this->render('@app/modules/document/views/tbl-attachment/_reroute', [
        'model' => $model,
    ])
    ?>
<?php } ?>
<?php
$script = "
    $('#approve').click(function() {
        $('#request_button').val('approve');
        $('#request_approve').trigger('click');
    });
";
$this->registerJs($script, View::POS_END, 'document');

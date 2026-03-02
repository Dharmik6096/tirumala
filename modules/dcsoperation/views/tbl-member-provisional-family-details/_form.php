<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\web\View;
use yii\helpers\Url;
use demogorgorn\ajax\AjaxSubmitButton;
use yii\web\JsExpression;

$form = ActiveForm::begin(['id' => 'add-famliy-detail']);
echo $form->errorSummary($memberFamilyDetail);
?>
<div class="row">
    <div class="col-sm-12">

        <div class="panel panel-default">
            <?php
            $class = '';
            if (empty($tabview)) {
                $class = ' collapse';
                ?>
                <div class="panel-heading">
                    <div class="panel-title">
                        <a class="pull-right" data-toggle="collapse" href="#familyDetailsCollapse">
                            <i id="collapse" class="fa fa-chevron-up"></i>
                        </a>
                        <?= Yii::t('app', 'Family Details') ?>
                    </div>
                </div>
            <?php }
            ?>

            <div id="familyDetailsCollapse" class="panel-collapse <?= $class ?>">
                <div class="panel-body">
                    <?= Html::activeHiddenInput($memberFamilyDetail, 'member_provisional_family_detail_code', ['id' => 'tblmemberprovisionalfamilydetails-member_provisional_family_detail_code']) ?>
                    <?= Html::activeHiddenInput($memberFamilyDetail, 'provisional_member_code', ['id' => 'provisional_member_code']) ?>
                    <?= Html::activeHiddenInput($memberFamilyDetail, 'union_code', ['id' => 'provisional_member_code', 'value' => $model->union_code]) ?>
                    <div class="col-sm-2">
                        <?= $form->field($memberFamilyDetail, 'family_member_name')->textInput() ?>
                    </div>
                    <div class="col-sm-2">
                        <?= $form->field($memberFamilyDetail, 'local_family_member_name')->textInput() ?>
                    </div>
                    <div class="col-sm-2">
                        <?= Yii::$app->controls->date($memberFamilyDetail, $form, 'dob', '', true); ?>
                    </div>
                    <div class="col-sm-2 number-validate">
                        <?= $form->field($memberFamilyDetail, 'age')->textInput() ?>
                    </div>
                    <div class="col-sm-2">
                        <?= Yii::$app->dropdown->dropdown('gender', $memberFamilyDetail, $form, '', 'Gender'); ?>
                    </div>
                    <div class="col-sm-2">
                        <?= Yii::$app->dropdown->dropdown('relation_code', $memberFamilyDetail, $form, '', $memberFamilyDetail->getAttributeLabel('relationship_code')); ?>
                    </div>
                    <div class="col-sm-2">
                        <?= $form->field($memberFamilyDetail, 'remarks')->textarea() ?>
                    </div>
                    <div class="col-sm-2 mt15">
                        <?= $form->field($memberFamilyDetail, 'is_nominee', ['checkboxTemplate' => "<div class='checkbox'>{input}{beginLabel}{labelTitle}{endLabel}</div>{error}{hint}",])->checkbox(); ?>
                    </div>
                    <div class="col-sm-2">
                        <?= $form->field($memberFamilyDetail, 'nominee_address')->textarea() ?>
                    </div>
                    <div class="col-sm-2">
                        <?= $form->field($memberFamilyDetail, 'local_nominee_address')->textarea() ?>
                    </div>
                    <div class="col-sm-2">
                        <?= $form->field($memberFamilyDetail, 'guardian_name')->textInput() ?>
                    </div>
                    <div class="col-sm-2">
                        <?= $form->field($memberFamilyDetail, 'local_guardian_name')->textInput() ?>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-sm-12 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
                        <div class="form-group">
                            <?php
                            AjaxSubmitButton::begin([
                                'label' => Yii::t('app', 'ADD'),
                                'useWithActiveForm' => 'add-famliy-detail',
                                'ajaxOptions' => [
                                    'type' => 'POST',
                                    'url' => Url::to(['create-family']),
                                    'beforeSend' => new JsExpression("function(data){
                                                $('#loadercontent').show();
                                                $('#pageloader').show();
                                                }"),
                                    'success' => new JsExpression('function(data){
                                                                $(\'#loadercontent\').hide();
                                                                $(\'#pageloader\').hide();
                                                                if (data.status == "error"){ 
                                                                    $(".help-block").text("");
                                                                    $(".form-group").removeClass("has-error");
                                                                    var errorMessage = "";
                                                                    $.each(data.errors, function(key, value) {
                                                                        errorMessage += value + "<br>";
                                                                        $(".field-" + key + " .help-block").text(value);
                                                                        $("#" + key).closest(".form-group").addClass("has-error");
                                                                    });
                                                                    bootbox.alert({
                                                                        title: "Validation Error",
                                                                        message: errorMessage
                                                                    });
                                                                } else {
                                                                    var successMessage = data.message ? data.message : "' . Yii::t('app', 'Member Family Details Successfully Created') . '";
                                                                   if($("#tblmemberprovisionalfamilydetails-member_provisional_family_detail_code").val()!=""){
                                                                    successMessage = "' . Yii::t('app', 'Member Family Details Successfully Updated') . '";
                                                                        }
                                                                    $(".help-block").text("");
                                                                    $(".form-group").removeClass("has-error");   
                                                                    $(\'#loadercontent\').hide();
                                                                    $(\'#pageloader\').hide();
                                                                    $.pjax.reload({container: "#member-family-details-grid"});
                                                                    $("#add-famliy-detail").trigger("reset");
                                                                    $("#tblmemberprovisionalfamilydetails-gender_code").val("").trigger("change");
                                            bootbox.alert("<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-success\'><i class=\'fa fa-check\'></i></div><span>"+successMessage+"</span></div></div>");
                                            }
                                            } '),
                                ],
                                'options' => ['class' => 'btn btn-default btn-raised submit_family',
                                    'type' => 'submit'],
                            ]);
                            AjaxSubmitButton::end();
                            ?>
                            <?= Yii::$app->controls->reset(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>
<?php
if ($tabview) {
    $previews = '';
    $nAddressProof = Yii::$app->general->getDisplayDocumentLink($model->provisional_member_code, 'tbl_member_provisional', ['nAddressProof'], 'image');
    $previews .= '<div class="doc-preview-card"><span class="doc-preview-header">' . Yii::t('app', 'Nominee Address Proof') . '</span>' . (trim($nAddressProof) != '' ? $nAddressProof : '<div class="no-attachment">' . Yii::t('app', 'No Attachment') . '</div>') . '</div>';

    $nAddressProofback = Yii::$app->general->getDisplayDocumentLink($model->provisional_member_code, 'tbl_member_provisional', ['nAddressProofback'], 'image');
    $previews .= '<div class="doc-preview-card"><span class="doc-preview-header">' . Yii::t('app', 'Nominee Address Proof Back') . '</span>' . (trim($nAddressProofback) != '' ? $nAddressProofback : '<div class="no-attachment">' . Yii::t('app', 'No Attachment') . '</div>') . '</div>';
    ?>
    <div class="row theme_border_left theme_border_right theme_border_bottom">
        <div class="col-md-12 padding_10_0">
            <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix margin_bottom_10">
                <h4 class="theme-box-heading"><?= Yii::t('app', 'Document Previews') ?></h4>
            </div>
            <div class="doc-preview-container">
                <?= $previews ?>
            </div>
        </div>
    </div>

<?php } ?>
<div id="familyDetailsgrid" class="col-sm-12">
    <div class="form-grid rebind_grid hide-grid-settings collapse_grid">
        <?=
        $this->render('_form_grid', [
            'memberFamilyDataProvider' => $memberFamilyDataProvider,
            'memberFamilySearchModel' => $memberFamilySearchModel,
        ])
        ?>
    </div>
</div>
<?php
$script = "
    //collapse icon up & down   
   $(document).ready(function() {
    $('#collapse').addClass('fa fa-chevron-up');
                                //$('#familyDetailsgrid').hide(); 

                                $('#familyDetailsCollapse').on('show.bs.collapse', function() {
                                $('#collapse').removeClass('fa fa-chevron-up').addClass('fa fa-chevron-down');
                                //$('#familyDetailsgrid').show(); 
                                });

                                $('#familyDetailsCollapse').on('hide.bs.collapse', function() {
                                $('#collapse').removeClass('fa fa-chevron-down').addClass('fa fa-chevron-up');
                                // $('#familyDetailsgrid').hide(); 
                                });
                                });
                                ";
$this->registerJs($script, View::POS_END, 'member-provisional-family-details');
?>

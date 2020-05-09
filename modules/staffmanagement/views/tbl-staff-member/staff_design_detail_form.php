<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\View;
use yii\jui\DatePicker;
use kartik\widgets\Select2;
use yii\helpers\Url;
use demogorgorn\ajax\AjaxSubmitButton;
use yii\web\JsExpression;
?>


<?php
$form = ActiveForm::begin(['options' => [
                'class' => 'mom_detail',
                'field-class' => 'form-group col-sm-3',
            ],
            'validateOnBlur' => TRUE,
            'validateOnEnter' => FALSE,
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
        ]);
?>
<div class="panel-subheading">
    <h5 class="panel-subtitle"></h5>
    <div class="row">
        <div class="table-responsive">
            <table class="table table-bordered web_theme_table table-striped table-main table-language">
                <thead>
                    <tr>
                        <th><?php echo $model->getAttributeLabel('staff_member_code') ?></th>
                        <th><?php echo $model->getAttributeLabel('designation_code') ?></th>
                        <th><?php echo $model->getAttributeLabel('tenure_from_date') ?></th>
                        <th><?php echo $model->getAttributeLabel('tenure_to_date') ?></th>
                        <th><?php echo $model->getAttributeLabel('remark') ?></th>
                        <th><?= Yii::t('app', 'Action') ?></th>
                    </tr>
                </thead>
                <tbody class="appendRaw">
                    <?php
                    $class = $allowUpdate ? '' : 'disabled none_pointer_events';
                    if (!empty($existData)) {
                        foreach ($existData as $updateData) {
                            ?>  
                            <tr class="<?= $updateData->staff_member_designation_code ?>">
                                <?= Html::activeHiddenInput($updateData, '[' . $updateData->staff_member_designation_code . ']staff_member_designation_code'); ?> 
                                <?= Html::activeHiddenInput($updateData, '[' . $updateData->staff_member_designation_code . ']staff_member_code'); ?> 
                                <?= Html::activeHiddenInput($updateData, '[' . $updateData->staff_member_designation_code . ']designation_code'); ?> 
                                <?= Html::activeHiddenInput($updateData, '[' . $updateData->staff_member_designation_code . ']tenure_from_date', ['value' => date('d-m-Y', strtotime($updateData->tenure_from_date))]) ?> 
                                <?= Html::activeHiddenInput($updateData, '[' . $updateData->staff_member_designation_code . ']tenure_to_date', ['value' => !empty($updateData->tenure_to_date) ? date('d-m-Y', strtotime($updateData->tenure_to_date)) : '']) ?> 
                                <?= Html::activeHiddenInput($updateData, '[' . $updateData->staff_member_designation_code . ']remark'); ?> 

                                <td class="staff_member_code"><?= Yii::$app->general->getforeignkey($updateData->staffMemberCode, 'staff_member_name'); ?></td>
                                <td class="designation_code"><?= Yii::$app->general->getforeignkey($updateData->designationCode, 'designation_name'); ?></td>
                                <td class="tenure_from_date"><?= date('d-m-Y', strtotime($updateData->tenure_from_date)) ?></td>
                                <td class="tenure_to_date"><?= !empty($updateData->tenure_to_date) ? date('d-m-Y', strtotime($updateData->tenure_to_date)) : ''; ?></td>
                                <td class="remark"><?= $updateData->remark ?></td>
                                <td class="staff_member_designation_code"><a href="javascript:void(0)" class='edit <?= $class ?> <?= $updateData->disableEdit() ? 'disabled' : '' ?>' onClick="editDesignation('<?= $updateData->staff_member_designation_code ?>')" id='<?= $updateData->staff_member_designation_code ?>' title='Edit'><span class='glyphicon glyphicon-pencil'></span></a></td>
                            </tr>
                            <?php
                        }
                    }
                    ?>

                </tbody>
            </table>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>

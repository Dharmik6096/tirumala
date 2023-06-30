<?php

use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
?>
<?php
$form = ActiveForm::begin(['options' => [
                'class' => 'popup-form',
                'id' => 'app-information-form-check',
            ], 'validateOnBlur' => TRUE,
            
            'validateOnChange' => FALSE,
            'enableClientValidation' => TRUE,
            'validateOnSubmit' => TRUE,
            'action' => Url::to(['/dcsoperation/tbl-member/app-information'])
        ]);
?>
<div class="modal modal-default fade" id="AppInformationModal" role="dialog">
    <div class="modal-dialog width_100-200">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close close-import" data-bs-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo Yii::t('app', 'Mobile App Information'); ?> (<?= $model->member_name ?>-<?= $model->member_code ?>)</h4>
            </div>
            <div class="modal-body">            
                <div class="panel-body">
                    <div class="panel-subheading">
                        <!--<div class="col-md-6"><b> <?= Yii::t('app', 'BMC') ?>: </b><?= Yii::$app->general->getmultiforeignkey($model->dcsCode, ['bmcCode'], 'bmc_name') ?> - <?= Yii::$app->general->getforeignkey($model->dcsCode, 'bmc_code') ?></div>-->
                        <!--<div class="col-md-6"><b> <?= $model->getAttributeLabel('dcs_code') ?>: </b><?= Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name') ?> - <?= Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_code_ex') ?></div>-->
<!--                        <div class="col-md-12"><b><?= Yii::t('app', 'Member') ?>: </b><?= $model->member_code ?> - <?= $model->member_name ?></div>-->
                        <div class="col-md-4 padding-left-0"><b> <?= $model->getAttributeLabel('mobile_no') ?>: </b><?= $model->mobile_no ?></div>
                        <div class="col-md-4"><b><?= Yii::t('app', 'Is APP Active') ?>:</b> <?= !empty($appInfo) ? ($appInfo->is_active == 1 ? 'Yes' : 'No') : 'N/A' ?></div>
                        <div class="col-md-4"><b><?= Yii::t('app', 'Is APP Block') ?>:</b> <?= !empty($appInfo) ? ( $appInfo->is_block == 1 ? 'Yes' : 'No') : 'N/A' ?></div>
                        <div class="col-md-4 padding-left-0"><b><?= Yii::t('app', 'APP Version') ?>:</b> <?= !empty($appInfo) ? $appInfo->version_no : 'N/A' ?></div>
                        <div class="col-md-4"><b><?= Yii::t('app', 'First Req.') ?>:</b> <?= !empty($appInfo) ? Yii::$app->controls->view_datetime($appInfo->orignating_timestamp) : 'N/A' ?></div>
                        <div class="col-md-4"><b><?= Yii::t('app', 'Last Req.') ?>:</b> <?= !empty($appInfo) ? Yii::$app->controls->view_datetime($appInfo->updated_at) : 'N/A' ?></div>
                        <div class="col-md-12 padding-left-0"><b><?= Yii::t('app', 'Device Name') ?>:</b> <?= !empty($appInfo) ? $appInfo->device_detail : 'N/A' ?></div>
                    </div> 
                    <div class="clearfix"></div>
                    <div class="col-md-12 padding_10_0 theme-box">
                        <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 clearfix">
                            <h4 class="theme-box-heading"><?= Yii::t('app', 'Notofication Details') ?></h4>
                        </div>
                        <table  class="table table-bordered table-responsive table-hover table-striped table-main table-language">
                            <thead>
                                <tr>
                                    <th><?= Yii::t('app', 'Tittle') ?></th>
                                    <th><?= Yii::t('app', 'Message') ?></th>
                                    <th><?= Yii::t('app', 'Datetime') ?></th>
                                    <th><?= Yii::t('app', 'Status') ?></th>
                                </tr> 
                            </thead>
                            <?php
                            if (!empty($alertInfo)) {
                                $i = 0;
                                foreach ($alertInfo as $alertData) {
                                    ?>
                                    <tr>
                                        <td><?= $alertData->header_info ?></td>
                                        <td><?= $alertData->message ?></td>
                                        <td><?= Yii::$app->controls->view_datetime($alertData->entry_datetime) ?></td>
                                        <td><?= $alertData->send_status == 2 ? 'Delivered' : ($alertData->send_status == 3 ? 'Failed' : 'Pending') ?></td>
                                    </tr>
                                    <?php
                                    $i++;
                                }
                            }
                            ?>
                        </table>
                    </div>
                </div>

                </br>
                <div class="footer">
                    <button type="button" class="btn btn-default btn-raised close-import" data-bs-dismiss="modal"><?= Yii::t('app', 'Cancel') ?></button>
                    <?php
                    if (!empty($appInfo) && $appInfo->is_active == 1 && $model->is_active == 1) {
                        echo Html::hiddenInput('member_code', $model->member_code);
                        echo Html::hiddenInput('member_name', $model->member_name, ['id' => 'member_name']);
                        echo Html::activeHiddenInput($appInfo, 'app_login_id');
                        $label = $appInfo->is_block == 1 ? 'Un-Block' : 'Block';
                        echo Html::hiddenInput('display_label', $label, ['id' => 'display_label']);
                        echo Html::submitButton(Yii::t('app', $label), ['class' => 'btn btn-default', 'id' => 'block-unblock-submit']);
                    }
                    ?>
                </div>

            </div>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>
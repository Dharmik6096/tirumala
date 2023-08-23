<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
?>
<div class="modal modal-default fade" id="mis_report_search_filter" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close close-import" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo Yii::t('app', 'Search Control Mapping'); ?></h4>
            </div>
            <div class="">
                <?php
                $form = ActiveForm::begin(['options' => [
                                'id' => 'control-form',
                                'field-class' => 'form-group col-sm-6'
                            ],
                            'method' => 'get',
                            'validateOnBlur' => FALSE,
                            'validateOnChange' => FALSE,
                            'enableClientValidation' => true,
                            'validateOnSubmit' => true,
                ]);
                ?>
                <div class="row margin_0">
                    <div class="modal-body">
                        <div class="col-sm-3">
                            <?= Yii::$app->dropdown->configFor($searchModel, $form, 'config_for', $searchModel->getAttributeLabel('config_for'), false, ['VLC', 'PORTAL']); ?>
                        </div>
                        <div class="col-sm-3">
                            <?= Html::hiddenInput('input', 0, ['id' => 'input']); ?>
                            <?= Yii::$app->dropdown->processName($searchModel, $form, 'tblconfigsearch-config_for,input', 'process_name', $model->getAttributeLabel('process_name')); ?>
                        </div>
                        <div class="col-sm-3">
                            <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', $model->getAttributeLabel('union_code'), TRUE); ?>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-sm-3">
                            <?= Yii::$app->dropdown->union_plant($model, $form, 'tblconfigmapping-union_code', 'plant_code', $model->getAttributeLabel('plant_code'), FALSE, ''); ?>
                        </div>
                        <div class="col-sm-3">
                            <?= Yii::$app->dropdown->plant_mcc($model, $form, 'tblconfigmapping-plant_code', 'mcc_plant_code', $model->getAttributeLabel('mcc_plant_code'), FALSE, ''); ?>
                        </div>
                        <div class="col-sm-3 bmc_class">
                            <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'tblconfigmapping-mcc_plant_code', 'bmc_code', Yii::t('app', 'BMC'), FALSE); ?>
                        </div>
                    </div>
                    <div class="modal-footer mt10 col-sm-12">
                        <?= Yii::$app->controls->search(); ?>
                        <button type="button" class="btn btn-danger close-import" data-dismiss="modal"><?= Yii::t('app', 'Cancel') ?></button>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
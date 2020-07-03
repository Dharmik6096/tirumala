<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
?>

<div class="modal modal-default fade" id="mis_report_search_filter" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close close-import" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo Yii::t('app', 'Search Member Payment Restrict'); ?></h4>
            </div>
            <div class="">
                <?php
                $form = ActiveForm::begin(['options' => [
                                'id' => 'report-form',
                                'field-class' => 'form-group col-sm-4'
                            ],
                            'method' => 'get',
                            'validateOnBlur' => FALSE,
                            'validateOnEnter' => TRUE,
                            'validateOnChange' => FALSE,
                            'enableClientValidation' => true,
                            'validateOnSubmit' => true,
                ]);
                ?>   
                <div class="row margin_0">

                    <div class="modal-body">

                        <div class="col-sm-4">
                            <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code'); ?>
                        </div>

                        <div class="col-sm-4">
                            <?= Yii::$app->dropdown->union_plant($model, $form, 'tblmemberpaymentrestrict-union_code', 'plant_code'); ?>
                        </div>


                        <div class="col-sm-4">
                            <?= Yii::$app->dropdown->plant_mcc($model, $form, 'tblmemberpaymentrestrict-plant_code', 'mcc_plant_code'); ?>
                        </div>


                        <div class="col-sm-4">
                            <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'tblmemberpaymentrestrict-mcc_plant_code', 'bmc_code'); ?>
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
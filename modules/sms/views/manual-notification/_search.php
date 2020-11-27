<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = Yii::t('app', isset($data['title']) ? $data['title'] : 'Search');
?>

<div class="modal modal-default fade" id="mis_report_search_filter" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close close-import" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?= $this->title; ?></h4>
            </div>
            <div class="">
                <?php
                $form = ActiveForm::begin(['options' => [
                                'id' => 'manual-sms-form',
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
                        <?php
                        $param = isset($data['param']) ? explode(',', $data['param']) : [];
                        foreach ($param as $key => $value) {
                            $value_array = explode(':', $value);
                            $value = $value_array[0];

                            if (isset($value_array[1]) && $value_array[1] == 'dateshift') {
                                ?>
                                <div class="col-sm-3">
                                    <?php
                                    echo Yii::$app->controls->date($model, $form, $value, 'form-group col-sm-6 padding-left-5 padding-right-5', false);
                                    ?>
                                </div>    
                                <?php
                                if (isset($value_array[2])) {
                                    ?>
                                    <div class="col-sm-3 shift">
                                        <?php
                                        echo Yii::$app->dropdown->dropdown('shift_applicability', $model, $form, 'col-sm-6 form-group', $model->getAttributeLabel($value_array[2]), false, $value_array[2]);
                                        ?>
                                    </div>    
                                    <?php
                                }
                            }
                            if (in_array($value, array('union_code'))) {
                                ?>
                                <div class="col-sm-3">
                                    <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union'); ?>
                                </div>   <?php
                            }
                            if (in_array($value, array('plant_code'))) {
                                ?>
                                <div class="col-sm-3">
                                    <?= Yii::$app->dropdown->union_plant($model, $form, 'manualnotification-union_code', 'plant_code', 'Plant'); ?>
                                </div>
                            <?php } if (in_array($value, array('mcc_code'))) { ?>
                                <div class="col-sm-3">
                                    <?php
                                    if (isset($value_array[1]) && $value_array[1] == 'union_code') {
                                        Yii::$app->dropdown->union_mcc($model, $form, 'manualnotification-union_code', $value, $model->getAttributeLabel('mcc_code'));
                                    } else {
                                        echo Yii::$app->dropdown->plant_mcc($model, $form, 'manualnotification-plant_code', $value, 'MCC');
                                    }
                                    ?>                
                                </div>
                                <?php
                            }
                            if (in_array($value, array('bmc_code'))) {
                                ?>
                                <div class="col-sm-3">
                                    <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'manualnotification-mcc_code', 'bmc_code', $model->getAttributeLabel('bmc_code')); ?>
                                </div>
                                <?php
                            }
                            if (in_array($value, array('dcs_code'))) {
                                ?>
                                <div class="col-sm-3">
                                    <?= Yii::$app->dropdown->bmc_society($model, $form, 'manualnotification-bmc_code', 'dcs_code', Yii::t('app', 'Society')); ?>
                                </div>
                                <?php
                            }
                            if (in_array($value, array('route_code'))) {
                                ?>
                                <div class="col-sm-3">
                                    <?= Yii::$app->dropdown->all_routes($model, $form, 'manualnotification-plant_code,manualnotification-mcc_code,manualnotification-bmc_code', 'route_code', $model->getAttributeLabel('route_code')); ?>
                                </div>
                                <?php
                            }

                            if (isset($value_array[1]) && $value_array[1] == 'static') {
                                ?>
                                <div class="col-sm-3">
                                    <?= Yii::$app->dropdown->dropdownStatic($value_array[2], $model, $form, 'form-group padding-right-5', $model->getAttributeLabel($value), false, $value) ?> 
                                </div>
                                <?php
                            }
                        }
                        if (isset($data['report_type'])) {
                            echo $form->field($model, 'report_type', ['options' => ['class' => 'form-group col-sm-3']])->dropDownList($data['report_type']);
                        }
                        ?>
                        <div class="modal-footer mt10 col-sm-12">
                            <?php
                            if ($param) {
                                echo Html::submitButton(Yii::t('app', 'Generate'), ['class' => 'btn btn-default apply-shortcut', 'name' => 'submit', 'value' => 'html', 'id' => 'html']);
                            }
                            ?>
                            <button type="button" class="btn btn-danger close-import" data-dismiss="modal"><?= Yii::t('app', 'Cancel') ?></button>
                        </div>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>




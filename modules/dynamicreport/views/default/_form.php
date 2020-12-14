<?php

use yii\widgets\ActiveForm;
use webvimark\modules\UserManagement\components\GhostHtml;
use yii\helpers\Url;

$form = ActiveForm::begin(['options' => [
                'id' => 'dynamicreport-form',
                'field-class' => 'form-group col-sm-6'
            ],
            'method' => 'get',
            'action' => Url::to(['index']),
            'validateOnBlur' => FALSE,
//            'validateOnEnter' => TRUE,
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
        ]);
?>   

<div class="row margin_0">

    <div class="modal-body">
        <div class="col-sm-3">
            <?= Yii::$app->dropdown->dropdown('report_code', $model, $form, '', 'Report Name'); ?>
        </div>
        <?php
        $param = isset($data['controls']) ? $data['controls'] : [];
        foreach ($param as $control) {
            if ($control->control_type == 'date') {
                ?>
                <div class="col-sm-3">
                    <?php
//                    echo Yii::$app->controls->date($model, $form, $control->control_name, 'form-group col-sm-6 padding-left-5 padding-right-5', false);
                    echo Yii::$app->controls->date($model, $form, $control->control_name, 'form-group col-sm-3 padding-left-5 padding-right-5', false);
                    ?>
                </div>    
                <?php
            } if ($control->control_type == 'dropdown') {
                ?>
                <div class="col-sm-3 shift">
                    <?php
                    $label = Yii::t('app', $control->control_label);
                    $cname = $control->control_name;
                    if (in_array($cname, array('union_code'))) {
                        Yii::$app->dropdown->federation_union($model, $form, $cname, $label);
                    } else if (in_array($cname, array('plant_code'))) {
                        Yii::$app->dropdown->union_plant($model, $form, 'dynamicform-union_code', $cname, $label);
                    } else if (in_array($cname, array('mcc_code'))) {
                        Yii::$app->dropdown->plant_mcc($model, $form, 'dynamicform-plant_code', $cname, $label);
                    } else if (in_array($cname, array('bmc_code'))) {
                        Yii::$app->dropdown->mcc_bmc($model, $form, 'dynamicform-mcc_code', $cname, $label);
                    } else if (in_array($cname, array('route_code'))) {
                        Yii::$app->dropdown->union_routes($model, $form, 'dynamicform-union_code', 'form-group col-sm-6 padding-right-5 padding-left-0', $label, $cname);
                    } else if (in_array($cname, array('dcs_code'))) {
                        Yii::$app->dropdown->bmc_society($model, $form, 'dynamicform-bmc_code', $cname, $label);
                    } else if (in_array($cname, array('member_code'))) {
                        echo Yii::$app->dropdown->depend_dropdown('member', $model, $form, 'dynamicform-dcs_code', 'form-group col-sm-6 padding-right-5 padding-left-5', $label, $cname);
                    } else if (in_array($cname, array('customer_type'))) {
                        echo Yii::$app->dropdown->customer_type($model, $form, 'dynamicform-bmc_code', $cname, $label, FALSE);
                    } else if (in_array($cname, array('customer_code'))) {
                        echo Yii::$app->dropdown->merge_dcs_customer($model, $form, 'dynamicform-bmc_code', $cname, $label);
                    } else {
                        echo Yii::$app->dropdown->dropdown('shift_applicability', $model, $form, 'col-sm-6 form-group', $label, false, $cname);
                    }
                    ?>
                </div>   
                <?php
            }
        }
        ?>

        <!--<div class="col-sm-3 mt25">-->
        <?php
        if ($param) {
//                echo GhostHtml::submitButton(Yii::t('app', 'Generate'), ['class' => 'btn btn-default apply-shortcut', 'name' => 'submit']);
        }
        ?>
        <!--</div>-->
    </div>

    <div class="modal-footer mt10 col-sm-12">
        <?php
        if ($param) {
            echo GhostHtml::submitButton(Yii::t('app', 'Generate'), ['class' => 'btn btn-default apply-shortcut', 'name' => 'html', 'value' => 'html', 'id' => 'html']);
        }
        ?>
        <button type="button" class="btn btn-danger close-import" data-dismiss="modal"><?= Yii::t('app', 'Cancel') ?></button>
    </div>
</div>
<?php ActiveForm::end(); ?>
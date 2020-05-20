<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\web\View;
use yii\helpers\Url;
?>
<?php
$form = ActiveForm::begin([
            'validateOnBlur' => FALSE,
            'validateOnEnter' => TRUE,
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
        ]);
?>
<?php echo $form->errorSummary($model); ?>

<div class="row">
    <div class="col-lg-8">
        <div class="col-sm-2">
            <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union', FALSE); ?>
        </div>
        <div class="col-sm-2">
            <?= Yii::$app->dropdown->union_plant($model, $form, 'tblbmcmilkdispatch-union_code', 'plant_code', TRUE); ?>
        </div>
        <div class="col-sm-2">
            <?= Yii::$app->dropdown->plant_mcc($model, $form, 'tblbmcmilkdispatch-plant_code', 'mcc_plant_code', TRUE); ?>
        </div>
        <div class="col-sm-2">
            <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'tblbmcmilkdispatch-mcc_plant_code', 'bmc_code', TRUE); ?>
        </div>
        <div class="col-sm-2"> 
            <?= $form->field($model, 'trip_code')->textInput() ?>
        </div>
        <div class="col-sm-2">
            <?= $form->field($model, 'from_date')->textInput() ?>
        </div>
        <div class="col-sm-2">
            <?= $form->field($model, 'from_shift_code')->textInput() ?>
        </div>
        <div class="col-sm-2">
            <?= $form->field($model, 'to_date')->textInput() ?>
        </div>
        <div class="col-sm-2">
            <?= $form->field($model, 'to_shift_code')->textInput() ?>
        </div>
        <div class="col-sm-2">
            <?= $form->field($model, 'destination_type')->textInput() ?>
        </div>
        <div class="col-sm-2">
            <?= $form->field($model, 'destination_code')->textInput() ?>
        </div>
        <div class="col-sm-2">
            <?php Yii::$app->dropdown->depend_dropdown('transporter', $model, $form, 'tblbmcmilkdispatch-union_code', 'form-group col-sm-2 padding-right-5 padding-left-0', 'Transporter'); ?>
        </div>
        <div class="col-sm-2"> 
            <?= Yii::$app->dropdown->depend_dropdown('transport_vehicle', $model, $form, 'tblbmcmilkdispatch-transporter_code', 'form-group col-sm-4', $model->getAttributeLabel('vehicle_code'), '', FALSE); ?>
        </div>

        <div class="clearfix"></div>

        <div class="col-sm-2"> 
            <?= $form->field($model, 'gross_weight')->textInput() ?>
        </div>
        <div class="col-sm-2"> 
            <?= $form->field($model, 'tare_weight')->textInput() ?>
        </div>
        <div class="col-sm-2"> 
            <?= $form->field($model, 'vehicle_in_time')->textInput() ?>
        </div>
        <div class="col-sm-2"> 
            <?= $form->field($model, 'vehicle_out_time')->textInput() ?>
        </div>
        <div class="col-sm-4"> 
            <?= $form->field($model, 'remarks')->textInput() ?>
        </div>
    </div>
    <div class="col-lg-4">
        <h5 class="panel-heading mb15"><?= Yii::t('app', 'Purchase Information') ?></h5>
        <table class="table tab-bordered">
            <thead>
                <tr>
                    <th>Milk Type</th>
                    <th>Silo No.</th>
                    <th>Avg.FAT</th>
                    <th>Avg.SNF</th>
                    <th>Qty</th>
                    <th>RTPL</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
    <div class="clearfix"></div>
    <h5 class="panel-heading mb15"><?= Yii::t('app', 'Dispatch Transactions') ?></h5>

    <div class="col-sm-1"> 
        <?= $form->field($transaction, 'milk_type_code')->textInput() ?>
    </div>
    <div class="col-sm-1"> 
        <?= $form->field($transaction, 'milk_quality_type_code')->textInput() ?>
    </div>
    <div class="col-sm-1"> 
        <?= $form->field($transaction, 'bmc_silos_info_code')->textInput() ?>
    </div>
    <div class="col-sm-1"> 
        <?= $form->field($transaction, 'chamber_no')->textInput() ?>
    </div>
    <div class="col-sm-1"> 
        <?= $form->field($transaction, 'dispatch_qty')->textInput() ?>
    </div>
    <div class="col-sm-1"> 
        <?= $form->field($transaction, 'qty_diff')->textInput() ?>
    </div>
    <div class="col-sm-1"> 
        <?= $form->field($transaction, 'qty_diff_type_code')->textInput() ?>
    </div>
    <div class="col-sm-1"> 
        <?= $form->field($transaction, 'balance_qty')->textInput() ?>
    </div>
    <div class="col-sm-1"> 
        <?= $form->field($transaction, 'fat')->textInput() ?>
    </div>
    <div class="col-sm-1"> 
        <?= $form->field($transaction, 'snf')->textInput() ?>
    </div>
    <div class="col-sm-1"> 
        <?= $form->field($transaction, 'clr')->textInput() ?>
    </div>
    <div class="col-sm-1"> 
        <?= $form->field($transaction, 'water')->textInput() ?>
    </div>
    <div class="clearfix"></div>
    <div class="col-sm-1"> 
        <?= $form->field($transaction, 'protein')->textInput() ?>
    </div>
    <div class="col-sm-1"> 
        <?= $form->field($transaction, 'density')->textInput() ?>
    </div>
    <div class="col-sm-1"> 
        <?= $form->field($transaction, 'lactose')->textInput() ?>
    </div>
    <div class="col-sm-1"> 
        <?= $form->field($transaction, 'freezing_point')->textInput() ?>
    </div>
    <div class="col-sm-1"> 
        <?= $form->field($transaction, 'temperature')->textInput() ?>
    </div>
    <div class="col-sm-1"> 
        <?= $form->field($transaction, 'hsn_code')->textInput() ?>
    </div>
    <div class="col-sm-1"> 
        <?= $form->field($transaction, 'seal_no_top')->textInput() ?>
    </div>
    <div class="col-sm-1"> 
        <?= $form->field($transaction, 'seal_no_bottom')->textInput() ?>
    </div>
    <div class="col-sm-1"> 
        <?= $form->field($transaction, 'seal_no_broken')->textInput() ?>
    </div>
    <div class="col-sm-1"> 
        <?= $form->field($transaction, 'dip_open')->textInput() ?>
    </div>
    <div class="col-sm-1"> 
        <?= $form->field($transaction, 'dip_close')->textInput() ?>
    </div>
    <div class="col-sm-1"> 
        <?= $form->field($transaction, 'dip_diff')->textInput() ?>
    </div>
    <div class="clearfix"></div>
    <div class="col-sm-1"> 
        <?= $form->field($transaction, 'rtpl')->textInput() ?>
    </div>
    <div class="col-sm-1"> 
        <?= $form->field($transaction, 'amount')->textInput() ?>
    </div>

    <div class="col-sm-12 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
        <div class="form-group">
            <?= Yii::$app->controls->save(Yii::$app->label->button($type), $model); ?>
            <?= Yii::$app->controls->reset(); ?>
            <?= Yii::$app->controls->cancel($model); ?>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>

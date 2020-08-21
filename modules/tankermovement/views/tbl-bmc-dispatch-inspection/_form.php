<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\web\View;
use yii\helpers\Url;
?>
<?php
$form = ActiveForm::begin([
            'validateOnBlur' => FALSE,
            
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
        ]);
?>
<?php echo $form->errorSummary($model); ?>

<div class="row">

    <div class="col-sm-2">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union', TRUE); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->union_plant($model, $form, 'tblbmcdispatchinspection-union_code', 'plant_code', TRUE, FALSE, '', TRUE); ?>
    </div> 
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->plant_mcc($model, $form, 'tblbmcdispatchinspection-plant_code', 'mcc_plant_code', TRUE, FALSE, '', TRUE); ?>
    </div>      
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'tblbmcdispatchinspection-mcc_plant_code', 'bmc_code', TRUE, FALSE, '', '', TRUE); ?>
    </div>
    <div class="col-sm-2"> 
        <?= Yii::$app->dropdown->depend_dropdown('union_vehicle', $model, $form, 'tblbmcdispatchinspection-union_code', 'form-group col-sm-4', $model->getAttributeLabel('vehicle_code'), '', TRUE); ?>
    </div>
    <div class="clearfix"></div>
    <div class="col-sm-2">
        <?= Yii::$app->controls->date($model, $form, 'inspection_date', '', TRUE, FALSE, TRUE); ?>
    </div>
    <div class="col-sm-2 shift">
        <?= Yii::$app->dropdown->dropdown('shift_applicability', $model, $form, '', true, false, 'shift_code'); ?>
    </div>
    <div class="col-sm-4"> 
        <?= $form->field($model, 'remarks')->textInput() ?>
    </div>
    <div class="clearfix"></div>
    <?php
    $index = 1;
    $cnt = 1;
    foreach ($config_list as $c) {
        ?>
        <?= Html::activeHiddenInput($config, '[' . $index . ']config_code', ['value' => $c->config_code]); ?>
        <div class="col-sm-2">
            <?= $c->prepareControl($form, $config, $index); ?>
        </div>
        <?php if ($cnt == 6) { ?>
            <div class="clearfix"></div>
            <?php
            $cnt = 0;
        }
        ?>
        <?php
        $cnt++;
        $index++;
    }
    ?>


    <div class="col-sm-12 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
        <div class="form-group">
            <?= Yii::$app->controls->save(Yii::$app->label->button($type), $model); ?>
            <?= Yii::$app->controls->reset(); ?>
            <?= Yii::$app->controls->custombutton('SKIP', ['/tankermovement/tbl-vehicle-trip/index']); ?>
        </div>
    </div>
</div>

<?php ActiveForm::end(); ?>



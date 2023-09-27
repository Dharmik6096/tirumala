<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\web\View;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;

$readonly = $type == 'create' ? FALSE : TRUE;
$class = $type == 'create' ? '' : 'disabled';
?>

<?php
$form = ActiveForm::begin([

            'options' => [],
            'validateOnBlur' => FALSE,
            
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
        ]);
?>
<?php echo $form->errorSummary($model); ?>
<div class="row">
    <div class="col-sm-12">
        <div class="col-sm-2 hide-qty-no <?= $class ?>">
            <?= $form->field($model, 'org_type')->textInput() ?>
        </div>
        <div class="col-sm-2 hide-qty-no <?= $class ?>">
        <?php
            if ($model->org_type == 'BMC') {
                $name = $model->bmcCode['bmc_name'];
            } elseif ($model->org_type == 'MCC') {
                $name = $model->mccCode['name'];
            }
        ?>
        <?= $form->field($model, 'org_name')->textInput(['value' => $name]) ?>
        <?= $form->field($model, 'org_code')->hiddenInput(['value' => $model->org_code])->label(false) ?>
        </div>
        <div class="col-sm-2 hide-qty-no <?= $class ?>">
            <?= $form->field($model, 'collection_type')->textInput() ?>
        </div>
        <div class="col-sm-2">
            <?= $form->field($model, 'm_start_time')->textInput(['type' => 'time']) ?>
        </div>
        <div class="col-sm-2">
            <?= $form->field($model, 'e_start_time')->textInput(['type' => 'time']) ?>
        </div>
        <div class="col-sm-2">
            <?= $form->field($model, 'm_lock_time')->textInput(['type' => 'time']) ?>
        </div>
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'e_lock_time')->textInput(['type' => 'time']) ?>
    </div>
     <div class="col-sm-2">
        <?= Yii::$app->dropdown->dropdownStatic('date_shift_enable', $model, $form, 'form-group', $model->getAttributeLabel('date_shift_enable'), false, 'date_shift_enable', false); ?>
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'grace_hr')->textInput() ?>
    </div>
    <div class="col-sm-2 padding_top_20 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
        <div class="form-group">
            <?= Yii::$app->controls->save(Yii::$app->label->button($type), $model); ?>
            <?= Yii::$app->controls->reset(); ?>
            <?= Yii::$app->controls->cancel($model); ?>
        </div>
    </div>
</div>

<?php ActiveForm::end(); ?>





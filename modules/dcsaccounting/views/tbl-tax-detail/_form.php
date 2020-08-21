<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\modules\dcsaccounting\models\TblTaxDetail */
/* @var $form yii\widgets\ActiveForm */

$title = Yii::$app->label->title($type, 'Tax Detail');
$button = Yii::$app->label->button($type);
$this->title = Yii::t('app', $title);
?>

<?php
$form = ActiveForm::begin(['options' => [
                'field-class' => 'form-group col-sm-3'
            ], 'validateOnBlur' => FALSE,
            
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
            'fieldConfig' => [
            //'labelOptions' => [ 'class' => false],
        ]]);
?>
<div class="row">
    <?php echo $form->errorSummary($model); ?>
    <div class="row">
        <div class="col-sm-12">
            <div class="col-sm-2">
                <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union'); ?>    
            </div>
            <div class="col-sm-2">
                <?= Yii::$app->dropdown->dropdown('tax', $model, $form, 'form-group padding-right-5 col-sm-2', 'Tax Setting', 'true'); ?>
            </div>
            <?= Html::activeHiddenInput($model, 'tax_code') ?>
            <?= $form->field($model, 'basic_tax_code', ['options' => ['class' => 'form-group padding-right-5 col-sm-2']])->dropDownList($basic_tax, ['prompt' => 'Select Basic Tax']); ?>
            <?= $form->field($model, 'type', ['options' => ['class' => 'form-group col-sm-2']])->dropDownList(['0' => 'Addition(+)', '1' => 'Substraction(-)']) ?>
            <?= $form->field($model, 'percentage', ['options' => ['class' => 'form-group col-sm-2']])->textInput() ?>
        </div>
    </div>
    <div class="col-sm-12 padding-0">
        <div class="ex2-grid">
            <div class='table-responsive'>
                <table class='table table-bordered table-striped table-main'>
                    <tr><th class='width5'></th><th class='width15'>Basic Tax Code</th><th class='width15'>Basic Tax Name</th><th class='width10'>Tax Value</th><th class='width15'>Operation</th></tr>
                    <?php
                    echo $form->field($model, 'tax_detail_code', ['options' => ['class' => '']])->checkboxList($data, [
                        'item' => function($index, $label, $name, $checked, $value) {
                            $data = explode('$', $label);

                            return "<tr><td class='width5'><input type='checkbox' class='checkbox' {$checked} name='{$name}' value='{$value}' ></td><td class='width15'>" . $data[0] . "</td><td class='width15'>" . $data[1] . "</td><td class='width10'>" . $data[2] . "</td><td class='width15'>" . $data[3] . "</td></tr>";
                        }
                    ])->label(FALSE);
                    ?>
                </table>
            </div>
        </div>
    </div>
    <div class="col-sm-12 mt15 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
        <?= Yii::$app->controls->save($button, $model); ?>
        <?= Yii::$app->controls->reset(); ?>
        <?= Yii::$app->controls->cancel($model); ?>
    </div>
</div>
<?php ActiveForm::end(); ?>
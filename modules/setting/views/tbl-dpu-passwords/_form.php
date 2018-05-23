<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\View;
use yii\jui\DatePicker;
use yii\helpers\Url;

$button = Yii::$app->label->button($type);
$this->title = Yii::t('app', 'DPU Passwords');
?>
<div class="grid-search">
    <?php echo $this->render('_search', ['model' => $searchModel]); ?>
</div>
<?php
$form = ActiveForm::begin(['options' => [
                'field-class' => 'form-group col-sm-3'
            ], 'validateOnBlur' => FALSE,
            'validateOnEnter' => TRUE,
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
            'fieldConfig' => [
        ]]);
?>
    <div class="table-responsive mt-50 table-height">
        <table class="table table-bordered table-striped table-language">
            <thead>
                <tr>
                    <th><?php echo $model[0]->getAttributeLabel('dcs_code') ?></th>
                    <th><?php echo $model[0]->getAttributeLabel('PPCode') ?></th>
                    <th><?php echo $model[0]->getAttributeLabel('bmc_code') ?></th>
                    <th><?php echo $model[0]->getAttributeLabel('AdminPwd') ?></th>
                    <th><?php echo $model[0]->getAttributeLabel('SuperPwd') ?></th>
                    <th><?php echo $model[0]->getAttributeLabel('UserPwd') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php
                $i = 0;
                foreach ($model as $models) {
                    $models->bmc_code = Yii::$app->general->getforeignkey($models->dcsCode, 'bmc_code');
                    $models->PPCode = substr($models->dcs_code, -3);
                    ?>
                    <tr>
                        <td><?= $models->dcs_code ?><?= Html::activeHiddenInput($models, '[' . $i . ']dcs_code') ?></td>
                        <td><?= $models->PPCode ?><?= Html::activeHiddenInput($models, '[' . $i . ']PPCode') ?></td>
                        <td><?= $models->bmc_code ?><?= Html::activeHiddenInput($models, '[' . $i . ']bmc_code') ?></td>
                        <td><?= $form->field($models, '[' . $i . ']AdminPwd')->textInput(['multiple' => true])->label(false) ?></td>
                        <td><?= $form->field($models, '[' . $i . ']SuperPwd')->textInput(['multiple' => true])->label(false) ?></td>
                        <td><?= $form->field($models, '[' . $i . ']UserPwd')->textInput(['multiple' => true])->label(false) ?></td>
                    </tr>
                    <?php $i++;
                }
                ?>
            </tbody>
        </table>
    </div>
<div class="shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
    <?= Yii::$app->controls->save($button, $model[0]); ?>
<?= Yii::$app->controls->reset(); ?>
</div>

<?php ActiveForm::end(); ?>

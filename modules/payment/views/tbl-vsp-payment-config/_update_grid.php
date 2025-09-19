<?php

use app\components\ActiveForm;
use yii\web\View;
use yii\helpers\Html;
?>
<?php
$form = ActiveForm::begin([
            'id' => 'vsp-payment-config-update',
        ]);
?>
<div class="no-effect table_form" >
    <?php
    $attribute = [
        ['attribute' => 'union_code', 'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
            }, 'vAlign' => 'middle', 'filter' => false, 'visible' => false],
        ['attribute' => 'plant_code', 'label' => Yii::t('app', 'Plant Code'), 'visible' => false, 'filter' => false, 'vAlign' => 'middle'],
        ['attribute' => 'plant_code', 'header' => Yii::t('app', 'Plant'),
            'value' => function ($model, $key, $index) use ($form) {
                echo Html::activeHiddenInput($model, '[' . $index . ']vsp_payment_config_code', ['value' => $model->vsp_payment_config_code]);
                return Yii::$app->general->getforeignkey($model->plantCode, 'name');
            }, 'filter' => false],
        ['attribute' => 'mcc_plant_code', 'visible' => false, 'label' => Yii::t('app', 'MCC Code'), 'vAlign' => 'middle', 'filter' => false],
        ['attribute' => 'mcc_plant_code', 'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->mccPlantCode, 'name');
            }, 'vAlign' => 'middle', 'filter' => false],
        ['attribute' => 'bmc_code', 'label' => Yii::t('app', 'BMC Code'), 'vAlign' => 'middle', 'filter' => false],
        ['attribute' => 'bmc_name', 'label' => Yii::t('app', 'BMC'), 'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
            }, 'vAlign' => 'middle', 'filter' => false],
        ['attribute' => 'dcs_code', 'label' => Yii::t('app', 'DCS Code'), 'vAlign' => 'middle', 'filter' => false],
        ['attribute' => 'ex_code', 'label' => Yii::t('app', 'Code Ex.'), 'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_code_ex');
            }, 'vAlign' => 'middle', 'filter' => false],
        ['attribute' => 'dcs_name', 'label' => Yii::t('app', 'DCS'), 'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name');
            }, 'vAlign' => 'middle', 'filter' => false],
        ['attribute' => 'billing_based_on',
            'format' => 'raw',
            'value' => function ($model, $key, $index) use ($form) {
                return '<span class=\'billing_based_on\'>' . Yii::$app->dropdown->dropdownStatic('billing_based_on', $model, $form, 'form-group', false, false, '[' . $index . ']billing_based_on', false, false, true, true) . '</span>';
            }, 'filter' => false
        ],
    ];

    $grid_option = [
        'id' => 'vsp-payment-config-update-grid',
        'attributes' => $attribute,
        'active_column' => FALSE,
    ];

    Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option, ['#'], false);
    ?>
</div>
<div class="panel-footer" >
    <?php
    if (!empty($dataProvider->getModels())) {
        echo Html::button(Yii::t('app', 'Update'), ['class' => 'btn btn-primary', 'id' => 'update']);
    }
    ?>
    <?= Yii::$app->controls->custombutton('Cancel', 'index'); ?> 
</div>

<?php ActiveForm::end(); ?>

<?php
$script = "
    $('.kv-panel-before').hide();
    $('#update').click(function(e) {
        e.preventDefault();
        $('#vsp-payment-config-update').submit();
    })";
$this->registerJs($script, View::POS_END, 'vsp-payment-config-update');

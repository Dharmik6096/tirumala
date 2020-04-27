<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\helpers\Html;
use app\components\GeneralFunctions;
?>

<div class="grid-search clearfix">
    <?php //echo $this->render('_search', ['model' => $searchModel]); ?>
</div>

<?php
$attribute = [
        [
        'attribute' => 'wef_date',
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->wef_date);
        }],
        ['attribute' => 'shift_code', 'value' => 'shiftCode.shift', 'vAlign' => 'middle',],
        ['attribute' => 'shift_for', 'value' => 'shiftCodeFor.shift', 'vAlign' => 'middle',],
        ['attribute' => 'applicable_for'],
        ['attribute' => 'applicable_code'],
        ['attribute' => 'code_ex', 'label' => Yii::t('app', 'Code Ex.'), 'value' => function($model) {
            return Yii::$app->general->getCustomer($model, $model->applicable_for, true);
        }, 'vAlign' => 'middle',],
        ['attribute' => 'applicable_code', 'value' => function($model) {
            if ($model->applicable_for == 'PLANT') {
                return Yii::$app->general->getforeignkey($model->plantCode, 'name');
            } else if ($model->applicable_for == 'MCC') {
                return Yii::$app->general->getforeignkey($model->mccPlantCode, 'name');
            } else if ($model->applicable_for == 'BMC') {
                return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
            } else if ($model->applicable_for == 'DCS') {
                return Yii::$app->general->getforeignkey($model->dcsName, 'dcs_name');
            } else {
                return Yii::$app->general->getforeignkey($model->customerMasterCode, 'customer_name');
            }
        }, 'label' => Yii::t('app', 'Applicable Name'), 'vAlign' => 'middle'],
];

$grid_option = [
    'id' => 'head-load-applicability-grid',
    'attributes' => $attribute,
    'active_column' => false,
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option, ['view', 'id' => Yii::$app->request->get('id')]);
?>
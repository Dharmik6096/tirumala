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
//        'filterType' => GridView::FILTER_DATE,
//        'filterWidgetOptions' => [
//            'pluginOptions' => ['format' => 'dd-mm-yyyy',
//                'autoclose' => true]
//        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->wef_date);
        }],
    ['attribute' => 'dcs_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name');
        }, 'vAlign' => 'middle', 'label' => Yii::t('app', 'DCS')],
    ['attribute' => 'shift_code', 'value' => 'shiftCode.shift', 'vAlign' => 'middle',],
    ['attribute' => 'shift_for', 'value' => 'shiftCodeFor.shift', 'vAlign' => 'middle',],
        //  ['attribute' => 'sub_center_code', 'value' => function($model) { return Yii::$app->general->getforeignkey($model->subCenterCode, 'sub_center_name'); }, 'vAlign' => 'middle',],
];

$grid_option = [
    'id' => 'head-load-applicability-grid',
    'attributes' => $attribute,
    'active_column' => false,
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option, ['view', 'id' => Yii::$app->request->get('id')]);
?>
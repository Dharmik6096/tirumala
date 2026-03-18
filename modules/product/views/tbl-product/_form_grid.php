<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\helpers\Html;
use app\modules\usermanagement\components\GhostHtml;
use app\modules\globalmaster\models\TblAnimalType;

$milkType = new TblAnimalType();
$milk_type = $milkType->getAnimalMilkTypeArray();
?>

<?php

$attribute = [
        ['attribute' => 'union_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'visible' => true,],
        ['attribute' => 'plant_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->plantCode, 'name');
        }, 'visible' => false,],
        ['attribute' => 'mcc_plant_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->mccPlantCode, 'name');
        }, 'visible' => false,],
        ['attribute' => 'bmc_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
        }, 'visible' => false,],
        ['attribute' => 'dcs_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name');
        }, 'visible' => false,],
        ['attribute' => 'product_group_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->productGroupCode, 'product_group_name');
        }],
        ['attribute' => 'unit_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unitCode, 'unit_name');
        }],
        ['attribute' => 'ref_code', 'visible' => false],
        ['attribute' => 'product_code'],
        ['attribute' => 'product_name'],
        [
        'attribute' => 'x_col3',
        'filter' => Yii::$app->dropdown->dropdownfilterStatic('product_type', $searchModel, 'x_col3'),
        'value' => function($model) {
            return isset($model->x_col3) ? Yii::$app->dropdown->getRecords('product_type')['data'][$model->x_col3] : '';
        }
    ],
        ['attribute' => 'tax_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->taxCode, 'tax_name');
        }, 'visible' => true,],
        ['attribute' => 'other_state_tax_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->stateTaxCode, 'tax_name');
        }, 'visible' => true],
        [
        'attribute' => 'is_inhouse', 'visible' => false,
        'filter' => Yii::$app->dropdown->dropdownfilterStatic('boolean_value', $searchModel, 'is_inhouse'),
        'value' => function($model) {
            return isset($model->is_inhouse) ? Yii::$app->dropdown->getRecords('boolean_value')['data'][$model->is_inhouse] : '';
        }
    ],
        [
        'attribute' => 'is_inclusive_tax', 'visible' => false,
        'filter' => Yii::$app->dropdown->dropdownfilterStatic('boolean_value', $searchModel, 'is_inclusive_tax'),
        'value' => function($model) {
            return isset($model->is_inhouse) ? Yii::$app->dropdown->getRecords('boolean_value')['data'][$model->is_inclusive_tax] : '';
        }
    ],
        [
        'attribute' => 'is_saleable', 'visible' => false,
        'filter' => Yii::$app->dropdown->dropdownfilterStatic('boolean_value', $searchModel, 'is_saleable'),
        'value' => function($model) {
            return isset($model->is_inhouse) ? Yii::$app->dropdown->getRecords('boolean_value')['data'][$model->is_saleable] : '';
        }
    ],
        [
        'attribute' => 'is_indent', 'visible' => false,
        'filter' => Yii::$app->dropdown->dropdownfilterStatic('boolean_value', $searchModel, 'is_indent'),
        'value' => function($model) {
            return isset($model->is_inhouse) ? Yii::$app->dropdown->getRecords('boolean_value')['data'][$model->is_indent] : '';
        }
    ],
        ['attribute' => 'product_desc'],
        ['attribute' => 'local_name', 'filter' => false],
        [
        'attribute' => 'is_dpu_product', 'visible' => false,
        'filter' => Yii::$app->dropdown->dropdownfilterStatic('boolean_value', $searchModel, 'is_dpu_product'),
        'value' => function($model) {
            return isset($model->is_dpu_product) ? Yii::$app->dropdown->getRecords('boolean_value')['data'][$model->is_dpu_product] : '';
        }
    ],
        ['attribute' => 'dpu_product_code', 'visible' => false],
        ['attribute' => 'item_code', 'filter' => false, 'visible' => false],
        ['attribute' => 'min_stock', 'filter' => false, 'visible' => false],
        ['attribute' => 'purchase_ledger', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->purchaseLedgerCode, 'ledger_name');
        }],
        ['attribute' => 'sale_ledger', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->saleLedgerCode, 'ledger_name');
        }],
        ['attribute' => 'stock_ledger', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->stockLedgerCode, 'ledger_name');
        }],
        ['attribute' => 'is_milk',
        'filter' => Yii::$app->dropdown->dropdownfilterStatic('boolean_value', $searchModel, 'is_milk'),
        'value' => function($model) {
            return isset($model->is_milk) ? Yii::$app->dropdown->getRecords('boolean_value')['data'][$model->is_milk] : '';
        }],
        ['attribute' => 'milk_type', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->milkType, 'animal_type_name');
        }, 'vAlign' => 'middle', 'filter' => Html::activeDropDownList($searchModel, 'milk_type', $milk_type, ['class' => 'form-control', 'prompt' => 'Select'])],
        ['attribute' => 'local_sale_ledger', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->localSaleLedgerCode, 'ledger_name');
        }],
        ['attribute' => 'coupon_ledger', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->couponLedgerCode, 'ledger_name');
        }],
];

$grid_option = [
    'id' => 'product-grid',
    'attributes' => $attribute,
    'active_column' => true,
    'actions' => [
        'view' => true,
        'edit' => function ($url, $model) {
            $disable = Yii::$app->general->allowUpdateDelete($model) ? '' : 'disabled';
            $options = ['title' => Yii::t('app', 'Edit'), 'class' => $disable];
            return GhostHtml::a('<i class="fa fa-pencil-alt"></i>', ['/product/tbl-product/update', 'id' => $model->product_code], $options);
        },
//        'update' => true,
        'delete' => ['option' => 'product_name,product_code,tbl-product/delete,checkAllowDelete()'],
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>

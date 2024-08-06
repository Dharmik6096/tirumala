<?php

use yii\helpers\Html;
use kartik\grid\GridView;
?>

<?php

$attribute = [
        ['attribute' => 'union_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'vAlign' => 'middle', 'filter' => false],
        ['attribute' => 'rule_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->ruleCode, 'rule_name');
        }, 'vAlign' => 'middle', 'filter' => false],
        ['attribute' => 'department_id', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->departmentCode, 'department');
        }, 'filter' => false],
        ['attribute' => 'organization_type', 'filter' => false],
        ['attribute' => 'module_name', 'filter' => false],
        ['attribute' => 'result_key', 'filter' => false],
        ['attribute' => 'is_active', 'filter' => false],
];

$grid_option = [
    'id' => 'alert-rule-mapping-grid',
    'attributes' => $attribute,
    'active_column' => FALSE,
];
Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>

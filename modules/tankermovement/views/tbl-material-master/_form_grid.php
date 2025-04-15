<?php

use yii\helpers\Html;
use webvimark\modules\UserManagement\components\GhostHtml;
?>
<?php

$attribute = [
        ['attribute' => 'union_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'filter' => false, 'visible' => false],
        ['attribute' => 'material_name'],
        ['attribute' => 'local_name', 'filter' => false],
        ['attribute' => 'ref_code'],
];

$grid_option = [
    'id' => 'material-master-list',
    'attributes' => $attribute,
    'active_column' => true,
    'actions' => [
        'view' => false,
        'update' => true,
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>



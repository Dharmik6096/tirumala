<?php

use yii\helpers\Html;
use webvimark\modules\UserManagement\components\GhostHtml;
?>

<?php

$attribute = [
    ['attribute' => 'engineer_id', 'label' => Yii::t('app', 'Code'), 'filter' => false],
    ['attribute' => 'engineer_id', 'label' => Yii::t('app', 'Name'), 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->engineerCode, 'name');
        }, 'vAlign' => 'middle', 'filter' => false],
];

$grid_option = [
    'id' => 'source-grid',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'delete' => ['option' => 'engineerCode.name,user_engineer_mapping_code,user/delete-engineer'],
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
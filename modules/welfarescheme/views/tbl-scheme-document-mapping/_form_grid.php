<?php

use yii\helpers\Html;
use webvimark\modules\UserManagement\components\GhostHtml;
use kartik\grid\GridView;
?>

<?php

$attribute = [
        ['attribute' => 'doc_id', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->docId, 'doc_name');
        }, 'filter' => false],
        ['attribute' => 'is_mandate', 'filter' => false],
];

$grid_option = [
    'id' => 'tbl-scheme-document-mapping-grid',
    'attributes' => $attribute,
    'active_column' => false,
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>


<?php

use yii\helpers\Html;
use webvimark\modules\UserManagement\components\GhostHtml;

?>
<?php

$attribute = [
    // ['attribute' => 'user_code', 'visible' => true, 'filter' => false],
    ['attribute' => 'applicable_code', 'value' => function($model){
        ($model->applicable_for == 'DCS') ? $model->dcs_code = $model->applicable_code : '';
       return Yii::$app->general->getField($model, $model->applicable_for,'ref_code');
    }, 'filter' => false],
    ['attribute' => 'applicable_code','label' => Yii::t('app', 'Name'),'value' => function($model){
        ($model->applicable_for == 'DCS') ? $model->dcs_code = $model->applicable_code : '';
       return Yii::$app->general->getField($model, $model->applicable_for);
    }, 'filter' => false],
    ['attribute' => 'applicable_for', 'visible' => true, 'filter' => false],
];

$grid_option = [
    'id' => 'source-grid',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'delete' => ['option' => 'organization_latlong_applicability_code,organization_latlong_applicability_code,tbl-organization-latlong/delete-source'],
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
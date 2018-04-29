<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\helpers\Html;
use webvimark\modules\UserManagement\components\GhostHtml;
use app\components\GeneralFunctions;
?>

<?php

$attribute = [
    //['attribute' => 'dcs_miscellaneous_code','vAlign' => 'middle'],
    ['attribute' => 'miscellaneous_code',
        'vAlign' => 'middle',
        'value' => 'miscellaneousCode.miscellaneous_name', 'filter' => Html::activeTextInput($searchModel, 'miscellaneous_code', ['class' => 'form-control']),],
    ['attribute' => 'description', 'vAlign' => 'middle'],
];

$grid_option = [
    'id' => 'dcs-miscellaneous-list',
    'attributes' => $attribute,
    'active_column' => true,
    'actions' => [
        'edit' => function ($url, $model) {
            $options = ['title' => 'Edit'];
            return GhostHtml::a('<i class="fa fa-pencil"></i>', ['/organisation/tbl-dcs-subcenter-misc/update', 'id' => Yii::$app->request->get('id'), 'name' => Yii::$app->request->get('name'), 'type' => Yii::$app->request->get('type'), 'pk' => $model->dcs_miscellaneous_code], ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Edit'], $options);
        },
                'delete' => ['option' => 'miscellaneous_code,dcs_miscellaneous_code,tbl-dcs-subcenter-misc/delete'],
            ]
        ];
        Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option, ['index', 'id' => Yii::$app->request->get('id'), 'name' => Yii::$app->request->get('name'), 'type' => Yii::$app->request->get('type')]);
        ?>
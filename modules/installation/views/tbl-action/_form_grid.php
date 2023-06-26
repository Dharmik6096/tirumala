<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\helpers\Html;
use app\modules\usermanagement\components\GhostHtml;

?>

<?php

$attribute = [

    ['attribute' => 'action_name'],
    ['attribute' => 'menu_level'],
    ['attribute' => 'parent_code'],
    ['attribute' => 'description'],

];

$grid_option = [
    'id' => 'action-grid',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'view' => true,
        'update' => true,
//                'delete' => ['option' => 'action_name,action_name,tbl-action/delete','id'=> $model->action_code],
            ]
        ];

        Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
        ?>

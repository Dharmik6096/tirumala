<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\helpers\Html;
use app\components\GeneralFunctions;
?>

<div class="grid-search clearfix">
    <?php echo $this->render('_search', ['model' => $searchModel]); ?>
</div>

<?php
$attribute = [
    ['attribute' => 'union_config_code', 'vAlign' => 'middle',],
    ['attribute' => 'perc_disp_recp_milk', 'vAlign' => 'middle'],
    ['attribute' => 'min_member_age', 'vAlign' => 'middle'],
    ['attribute' => 'manual_days_collection', 'vAlign' => 'middle'],
    ['attribute' => 'audit_response_time', 'vAlign' => 'middle'],
    ['attribute' => 'auto_audit_resolution', 'vAlign' => 'middle'],
    ['attribute' => 'range_end', 'vAlign' => 'middle'],
    
];

$grid_option = [
    'id' => 'union-config-grid',
    'attributes' => $attribute,
    'active_column' => true,
    'actions' => [
        'update' => true,
        'delete' => ['option' => 'range_end,union_config_code,tbl-union-config/delete'],
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
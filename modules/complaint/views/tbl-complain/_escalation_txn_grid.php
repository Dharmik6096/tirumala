<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use webvimark\modules\UserManagement\components\GhostHtml;
use yii\helpers\Url;
?>

<?php

$attribute = [
        ['attribute' => 'complain_escalation_txn_code', 'filter' => false],
        ['attribute' => 'task_activity_code', 'filter' => false],
        ['attribute' => 'user_type', 'filter' => false],
        ['attribute' => 'escalation_time', 'filter' => false],
        ['attribute' => 'level', 'filter' => false],
        ['attribute' => 'user_code', 'filter' => false],
        ['attribute' => 'status', 'filter' => false],
        ['attribute' => 'assign_date', 'filter' => false],
];

$grid_option = [
    'id' => 'complain_escalation_txn_detail',
    'attributes' => $attribute,
    'active_column' => false,
];

Yii::$app->grid->bind($escalationTxnDataProvider, $complain_escalation_txn, $grid_option, '', false);
?>

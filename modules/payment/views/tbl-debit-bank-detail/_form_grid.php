<?php

use yii\helpers\Html;
use webvimark\modules\UserManagement\components\GhostHtml;
use yii\helpers\Url;

?>
<?php

$attribute = [
    ['attribute' => 'debit_bank_detail_code', 'filter' => false],
    ['attribute' => 'union_bank_payment_code', 'filter' => false],
    ['attribute' => 'union_code', 'filter' => false],
    ['attribute' => 'branch_code', 'filter' => false],
    ['attribute' => 'branch_name', 'filter' => false],
    ['attribute' => 'module_code', 'filter' => false],
    ['attribute' => 'module_name', 'filter' => false],
    ['attribute' => 'bank_account_no', 'filter' => false],
    ['attribute' => 'account_holder_name', 'filter' => false],
    ['attribute' => 'email', 'filter' => false],
    ['attribute' => 'mobile_no', 'filter' => false],
];

$grid_option = [
    'id' => 'debit-bank-list',
    'attributes' => $attribute,
    'active_column' => false,
];
Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>

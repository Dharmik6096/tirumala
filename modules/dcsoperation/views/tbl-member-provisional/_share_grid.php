<?php

use webvimark\modules\UserManagement\components\GhostHtml;
use yii\helpers\Url;
use yii\web\View;
?>

<?php

$attribute = [
        ['attribute' => 'mode_of_payment', 'value' => function ($model) {
            return !empty($model->mode_of_payment) ? Yii::$app->dropdown->getRecords('mode_of_payment')['data'][$model->mode_of_payment] : '';
        }, 'filter' => false,],
        ['attribute' => 'ref_no', 'filter' => false],
        ['attribute' => 'bank_name', 'filter' => false],
        ['attribute' => 'amount_deposit', 'filter' => false],
        ['attribute' => 'deposit_date', 'vAlign' => 'middle', 'value' => function($model) {
            return Yii::$app->controls->view_date($model->deposit_date);
        }, 'filter' => false],
        ['attribute' => 'no_of_share_req', 'filter' => false],
        ['attribute' => 'no_of_share_apply', 'filter' => false],
        ['attribute' => 'payable_share_amount', 'filter' => false],
        ['attribute' => 'admission_fee', 'filter' => false],
        ['attribute' => 'amount_payable', 'filter' => false],
        ['attribute' => 'total_amount', 'filter' => false],
        ['attribute' => 'per_share_rate', 'filter' => false]
];

$grid_option = [
    'id' => 'member-share-grid',
    'attributes' => $attribute,
    'active_column' => false,
];

Yii::$app->grid->bind($shareDataProvider, $shareMemberModel, $grid_option);
?>
<?php

$script = '$(".kv-panel-before").hide();';
$this->registerJs($script, View::POS_END, 'member-share-list');

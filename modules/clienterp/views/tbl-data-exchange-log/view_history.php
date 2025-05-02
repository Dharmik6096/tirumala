<?php

use yii\helpers\Html;
use yii\web\View;
?>

<div class="grid-search no-effect">
    <?php
    $attribute = [
            ['attribute' => 'process_name', 'filter' => false],
            ['attribute' => 'data_post_status', 'value' => function ($model) {
                return isset(Yii::$app->dropdown->getRecords('send_status')['data'][$model->data_post_status]) ? Yii::$app->dropdown->getRecords('send_status')['data'][$model->data_post_status] : '';
            }, 'filter' => false],
            ['attribute' => 'picked_datetime', 'value' => function($model) {
                return Yii::$app->controls->view_datetime($model['picked_datetime']);
            }, 'filter' => false],
            ['attribute' => 'response_datetime', 'value' => function($model) {
                return Yii::$app->controls->view_datetime($model['response_datetime']);
            }, 'filter' => false],
            ['attribute' => 'resp_status', 'filter' => false],
            ['attribute' => 'resp_desc', 'filter' => false],
            ['attribute' => 'resp_msg', 'filter' => false],
            ['attribute' => 'resp_param_1', 'filter' => false],
            ['attribute' => 'resp_param_2'],
            ['attribute' => 'resp_param_3', 'filter' => false],
            ['attribute' => 'resp_param_4', 'filter' => false],
            ['attribute' => 'resp_param_5', 'filter' => false],
            ['attribute' => 'resp_param_6', 'filter' => false],
    ];

    $grid_option = [
        'id' => 'history-data',
        'attributes' => $attribute,
        'active_column' => false,
    ];

    Yii::$app->grid->bind($exchangeDataProvider, $dataExchangeModel, $grid_option, '', false);
    ?>
</div>
<?php
$script = '$(".kv-panel-before").hide();';
$this->registerJs($script, View::POS_END, 'data-exchange-list');

<div class="clearfix"></div>
<div class="hide_toolbar_only hide_filters_only">

    <?php
    $attribute = [
        ['attribute' => 'tran_datetime', 'label' => Yii::t('app', 'Transaction Date'), 'value' => function ($model) {
                return Yii::$app->controls->view_date($model->tran_datetime);
            }, 'vAlign' => 'middle', 'filter' => false],
        ['attribute' => 'entry_type', 'vAlign' => 'middle', 'filter' => false],
        ['attribute' => 'old_value', 'filter' => false],
        ['attribute' => 'new_value', 'filter' => false],
        ['attribute' => 'final_value', 'filter' => false],
        ['attribute' => 'transfer_ref_code', 'filter' => false],
        ['attribute' => 'transfer_ref_name', 'filter' => false],
    ];

    $grid_option = [
        'id' => 'medicine-stock-txn-list',
        'attributes' => $attribute,
        'active_column' => FALSE,
    ];

    Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
    ?>
</div>
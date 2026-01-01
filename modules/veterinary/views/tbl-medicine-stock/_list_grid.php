<div class="clearfix"></div>
<div class="hide_toolbar_only hide_filters_only">

    <?php
    $attribute = [
        ['attribute' => 'medicine_id', 'label' => Yii::t('app', 'Medicine Name'), 'value' => function ($model) {
                return Yii::$app->general->getforeignkey($model->medicineMasterCode, 'medicine_name');
            }, 'filter' => false],
        ['attribute' => 'batch_no', 'filter' => true],
        [
            'attribute' => 'expire_date',
            'value' => function ($model) {
                return Yii::$app->controls->view_date($model->expire_date);
            }
        ],
        ['attribute' => 'available_stock', 'filter' => true],
        ['attribute' => 'qty', 'filter' => true],
        ['attribute' => 'rate', 'format' => Yii::$app->general->CurrencyFormat(), 'visible' => true, 'filter' => false],
    ];

    $grid_option = [
        'id' => 'medicine-stock-trans-txn-list',
        'attributes' => $attribute,
        'active_column' => FALSE,
    ];

    Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
    ?>
</div>
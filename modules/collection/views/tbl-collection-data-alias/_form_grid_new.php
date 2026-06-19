<div class="padding_left_45">
    <?php
    use kartik\grid\GridView;
    use yii\helpers\Html;
    
    $models = new app\modules\collection\models\TblCollectionDataAliasSearch();
    $models->table_name = $searchModel->table_name;
    $is_pending = $searchModel->is_pending_approval ?? false;
    $dataProvide = $models->searchChild($model, Yii::$app->request->queryParams, $is_pending);
    ?>
    <div class="no-effect">
        <?php
        $attributes = [
            [
                'class' => 'kartik\grid\CheckboxColumn',
                'header' => false,
                'rowSelectedClass' => GridView::TYPE_SUCCESS,
                'headerOptions' => ['class' => 'skip-export'],
                'contentOptions' => ['class' => 'skip-export'],
                'checkboxOptions' => function($m, $key, $index) use ($model, $is_concate, $parent_index) {
                    $code = $m['collection_data_alias_code'] . '###' . $m['action_perform'];
                    if (!empty($is_concate)) {
                        $code = $m['collection_data_alias_code'] . '###' . $m['process_approval_code'] . '###' . $m['action_perform'];
                    }
                    $amount = isset($m['amount']) ? $m['amount'] : ($m->amount ?? 0);
                    if ($m['action_perform'] == 'UPDATE') {
                        $old_amount = isset($m['old_amount']) ? $m['old_amount'] : ($m->old_amount ?? 0);
                        $amount = $amount - $old_amount;
                    } elseif ($m['action_perform'] == 'DELETE') {
                        $amount = -$amount;
                    }
                    return [
                        'class' => 'checkbox group-checkbox child-checkbox checkbox-collection child_of_parent_row_' . $parent_index .' child-checkbox-'.$m['collection_data_alias_code'], 
                        'data-parent-id' => 'parent_row_' . $parent_index, 
                        'data-amount' => $amount,
                        'value' => $code
                    ];
                }
            ],
            ['attribute' => 'action_perform', 'filter' => false, 'visible' => TRUE],
            ['attribute' => 'customer_type', 'value' => function($m) {
                return Yii::$app->general->getforeignkey($m->customerType, 'customer_desc');
            }, 'filter' => FALSE, 'visible' => !empty($showType) ? TRUE : FALSE],
            ['attribute' => 'customer_code', 'label' => Yii::t('app', 'Code'), 'filter' => false, 'visible' => !empty($showType) ? TRUE : FALSE],
            ['attribute' => 'ex_code', 'label' => Yii::t('app', 'Code Ex.'), 'value' => function($m) {
                return Yii::$app->general->getCustomer($m, $m->customer_type, TRUE);
            }, 'visible' => !empty($showType) ? TRUE : FALSE],
            ['attribute' => 'customer_code', 'label' => Yii::t('app', 'Name'), 'value' => function($m) {
                return Yii::$app->general->getCustomer($m, $m->customer_type);
            }, 'filter' => false, 'visible' => !empty($showType) ? TRUE : FALSE],
            ['label' => Yii::t('app', 'Member Code'), 'attribute' => 'member_code', 'value' => function($m) {
                return !empty($m->member_code) ? substr($m->member_code, -4) : '';
            }, 'visible' => !empty($showFarmer) ? TRUE : FALSE, 'filter' => false],
            ['attribute' => 'member_code', 'value' => function($m) {
                return Yii::$app->general->getforeignkey($m->memberCode, 'member_name');
            }, 'filter' => false, 'visible' => !empty($showFarmer) ? TRUE : FALSE],
            ['attribute' => 'sample_no', 'filter' => false],
            ['attribute' => 'route_code', 'value' => function($m) {
                return Yii::$app->general->getforeignkey($m->routeCode, 'route_name');
            }, 'filter' => FALSE],
            ['attribute' => 'old_route_code', 'value' => function($m) {
                return Yii::$app->general->getforeignkey($m->oldRouteCode, 'route_name');
            }, 'filter' => FALSE],
            ['attribute' => 'old_customer_code', 'filter' => false, 'visible' => (isset($is_dcs_editable) && $is_dcs_editable)],
            ['attribute' => 'old_milk_type_code', 'value' => function($m) {
                return Yii::$app->general->getforeignkey($m->oldMilkTypeCode, 'animal_type_name');
            }, 'filter' => false, 'visible' => TRUE],
            ['attribute' => 'old_milk_quality_type_code', 'value' => function($m) {
                return Yii::$app->general->getforeignkey($m->oldMilkQualityCode, 'milk_quality_type_name');
            }, 'filter' => false, 'visible' => TRUE],
            ['attribute' => 'old_qty', 'filter' => false, 'visible' => TRUE],
            ['attribute' => 'old_fat', 'filter' => false, 'visible' => TRUE],
            ['attribute' => 'old_snf', 'filter' => false, 'visible' => TRUE],
            ['attribute' => 'old_rtpl', 'filter' => false, 'visible' => TRUE],
            ['attribute' => 'old_amount', 'filter' => false, 'format' => Yii::$app->general->CurrencyFormat(), 'visible' => TRUE],
            ['attribute' => 'milk_type_code', 'value' => function($m) {
                return Yii::$app->general->getforeignkey($m->milkTypeCode, 'animal_type_name');
            }, 'filter' => FALSE],
            ['attribute' => 'milk_quality_type_code', 'value' => function($m) {
                return Yii::$app->general->getforeignkey($m->milkQualityCode, 'milk_quality_type_name');
            }, 'filter' => FALSE],
            ['attribute' => 'qty', 'filter' => false],
            ['attribute' => 'fat', 'filter' => false],
            ['attribute' => 'snf', 'filter' => false],
            ['attribute' => 'scheme_rate', 'filter' => false],
            ['attribute' => 'actual_rate', 'filter' => false],
            ['attribute' => 'rtpl', 'filter' => false],
            ['attribute' => 'amount', 'filter' => false, 'format' => Yii::$app->general->CurrencyFormat(),],
            ['attribute' => 'old_antibiotic', 'filter' => false, 'visible' => !empty($showFarmer) ? FALSE : TRUE],
            ['attribute' => 'antibiotic', 'filter' => false, 'visible' => !empty($showFarmer) ? FALSE : TRUE],
            ['attribute' => 'created_by', 'value' => function($m) {
                $createdBy = $m->createdBy;
                if (!empty($createdBy)) {
                    return !empty($createdBy->contact_person) ? $createdBy->contact_person : $createdBy->firstname;
                }
                return null;
            }, 'filter' => FALSE],
            ['attribute' => 'error_desc', 'filter' => false],
        ];
        ?>
    </div>

    <?php
    echo GridView::widget([
        'id' => 'details-grid-'.$model['dcs_code'],
        'dataProvider' => $dataProvide,
        'layout' => '{items}{pager}',
        'columns' => $attributes,
        'containerOptions' => ['style' => 'overflow: auto; height: auto !important'],
        'headerRowOptions' => ['style' => 'background-color: rgb(0, 163, 222); color: white;'],
        'filterRowOptions' => ['class' => 'kartik-sheet-style'],
    ]);
    ?>
</div>

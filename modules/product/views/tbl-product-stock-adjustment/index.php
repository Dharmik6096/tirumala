<?php
$this->title = Yii::t('app', Yii::$app->label->title('list', $adjustment_type));
$action = $adjustment_type == 'Good Issue' ? 'create' : 'receipt-create';
$this->params['menu'][] = Yii::$app->controls->add($adjustment_type, $action);
?>
<div class="tbl-banks-index">
    <div class="panel panel-default panel-grid panel-main">
        <div class="panel-heading">
            <?= $this->title; ?>
        </div>
        <div class="panel-body">
            <?=
            $this->render('_form_grid', [
                'dataProvider' => $dataProvider,
                'searchModel' => $searchModel,
                'adjustment_type' => $adjustment_type,
            ])
            ?>
        </div>
    </div>
</div>

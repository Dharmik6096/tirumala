<?php
$this->title = Yii::t('app', Yii::$app->label->title('list', 'Milk Collection summary'));
$this->params['menu'][] = Yii::$app->controls->add('Milk Collection summary');
?>
<div class="tbl-local-milk-sale-index">
    <div class="panel panel-default panel-grid panel-main">
        <div class="panel-heading">
            <?= $this->title; ?>           
        </div>
        <div class="panel-body">
            <?=
            $this->render('_form_grid', [
                'dataProvider' => $dataProvider,
                'searchModel' => $searchModel,
            ])
            ?>
        </div>
    </div>
</div>


<?php
$this->title = Yii::t('app', Yii::$app->label->title('list', 'Collection Point'));
$this->params['menu'][]=Yii::$app->controls->add('Collection Point');
?>
<div class="tbl-collection-point-index">
    <div class="panel panel-default panel-grid panel-main">
        <div class="panel-heading">
            <?= $this->title; ?>
        </div>
        <div class="panel-body">
            <?=
            $this->render('_form_grid', [
                'model' => $model,
                'dataProvider' => $dataProvider,
                'searchModel' => $searchModel,
            ])
            ?>
        </div>
    </div>
</div>
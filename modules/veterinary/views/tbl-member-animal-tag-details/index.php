<?php
$this->title = Yii::t('app', Yii::$app->label->title('list', 'Member Animal Tag Details'));
$this->params['menu'][] = Yii::$app->controls->import('member-animal-tag-details', $this);
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
            ])
            ?>
        </div>
    </div>
</div>
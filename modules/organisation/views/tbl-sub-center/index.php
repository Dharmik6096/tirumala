<?php
$this->title = Yii::t('app', Yii::$app->label->title('list', 'Sub Center'));
$this->params['menu'][]=Yii::$app->controls->add('Sub Center');
$this->params['menu'][]=Yii::$app->controls->import('sub-center', $this);
?>
<div class="tbl-sub-center-index">
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
<?php
$this->title = Yii::t('app', Yii::$app->label->title('list', 'Insurance Member Details'));
?>
<div class="panel panel-default panel-grid panel-main hide-grid-settings">
    <div class="panel-heading">
        <?= $this->title.' ('.$header_detail.')'; ?>
    </div>
    <div class="panel-body">
        <?=
        $this->render('_member_details_grid', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ])
        ?>
    </div>
</div>
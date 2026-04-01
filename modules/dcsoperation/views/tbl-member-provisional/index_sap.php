<?php
$this->title = Yii::t('app', Yii::$app->label->title('list', 'SAP Error Data (Provisional Member)'));
?>
<div class="panel panel-default panel-grid panel-main">
    <div class="panel-heading">
        <?= $this->title; ?>
    </div>
    <div class="panel-body">
        <div>
            <?=
            $this->render('_from_grid_sap', [
                'dataProvider' => $dataProvider,
                'searchModel' => $searchModel,
            ])
            ?>
        </div>
    </div>
</div>

<?php

use yii\web\View;

$this->title = Yii::t('app', Yii::$app->label->title('list', 'BIPL Smart Re-Push'));
?>
<div class="tbl-local-milk-sale-index">
    <div class="panel panel-default panel-grid panel-main">
        <div class="panel-heading">
            <?= $this->title; ?>           
        </div>
        <div class="panel-body">
            <?php
            if ($searchModel->smart_master_type == 0) {
                echo $this->render('_repush_bulk_grid', [
                    'dataProvider' => $dataProvider,
                    'searchModel' => $searchModel,
                ]);
            } else {
                echo $this->render('_repush_dcs_bulk_grid', [
                    'dataProvider' => $dataProvider,
                    'searchModel' => $searchModel,
                ]);
            }
            ?>
        </div>
    </div>
</div>
<?php
if (empty($dataProvider->getModels()) || !empty($searchModel->getErrors())) {
    $script = "
            $(document).ready(function () {
                $('#search_filter').modal('toggle');
            });
        ";
    $this->registerJs($script, View::POS_READY, 'member-repush-list');
}


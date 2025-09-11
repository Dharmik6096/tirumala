<?php

use yii\web\View;

$this->title = Yii::t('app', Yii::$app->label->title('list', 'Milk Collection summary Re-Push'));
?>
<div class="tbl-local-milk-sale-index">
    <div class="panel panel-default panel-grid panel-main">
        <div class="panel-heading">
            <?= $this->title; ?>           
        </div>
        <div class="panel-body">
            <?=
            $this->render('_repush_bulk_grid', [
                'dataProvider' => $dataProvider,
                'searchModel' => $searchModel,
            ])
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
    $this->registerJs($script, View::POS_READY, 'milk-collection-summary-repush-list');
}


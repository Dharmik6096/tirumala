<?php

use yii\helpers\Url;

Url::remember();
$this->title = $title;
?>
<div class="panel panel-default panel-grid panel-main">
    <div class="panel-heading">
        <?= $this->title; ?>           
    </div>
    <div class="panel-body">
        <?= $this->render('_search', ['searchModel' => $searchModel]); ?>
        <div class="clearfix"></div>
        <?php
        //      echo $this->render('_disburse_grid', ['searchModel' => $searchModel, 'model' => $model, 'dataProvider' => $dataProvider]);
        ?>

        <?php
//
        if (!empty($model->party_payment_code)) {
            echo $this->render('_disburse_grid', ['model' => $model,
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'dataProviderDetail' => $dataProviderDetail,
                'headDetail' => $headDetail,
                'searchModelDetail' => $searchModelDetail,
                'searchModelHead' => $searchModelHead]);
        }
        ?>
    </div>
</div>

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
        <?php echo $this->render('_search', ['model' => $model]); ?>
        <div class="clearfix"></div>
        <?php
        echo $this->render('_disburse_grid', ['searchModel' => $searchModel, 'model' => $model, 'dataProvider' => $dataProvider]);
        ?>
    </div>
</div>

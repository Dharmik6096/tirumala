<?php

use yii\helpers\Url;
use yii\helpers\ArrayHelper;

$this->title = Yii::t('app', 'Tanker Milk Quality');

Url::remember();
?>
<div class="panel panel-default panel-grid panel-main hide-grid-settings">
    <div class="panel-heading">
        <?= $this->title; ?>           
    </div>
    <div class="panel-body">
        <div class="large-search">
            <?php echo $this->render('_search', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider, 'dataProviderCount' => $dataProviderCount]); ?>
        </div>
        <?php if (!empty($searchModel->parsing_no)) { ?>
            <?php
            echo $this->render('_form', ['model' => $model, 'config_list' => $config_list, 'config' => $config, 'searchModel' => $searchModel]);
            ?>
            <div class="clearfix"></div>
            <div class="panel-heading">
                <?php echo 'Tanker Milk Quality Records'; ?>
            </div>
            <?php
            echo $this->render('_add_form_grid', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider, 'config_list' => $config_list, 'tripData' => $tripData]);
            ?>
        <?php } ?>
    </div>
</div>





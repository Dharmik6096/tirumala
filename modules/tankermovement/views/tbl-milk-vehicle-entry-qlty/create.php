<?php

use yii\helpers\Url;
use yii\helpers\ArrayHelper;

$this->title = Yii::t('app', 'Tanker Milk Lot Quality');

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
        <?php
        if ($dataProviderCount > 0) {
            echo $this->render('_form', ['model' => $model, 'config_list' => $config_list, 'config' => $config]);
            ?>
            <div class="clearfix"></div>
            <div class="panel-heading">
                <?php echo 'Tanker Milk Lot Quality Records'; ?>
            </div>
            <div class="panel-body">
                <?php echo $this->render('_add_form_grid', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider, 'config_list' => $config_list]); ?>
            </div> 
        <?php } ?>
    </div>
</div>





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
            <?php echo $this->render('_search', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider]); ?>
        </div>
        <?php
        if ($dataProvider->getCount() > 0) {
            echo $this->render('_form', ['model' => $model]);
            ?>
            <div class="clearfix"></div>
            <div class="panel-heading">
                <?php echo 'Tanker Milk Quality Records'; ?>
            </div>
            <?php
            echo $this->render('_add_form_grid', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider]);
        }
        ?>
    </div>
</div>





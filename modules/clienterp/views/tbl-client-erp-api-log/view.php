<?php

use yii\helpers\Html;
use kartik\detail\DetailView;

$this->title = Yii::$app->label->title('view', 'Client ERP API Log');
?>
<div class="panel panel-default panel-grid panel-main">
    <div class="panel-heading">
        <?= Yii::$app->controls->cancel($model); ?>
        <?= Html::encode($this->title) ?>
    </div>
    <div class="panel-body hide-grid-settings">
        <div class="form-grid">
            <div class="table-responsive">
                <?php
                if ($erp_process_name == 2) {
                    $attributes = include('_milk_view.php');
                } else {
                    $attributes = include('_log_view.php');
                }
                echo DetailView::widget([
                    'model' => $model,
                    'attributes' => $attributes,
                    'mode' => 'view',
                    'bordered' => true,
                    'striped' => false,
                    'responsive' => true,
                    'hAlign' => 'left',
                    'vAlign' => 'top',
                    'container' => ['id' => 'kv-demo'],
                ]);
                ?>
            </div>
        </div>
        <?php
        if ($erp_process_name == 2) { ?>
            <div class="col-md-12 padding_10_0 theme-box view-subtitle">
                <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 margin-bottom-10 clearfix">
                    <h4 class="theme-box-heading"><?= Yii::t('app', 'Log Details') ?></h4>
                </div>
                <div class="form-grid">
                    <?=
                    $this->render('_log_grid', [
                        'searchModel' => $searchModel,
                        'dataProvider' => $dataProvider,
                    ]);
                    ?>
                </div>
            </div>
        <?php
        } ?>
    </div>
<?php

use yii\widgets\ActiveForm;
use yii\web\View;
use yii\widgets\Pjax;
use kartik\grid\GridView;
use kartik\export\ExportMenu;

$this->title = Yii::t('app', Yii::$app->label->title('list', '210 - Union-Day Crosstab'));
?>

<div class="tbl-banks-index">
    <div class="panel panel-default panel-grid panel-main">
        <div class="panel-heading">
            <?= $this->title; ?>           
        </div>
        <div class="panel-body">
            <div class="report-area">
                <?php echo $this->render('_search_dpu', ['model' => $model]); ?>

                <?php
                $attribute = [
                    ['attribute' => 'union_name', 'label' => Yii::t('app', 'Union Name'), 'filter' => false],
                ];
                if (!empty($extra))
                    $attribute = array_merge($attribute, $extra);
                $grid_option = [
                    'id' => 'shift-report',
                    'attributes' => $attribute,
                    'active_column' => false,
                        //'actions' => []
                ];


                Yii::$app->grid->bind($dataProvider, $model, $grid_option);
                ?>
            </div>
        </div>
    </div>
</div>
<?php

use yii\widgets\ActiveForm;
use yii\web\View;
use yii\widgets\Pjax;
use kartik\grid\GridView;
use kartik\export\ExportMenu;

$this->title = Yii::t('app', Yii::$app->label->title('list', '209 - Society-Day Crosstab'));
?>

<div class="tbl-banks-index">
    <div class="panel panel-default panel-grid panel-main">
        <div class="panel-heading">
            <?= $this->title; ?>           
        </div>
        <div class="panel-body">
            <?php echo $this->render('_search_dpu', ['model' => $model]); ?>
            <div class="report-area">

                <?php
                $attribute = [
                        ['attribute' => 'union_name', 'label' => Yii::t('app', 'Union Name'), 'filter' => false],
                        ['attribute' => 'district_name', 'filter' => false],
                        ['attribute' => 'dcs_code', 'filter' => false],
                        ['attribute' => 'dcs_name', 'filter' => false],
                        ['attribute' => 'MinDate', 'filter' => false],
                        ['attribute' => 'Summary', 'filter' => false],
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
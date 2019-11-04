<?php

use yii\widgets\ActiveForm;
use yii\web\View;
use yii\widgets\Pjax;
use kartik\grid\GridView;
use kartik\export\ExportMenu;

$this->title = Yii::t('app', Yii::$app->label->title('list', '204 - No Collection Summary Report'));
?>

<div class="tbl-banks-index">
    <div class="panel panel-default panel-grid panel-main">
        <div class="panel-heading">
            <?= $this->title; ?>           
        </div>
        <div class="panel-body">
            <?php echo $this->render('_search_custom', ['model' => $model]); ?>
            <div class="report-area">

                <?php
                $attribute = [
                        ['attribute' => 'union_name', 'value' => 'union_name', 'label' => Yii::t('app', 'Union Name'), 'filter' => false],
                        ['attribute' => 'total_dcs', 'value' => 'total_dcs', 'label' => Yii::t('app', 'No of Societies'), 'filter' => false],
                        //['attribute' => 'dcs_code', 'value' => 'dcs_code', 'filter' => false],
                ];

                $grid_option = [
                    'id' => 'report-no-cln-dcs',
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
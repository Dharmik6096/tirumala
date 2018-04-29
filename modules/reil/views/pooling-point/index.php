<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\reil\models\PoolingPointSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */


$this->title = Yii::t('app', Yii::$app->label->title('list', 'Pooling Points'));
?>

<div class="pooling-point-index">
    <div class="panel panel-default panel-grid panel-main">
        <div class="panel-heading">
            <?= $this->title; ?>
        </div>
        <div class="panel-body">
            
             <?php echo $this->render('_search', ['model' => $searchModel]); ?>
            
             <?php

                $attribute = [
                    ['attribute' => 'PlantCode'],
                    ['attribute' => 'PlantName'],
                    ['attribute' => 'BMCCode'],
                    ['attribute' => 'BMCName'],
                    ['attribute' => 'PPCode'],
                    ['attribute' => 'PlantCode', 'visible' => false],
                    ['attribute' => 'VillageCode', 'visible' => false],
                    ['attribute' => 'IMEINo', 'visible' => false],
                    ['attribute' => 'DPU_INIT_DTTM', 'visible' => false],
                    ['attribute' => 'RATEID_B', 'visible' => false],
                    ['attribute' => 'RATEID_C', 'visible' => false],
                    ['attribute' => 'DPU_RATE_ID_B', 'visible' => false],
                    ['attribute' => 'DPU_RATE_ID_C', 'visible' => false],
                    ['attribute' => 'DPURate_update_DTTM_B', 'visible' => false],
                    ['attribute' => 'DPURate_update_DTTM_C', 'visible' => false],
                    ['attribute' => 'PPACTIVE', 'visible' => false],
                    ['attribute' => 'DPU_MEM_UPDATION_REQUIRED', 'visible' => false],
                    ['attribute' => 'MID', 'visible' => false],
                    ['attribute' => 'DPU_MID', 'visible' => false],
                    ['attribute' => 'DPUMem_Update_DTTM', 'visible' => false],
                ];

                $grid_option = [
                    'id' => 'pooling-list',
                    'attributes' => $attribute,
                    'active_column' => false,
                    'actions' => [
                        'view' => true,
                    ]
                ];

                Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
            ?>
        </div>
    </div>
</div>

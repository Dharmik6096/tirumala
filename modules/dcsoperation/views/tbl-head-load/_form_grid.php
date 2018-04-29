<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\helpers\Html;
use app\components\GeneralFunctions;
?>

<div class="grid-search clearfix">
    <?php echo $this->render('_search', ['model' => $searchModel]); ?>
</div>

<?php
$attribute = [
    ['attribute' => 'head_load_code','value'=>'head_load_code'],
    ['attribute' => 'criteria_description','value'=>'criteria_description'],
    ['attribute' => 'criteria_type_code','value'=>'criteriaTypeCode.criteria_name'],
    
];

$grid_option = [
    'id' => 'head-load-grid',
    'attributes' => $attribute,
    'active_column' => true,
    'actions' => [
        'view' => true,
        'update'=>true,
        'add' => function ($url, $model) {
            $disable = ($model->is_active==0)?'disabled':'';
            $options = ['data-val' => $model->head_load_code, 'title'=>'Transaction','class'=>$disable];
            return Html::a('<span class="glyphicon glyphicon-plus"></span>', ['/dcsoperation/tbl-head-load-transaction/create', 'id' => $model->head_load_code], $options);
        },
        'mapping' => function ($url, $model) {
            $disable = ($model->is_active==0)?'disabled':'';
            $options = ['data-name' => $model->head_load_code, 'data-val' => $model->head_load_code, 'title'=>'Mapping','class'=>$disable];
            return Html::a('<span class="glyphicon glyphicon-link"></span>', ['/dcsoperation/tbl-head-load/map-dcs', 'id' => $model->head_load_code], $options);
        }
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
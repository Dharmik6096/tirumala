<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\helpers\Html;
use app\components\GeneralFunctions;
use webvimark\modules\UserManagement\components\GhostHtml;
use yii\helpers\Url;
use yii\web\View;
?>

<div class="grid-search clearfix">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
</div>

<?php
$attribute = [
    ['attribute' => 'union_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        },
        'filter' => false, 'visible' => false],
    ['attribute' => 'head_load_code', 'value' => 'head_load_code', 'vAlign' => 'middle', 'visible' => false],
    ['attribute' => 'fix_value', 'vAlign' => 'middle',],
    ['attribute' => 'min_km', 'vAlign' => 'middle',],
    ['attribute' => 'min_qty', 'vAlign' => 'middle',],
    ['attribute' => 'max_qty', 'vAlign' => 'middle',],
    ['attribute' => 'criteria_description', 'value' => 'criteria_description', 'vAlign' => 'middle',],
];

$grid_option = [
    'id' => 'head-load-grid',
    'attributes' => $attribute,
    'active_column' => true,
    'actions' => [
        'view' => true,
        'mapping' => function ($url, $model) {
            $disable = ($model->is_active == 0) ? 'disabled' : '';
            $options = ['data-name' => $model->head_load_code, 'data-val' => $model->head_load_code, 'title' => Yii::t('app', 'Applicability'), 'class' => $disable];
            return GhostHtml::a('<i class="fa fa-plus"></i>', ['/vsp/tbl-head-load/head-load-applicability', 'id' => $model->head_load_code], $options);
        },
        'in-active' => function ($url, $model) {
            $disable = ($model->is_active == 0) ? 'disabled' : '';
            $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Deactivate', 'class' => 'inact-record ' . $disable, 'data-val' => $model->head_load_code, 'data-name' => $model->head_load_code, 'title' => Yii::t('app', 'InActive')];
            return GhostHtml::a_alert('<i class="fa fa-close"></i>', ['/vsp/tbl-head-load/in-active'], $options);
        },
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>

<?php
$script = "
  $(document).ready(function(){
    $(document).on('click','.inact-record',function(e){
    var id= $(this).attr('data-val');
    var name = $(this).attr('data-name');
   bootbox.confirm({
        message: '<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-question\'></i></div><span>Are you sure you want to deactivate \"'+name+'\"?</span></div></div>',
        buttons: {
            'cancel': {
                            label: 'Cancel',
                            className: 'btn btn-danger'
              },
            'confirm': {
                            label: 'Ok',
                            className: 'btn btn-primary'
             }
        },
        callback: function(result) {
            if (result) {
                 $.ajax({
                        type: 'get',
                        url: '" . Url::to(['in-active']) . "',
                        data:{'id':id},
                        success: function(data) {                  
                        },                       
            });
            }
        }
    });
    });
});";
$this->registerJs($script, View::POS_END, 'head-load-index');

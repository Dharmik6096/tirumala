<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\helpers\Html;
use app\components\GeneralFunctions;
use kartik\grid\GridView;
?>

<div class="grid-search clearfix">
    <?php echo $this->render('_search', ['model' => $searchModel]); ?>
</div>

<?php
$attribute = [
    ['attribute' => 'meeting_agenda_code','value'=>'meeting_agenda_code',],
    ['attribute' => 'meeting_type_code','value'=>'meetingTypeCode.meeting_type_name',],
    ['attribute' => 'subject_line','value'=>'subject_line',],
    [
        'attribute' => 'meeting_date',
        'filterType'=>GridView::FILTER_DATE,
        'filterWidgetOptions'=>[
            'pluginOptions'=>['format'=>'dd-mm-yyyy',
                'autoclose'=>true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->meeting_date);
        }],
    [
        'attribute' => 'date',
        'visible' => false,'filter'=>false,
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->date);
        }],
                ['attribute' => 'detailed_agenda', 'visible' => false,'filter'=>false,],
                ['attribute' => 'meeting_time', 'visible' => false,'filter'=>false,],
];

$grid_option = [
    'id' => 'meeting-agenda-grid',
    'attributes' => $attribute,
    'active_column' => true,
    'actions' => [
        'view' => true,
        'attandance' => function ($url, $model) {
            $options = ['data-val' => $model->meeting_agenda_code, 'title'=>'Attendance'];
            return Html::a('<span class="glyphicon glyphicon-link"></span>', ['/dcsoperation/tbl-meeting-agenda/view-attandance', 'id' => $model->meeting_agenda_code], $options);
        },
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
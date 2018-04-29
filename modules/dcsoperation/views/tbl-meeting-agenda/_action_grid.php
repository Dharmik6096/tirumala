 <?php
 
 use kartik\grid\GridView;
 
    $attribute = [
        ['attribute' => 'meeting_agenda_code','label'=>'Meeting Agenda Subject','value'=>'meetingAgendaCode.subject_line',],
        [
        'attribute' => 'date',
            'filterType'=>GridView::FILTER_DATE,
        'filterWidgetOptions'=>[
            'pluginOptions'=>['format'=>'dd-mm-yyyy',
                'autoclose'=>true]
        ],
        //'filter' => Yii::$app->controls->search_date($searchModel,'date'),
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->date);
        }],
        ['attribute' => 'dcs_code','value'=>'dcsCode.dcs_name',],        
        ['attribute' => 'action_taken','value'=>'action_taken',],
    ];

    $grid_option = [
        'id' => 'mom-action-grid',
        'attributes' => $attribute,
        'active_column' => true,
    ];

    Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option,['mom-action','id'=> Yii::$app->request->get('id')]);
    ?>
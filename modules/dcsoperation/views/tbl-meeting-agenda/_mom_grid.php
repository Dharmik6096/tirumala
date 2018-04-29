 <?php 
 use yii\helpers\Html;
 ?>

<?php
        $attribute = [
            ['attribute' => 'mom_code','value'=>'mom_code',],
            ['attribute' => 'mom','value'=>'mom',],
            [
                'attribute' => 'status',
                'filter' => Html::activeDropDownList($searchModel, 'status', [1=>'Open',0=>'Close'],['class'=>'form-control','prompt'=>'Select']),
                'value' => function($model) {
                    return ($model->status==1)?'Open':'Close';}],
            ['attribute' => 'dcs_code','value'=>'dcsCode.dcs_name',],
            ['attribute' => 'meeting_type_code','value'=>'meetingTypeCode.meeting_type_name',],
        ];

        $grid_option = [
            'id' => 'mom-grid',
            'attributes' => $attribute,
            'active_column' => true,
            'actions' => [
                'action' => function ($url, $model) {
                    $options = ['data-val' => $model->mom_code, 'title'=>'MOM Action'];
                    return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['/dcsoperation/tbl-meeting-agenda/mom-action', 'id' => $model->mom_code], $options);
                }
            ]
        ];

        Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option,['view','id'=> Yii::$app->request->get('id')]);
        ?>
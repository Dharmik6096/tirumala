<?php

use yii\widgets\ActiveForm;
use yii\grid\GridView;
use yii\web\View;
use yii\widgets\Pjax;


$request = Yii::$app->request->queryParams;
$min_date = empty($request["min_date"]) ? '' : $request["min_date"];
$max_date = empty($request["max_date"]) ? '' : $request["max_date"];
?>

<?php
$form = ActiveForm::begin([
            'action' => ['view'],
            'method' => 'get',
            'id' => 'village-form-view',
            'validateOnSubmit' => true,
        ]);
$this->title = Yii::t('app', Yii::$app->label->title('list', 'Village Wise Shift Milk Collections'));
?>
<div class="tbl-banks-index">
    <div class="panel panel-default panel-grid panel-main">
        <div class="panel-heading">
            <?= $this->title; ?>           
        </div>
        <div class="panel-body">
            <div class="col-sm-2">
                <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', FALSE); ?>
            </div>
            <div class="col-sm-2">
                <?= Yii::$app->dropdown->depend_dropdown('dcs', $model, $form, 'tblmilkcollectionsearch-union_code'); ?>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <?= Yii::$app->controls->min_max_date('min_date', 'max_date',$min_date,$max_date); ?>
                </div>
            </div>
            <div class="col-sm-2">
                <?= Yii::$app->controls->search(); ?>
            </div>
            <?php ActiveForm::end(); ?>

                           <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $model,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    'village_name',
                    'total_shift',
                    'get_shift_data',
                ],
            ]); ?>
            
        </div>
       
    </div>
</div>

<?php
$script = "
    $('body').on('submit', '#village-form-view', function() {    
    var dcs_code = $('#tblmilkcollectionsearch-dcs_code').val();
    var min_date = $('#w0').val();
    var min_date = $('#w0').val();
    var max_date = $('#w0-2').val();
    if(dcs_code != ''){
       if(min_date == ''){
         alert ('Please Select From Date');
         return false;
        }
        else if(max_date == ''){
          alert ('Please Select To Date');
          return false;
       }
    }
});";
        
$this->registerJs($script, View::POS_END, 'date-validate');


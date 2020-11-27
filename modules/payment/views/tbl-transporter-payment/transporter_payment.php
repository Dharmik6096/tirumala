<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\web\View;
use yii\helpers\Html;
use kartik\grid\GridView;
$this->title = 'Transporter Payment: Step 2';
?>
<?php
$array = $dataProvider->allModels;
$tot_amt = array_sum(array_map(function($array) {
            return $array['final_amount'];
        }, $array));
?>

    <div class="panel panel-default panel-grid panel-main">
        <div class="panel-body"> 
            <div class="panel-heading">
                <?= $this->title; ?>    
                <div id="total-payment">
                    Total Payable :: <?= $tot_amt; ?>
                </div>        
            </div> 
            <?php
            $form = ActiveForm::begin([
                        'action' => ['save-transporter-payment'],
                        'id' => 'transporter-payment',
                        'validateOnBlur' => TRUE,
                        
                        'validateOnChange' => TRUE,
                        'enableClientValidation' => true,
                        'validateOnSubmit' => true,
            ]);
            ?>
            <?php
            $attribute = [
                ['class' => 'kartik\grid\SerialColumn'],
                [
                    'class' => 'kartik\grid\ExpandRowColumn',
                    'width' => '50px',
                    'expandIcon'=>'<span class="fa fa-plus"></span>',
                    'collapseIcon'=>'<span class="fa fa-minus"></span>',
                    'expandTitle' => 'View Vehicles',
                    'expandAllTitle' => 'View All Vehicles',
                    'collapseTitle' => 'Hide Vehicles',
                    'collapseAllTitle' => 'Hide All Vehicles',
                    'value' => function ($model, $key, $index, $column) {
                        return GridView::ROW_COLLAPSED;
                    },
                    'detail' => function ($data,$key, $index, $column) use($form,$model) {
                        $model->transporter_code = $data['transporter_code'];
                        return Yii::$app->controller->renderPartial('_expand-vehicle-details', ['model' => $model,'index'=>$index,'form'=>$form]);
                    },
                    'headerOptions' => ['class' => 'kartik-sheet-style'] ,
                    'expandOneOnly' => true
                ],
                [
                    'attribute' => 'transporter_name',
                    'hAlign' => Yii::$app->general->ColoumnAlign(),
//                    'class' => 'test',
                    'format' => 'raw',
                    'value' => function ($data,$key, $index) use ($form,$model) {
                        return Html::activeHiddenInput($model, '['.$index.']union_code', ['value' => $model['union_code']]).Html::activeHiddenInput($model, '['.$index.']from_date', ['value' => $model['from_date']]).Html::activeHiddenInput($model, '['.$index.']to_date', ['value' => $model['to_date']]).Html::activeHiddenInput($model, '['.$index.']transporter_code', ['value' => $data['transporter_code']]).$data['transporter_name'];
                    },
                ],
                [
                    'attribute' => 'bmc_name',
                    'hAlign' => Yii::$app->general->ColoumnAlign(),
                    'format' => 'raw',
                    'value' => function ($data,$key, $index) use ($form,$model) {
                        return Html::activeHiddenInput($model, '['.$index.']bmc_code', ['value' => $data['bmc_code']]).$data['bmc_name'];
                    },
                ],
                [
                    'attribute' => 'total_vehicle',
                    'hAlign' => Yii::$app->general->ColoumnAlign(),
                    'format' => 'raw',
                    'value' => function ($data,$key, $index) use ($form,$model) {
                        return $form->field($model, '['.$index.']total_vehicle')->textInput(['class' => 'total_vehicle form-control', "readonly" => TRUE,'value' => $data['total_vehicle']])->label(FALSE);
                    },
                ],
                [
                    'attribute' => 'coll_qty',
                    'hAlign' => Yii::$app->general->ColoumnAlign(),
                    'format' => 'raw',
                    'value' => function ($data,$key, $index) use ($form,$model) {
                        return $form->field($model, '['.$index.']coll_qty')->textInput(['class' => 'coll_qty form-control', "readonly" => TRUE,'value' => $data['coll_qty']])->label(FALSE);
                    },
                ],
                [
                    'attribute' => 'coll_kg_fat',
                    'hAlign' => Yii::$app->general->ColoumnAlign(),
                    'format' => 'raw',
                    'value' => function ($data,$key, $index) use ($form,$model) {
                        return $form->field($model, '['.$index.']coll_kg_fat')->textInput(['class' => 'coll_kg_fat form-control', "readonly" => TRUE,'value' => $data['coll_kg_fat']])->label(FALSE);
                    },
                ],
                [
                    'attribute' => 'coll_kg_snf',
                    'hAlign' => Yii::$app->general->ColoumnAlign(),
                    'format' => 'raw',
                    'value' => function ($data,$key, $index) use ($form,$model) {
                        return $form->field($model, '['.$index.']coll_kg_snf')->textInput(['class' => 'coll_kg_snf form-control', "readonly" => TRUE,'value' => $data['coll_kg_snf']])->label(FALSE);
                    },
                ],
                [
                    'attribute' => 'disp_qty',
                    'hAlign' => Yii::$app->general->ColoumnAlign(),
                    'format' => 'raw',
                    'value' => function ($data,$key, $index) use ($form,$model) {
                        return $form->field($model, '['.$index.']disp_qty')->textInput(['class' => 'disp_qty form-control', "readonly" => TRUE,'value' => $data['disp_qty']])->label(FALSE);
                    },
                ],
                [
                    'attribute' => 'disp_kg_fat',
                    'hAlign' => Yii::$app->general->ColoumnAlign(),
                    'format' => 'raw',
                    'value' => function ($data,$key, $index) use ($form,$model) {
                        return $form->field($model, '['.$index.']disp_kg_fat')->textInput(['class' => 'disp_kg_fat form-control', "readonly" => TRUE,'value' => $data['disp_kg_fat']])->label(FALSE);
                    },
                ],
                [
                    'attribute' => 'disp_kg_snf',
                    'hAlign' => Yii::$app->general->ColoumnAlign(),
                    'format' => 'raw',
                    'value' => function ($data,$key, $index) use ($form,$model) {
                        return $form->field($model, '['.$index.']disp_kg_snf')->textInput(['class' => 'disp_kg_snf form-control', "readonly" => TRUE,'value' => $data['disp_kg_snf']])->label(FALSE);
                    },
                ],
                [
                    'attribute' => 'no_of_days',
                    'hAlign' => Yii::$app->general->ColoumnAlign(),
                    'format' => 'raw',
                    'value' => function ($data,$key, $index) use ($form,$model) {
                        return $form->field($model, '['.$index.']no_of_days')->textInput(['class' => 'no_of_days form-control', "readonly" => TRUE,'value' => $data['no_of_days']])->label(FALSE);
                    },
                ],
                [
                    'attribute' => 'total_amount',
                    'hAlign' => Yii::$app->general->ColoumnAlign(),
                    'format' => 'raw',
                    'value' => function ($data,$key, $index) use ($form,$model) {
                        return $form->field($model, '['.$index.']total_amount')->textInput(['class' => 'total_amount form-control', "readonly" => TRUE,'value' => $data['total_amount']])->label(FALSE);
                    },
                    'pageSummary' => true
                ],
                [
                    'attribute' => 'total_deduction',
                    'hAlign' => Yii::$app->general->ColoumnAlign(),
                    'format' => 'raw',
                    'value' => function ($data,$key, $index) use ($form,$model) {
                        return $form->field($model, '['.$index.']total_deduction')->textInput(['class' => 'total_deduction form-control', "readonly" => TRUE,'value' => $data['total_deduction']])->label(FALSE);
                    },
                ],
                [
                    'attribute' => 'final_amount',
                    'hAlign' => Yii::$app->general->ColoumnAlign(),
                    'format' => 'raw',
                    'value' => function ($data,$key, $index) use ($form,$model) {
                        return $form->field($model, '['.$index.']final_amount')->textInput(['class' => 'final_amount form-control', "readonly" => TRUE,'value' => $data['final_amount']])->label(FALSE);
                    },
                    'pageSummary' => true
                ],
                [
                    'attribute' => 'adjust_amount',
                    'hAlign' => Yii::$app->general->ColoumnAlign(),
                    'format' => 'raw',
//                    'pageSummary' => true,
                    'value' => function ($data,$key, $index) use ($form,$model) {
                        return $form->field($model, '['.$index.']adjust_amount')->textInput(['class' => 'adjust-amount form-control','value' => '','data-key'=>$index])->label(FALSE);
                    },
                ],
                ['attribute' => 'net_amount',
                        'format' => 'raw',
                        'value' => function ($data,$key, $index) use ($form,$model) {
                            return $form->field($model, '['.$index.']net_amount')->textInput(['class' => 'net-amount form-control', "readonly" => TRUE])->label(FALSE);
                        },
                ],
                ['attribute' => 'remarks',
                    'format' => 'raw',
                    'value' => function ($data,$key, $index) use ($form,$model) {
                        return $form->field($model, '['.$index.']remarks')->textInput(['value' => ''])->label(FALSE);
                    },
                ],
            ];

            echo GridView::widget([
                'bordered' => true,
                'hover' => true,
                'resizableColumns'=>false,
                'layout' => '{items}{pager}',
                'id' => 'transporter-summary-grid',
//                'floatHeader' => true,
//                'floatOverflowContainer' => true,
                'dataProvider' => $dataProvider,
                'columns' => $attribute, // check the configuration for grid columns by clicking button above
                'containerOptions' => ['style' => 'overflow: auto'], // only set when $responsive = false
                'headerRowOptions' => ['class' => 'kartik-sheet-style'],
                'filterRowOptions' => ['class' => 'kartik-sheet-style'],
//                'showPageSummary' => true
            ]);
            ?>
        </div>
        <div class="panel-footer" >
            <?= Html::button(Yii::t('app', 'Confirm'), ['class' => 'btn btn-primary', 'id' => 'adjust']); ?>
            <?= Yii::$app->controls->custombutton('Cancel', 'create'); ?> 
        </div>
        <?php ActiveForm::end(); ?>
    </div>
<?php
$script = '$("#adjust").click(function() {
   $("#transporter-payment").submit();
});
';
$script .= "
    SumAmount();
    $('.adjust-amount').change(function(){
        var adjust_amount = parseFloat($(this).val());
        var data_key = $(this).attr('data-key');
        var net = $('#tbltransporterpayment-'+data_key+'-net_amount').val();
        var final_amount = parseFloat($('#tbltransporterpayment-'+data_key+'-final_amount').val());
        if(adjust_amount == '' || isNaN(adjust_amount)){
            adjust_amount = 0;
        }
        var diff = final_amount + adjust_amount;
        if(diff == '0'){
            diff = '';
        }
        $('#tbltransporterpayment-'+data_key+'-net_amount').val(diff);
        SumAmount();
    });
    function SumAmount(){
        var total = parseFloat(0.00);
        $('.adjust-amount').each(function() {
            var adjust =  parseFloat($(this).val());
            if(adjust != '' &&  !isNaN(adjust)){
                total = total + adjust;
            }
        });
        $('.final_amount').each(function(){
            var final =  parseFloat($(this).val());
            if(final != '' &&  !isNaN(final)){
                total = total + final;
            }
        });
        $('#total-payment').html('Total Payable :: '+total.toFixed(2))
    }

    $('input[type=checkbox]').change(function(){
        var change = '';
        var checkbox_par = $(this).closest('tr').closest('table').closest('div').closest('div').closest('div').closest('div').closest('div').closest('div').closest('tr').closest('tbody tr');
        var id = checkbox_par.attr('data-key');
        
        $('#vehicle-summary-grid-'+ id + ' input[type=checkbox]').each(function () {
            if (this.checked) {
                change = change + ',' + $(this).val();
            }
        });
        var from_date = $('#tbltransporterpayment-'+id+'-from_date').val();
        var to_date = $('#tbltransporterpayment-'+id+'-to_date').val();
        var transporter_code = $('#tbltransporterpayment-'+id+'-transporter_code').val();
        var bmc_code = $('#tbltransporterpayment-'+id+'-bmc_code').val();
        var adjust_amount = $('#tbltransporterpayment-'+id+'-adjust_amount').val();
        $.ajax({
            type: 'post',
            url:'" . Url::to(['transporter-summary']) . "',
            data: {'vehicle_code':change,'from_date':from_date, 'to_date':to_date, 'transporter_code':transporter_code,'bmc_code':bmc_code },
            success: function(data) {                                        
                  var obj = $.parseJSON(data);
                  if (obj.status == 'success')
                  {
                    $.each(obj.data.list, function(index, value) {
                        var result = Object.keys(value).map(function(key) {
                            return [key, value[key]];
                        });
                        $.each(result, function( index, value ) {
                            $('#tbltransporterpayment-'+id+'-'+value[0]).val(value[1]);
                        });
                        SumAmount();
                    });
                  }else{
//                    console.log('error'); 
                  }
            },
            error:function(data){
		
	    }
	});
    });";
$script .= "$('.kv-panel-before').hide();";
$this->registerJs($script, View::POS_END, 'panel-before-hide');
?>
<?php

use yii\bootstrap5\ActiveForm;
use yii\helpers\Url;
use yii\web\View;
use yii\helpers\Html;
use kartik\grid\GridView;
use app\modules\globalmaster\models\TblAnimalType;
use app\modules\usermanagement\components\GhostHtml;
?>
<div class=""></div>
<?php
$form = ActiveForm::begin([
            'id' => 'update-milk-dispatch',
        ]);
?>

<div class=" no-effect table_form" >
    <?php
    $attribute = [
        ['attribute' => 'dcs_code', 'label' => Yii::t('app', 'BMC'), 'value' => function ($model, $key, $index) use ($form) {
                echo Html::activeHiddenInput($model, '[' . $index . ']dcs_milk_dispatch_code', ['value' => $model->dcs_milk_dispatch_code]);
                echo Html::activeHiddenInput($model, '[' . $index . ']union_code', ['value' => Yii::$app->general->getforeignkey($model->dcsMilkDispatch, 'union_code')]);
                echo Html::activeHiddenInput($model, '[' . $index . ']dcs_code', ['value' => $model->dcs_code]);
                return Yii::$app->general->getmultiforeignkey($model->dcsMilkDispatch, ['bmcCode'], 'bmc_name');
            }, 'filter' => false],
        ['attribute' => 'dcs_code', 'filter' => FALSE],
        ['attribute' => 'dcs_code', 'label' => Yii::t('app', 'Code Ex.'), 'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_code_ex');
            }],
        ['attribute' => 'dcs_code', 'label' => Yii::t('app', 'Name'), 'value' => function($model) {
                return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name');
            }, 'filter' => false],
        ['label' => 'Date', 'attribute' => 'date_time_of_dispatch',
            'filterType' => GridView::FILTER_DATE,
            'filterWidgetOptions' => [
                'pluginOptions' => ['format' => 'dd-mm-yyyy',
                    'autoclose' => true]
            ],
            'value' => function ($model, $key, $index) use ($form) {
                echo Html::activeHiddenInput($model, '[' . $index . ']date_time_of_dispatch', ['value' => Yii::$app->controls->view_date(Yii::$app->general->getforeignkey($model->dcsMilkDispatch, 'date_time_of_dispatch'), 'php:Y-m-d')]);
                return Yii::$app->controls->view_date(Yii::$app->general->getforeignkey($model->dcsMilkDispatch, 'date_time_of_dispatch'));
            }, 'filter' => false],
        ['attribute' => 'shift_code', 'label' => Yii::t('app', 'Shift'), 'value' => function ($model, $key, $index) use ($form) {
                echo Html::activeHiddenInput($model, '[' . $index . ']shift_code', ['value' => Yii::$app->general->getforeignkey($model->dcsMilkDispatch, 'shift_code')]);
                return Yii::$app->general->getmultiforeignkey($model->dcsMilkDispatch, ['shiftCode'], 'shift');
            }, 'filter' => false],
        ['attribute' => 'milk_type_code',
            'format' => 'raw',
            'value' => function ($model, $key, $index) use ($form, $detailModel) {
                return '<span class=\'rtpl_validate\'>' . Yii::$app->dropdown->dropdown('milk_type_code', $model, $form, '', FALSE, FALSE, '[' . $index . ']milk_type_code', FALSE, TRUE, $model->milk_type_code) . '</span>';
            },
        ],
        ['attribute' => 'milk_quality_type_code',
            'format' => 'raw',
            'value' => function ($model, $key, $index) use ($form, $detailModel) {
                return '<span class=\'rtpl_validate\'>' . Yii::$app->dropdown->dropdown('milk_quality_type_code', $model, $form, '', FALSE, FALSE, '[' . $index . ']milk_quality_type_code', FALSE, TRUE, $model->milk_quality_type_code) . '</span>';
            },
        ],
        ['attribute' => 'dispatch_qty',
            'format' => 'raw',
            'value' => function ($model, $key, $index) use ($form) {
                return '<span class=\'qty_change\'>' . $form->field($model, '[' . $index . ']dispatch_qty')->textInput(['value' => $model->dispatch_qty, 'class' => 'form-control number-validate',])->label(FALSE) . '</span>';
            },
        ],
        ['attribute' => 'avg_fat',
            'format' => 'raw',
            'value' => function ($model, $key, $index) use ($form) {
                return '<span class=\'rtpl_validate\'>' . $form->field($model, '[' . $index . ']avg_fat')->textInput(['value' => $model->avg_fat, 'class' => 'form-control number-validate',])->label(FALSE) . '</span>';
            },
        ],
        ['attribute' => 'avg_snf',
            'format' => 'raw',
            'value' => function ($model, $key, $index) use ($form) {
                return '<span class=\'rtpl_validate\'>' . $form->field($model, '[' . $index . ']avg_snf')->textInput(['value' => $model->avg_snf, 'class' => 'form-control number-validate',])->label(FALSE) . '</span>';
            },
        ],
        ['attribute' => 'avg_clr',
            'format' => 'raw',
            'value' => function ($model, $key, $index) use ($form) {
                return $form->field($model, '[' . $index . ']avg_clr')->textInput(['value' => $model->avg_clr, 'class' => 'form-control number-validate', 'readonly' => TRUE])->label(FALSE);
            },
        ],
        ['attribute' => 'rtpl',
            'format' => 'raw',
            'value' => function ($model, $key, $index) use ($form) {
                echo Html::activeHiddenInput($model, '[' . $index . ']purchase_rate_code', ['value' => $model->purchase_rate_code]);
                return '<span class=\'qty_change\'>' . $form->field($model, '[' . $index . ']rtpl')->textInput(['class' => 'form-control', 'readonly' => TRUE])->label(FALSE);
            },
        ],
        ['attribute' => 'total_amount',
            'format' => 'raw',
            'value' => function ($model, $key, $index) use ($form) {
                return $form->field($model, '[' . $index . ']total_amount')->textInput(['class' => 'form-control', 'readonly' => TRUE])->label(FALSE);
            },
        ],
    ];

    $grid_option = [
        'id' => 'update-milk-dispatch-list-data',
        'attributes' => $attribute,
        'active_column' => false,
        'showPageSummary' => false,
    ];

    Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option, ['#'], false);
    ?>
</div>
<div class="panel-footer" >
    <?php
    if (!empty($dataProvider->getModels())) {
        echo Html::button(Yii::t('app', 'Update'), ['class' => 'btn btn-primary', 'id' => 'update']);
    }
    ?>
    <?= Yii::$app->controls->custombutton('Cancel', 'index'); ?> 
</div>

<?php ActiveForm::end(); ?>

<?php
$script = "
    $('.kv-panel-before').hide();
    $('#update').click(function(e) {
        e.preventDefault();
        $('#update-milk-dispatch').submit();
    });
    $(document).on('change','span.rtpl_validate select', function() { 
        var tr_key = $(this).closest('tr').attr('data-key');
         rtpl(tr_key);
    });
    $(document).on('change','span.rtpl_validate input', function() { 
        var tr_key = $(this).closest('tr').attr('data-key');
         rtpl(tr_key);
          calculateClr(tr_key);
    });
      $(document).on('change','span.qty_change input', function() { 
        var tr_key = $(this).closest('tr').attr('data-key');
         amount(tr_key);
    });
    

 function rtpl(tr_key){
        $('#tbldcsmilkdispatchtxn-'+tr_key+'-rtpl').val('');
        $('#tbldcsmilkdispatchtxn-'+tr_key+'-rate_code').val('');
        $('#tbldcsmilkdispatchtxn-'+tr_key+'-amount').val('');
     
         var dcs = $('#tbldcsmilkdispatchtxn-'+tr_key+'-dcs_code').val();
         var milk_type = $('#tbldcsmilkdispatchtxn-'+tr_key+'-milk_type_code').val();
         var dt_date = $('#tbldcsmilkdispatchtxn-'+tr_key+'-date_time_of_dispatch').val();
         var shift = $('#tbldcsmilkdispatchtxn-'+tr_key+'-shift_code').val();
         var fat = $('#tbldcsmilkdispatchtxn-'+tr_key+'-avg_fat').val();
         var snf = $('#tbldcsmilkdispatchtxn-'+tr_key+'-avg_snf').val();
         var milk_quality_type = $('#tbldcsmilkdispatchtxn-'+tr_key+'-milk_quality_type_code').val();
         console.log(dcs);
         console.log(milk_type);
         console.log(dt_date);
         console.log(shift);
         console.log(fat);
         console.log(snf);
         console.log(milk_quality_type);
        if(dcs != '' && milk_type != '' && dt_date!= '' && shift != '' && fat != '' && snf != '' && milk_quality_type != ''){
            $.ajax({
                type: 'post',
                url:'" . Url::to(['validate-rtpl']) . "',
                data: {'dcs_code':dcs,'milk_type':milk_type,'milk_quality_type':milk_quality_type,'dt_date':dt_date,'shift_code':shift,'fat':fat,'snf':snf},
                success: function(data) {   
                      var obj = $.parseJSON(data);
                      if (obj.status == 'success')
                      {
                            $('#tbldcsmilkdispatchtxn-'+tr_key+'-rtpl').val(obj.data.list.rtpl);
                            $('#tbldcsmilkdispatchtxn-'+tr_key+'-purchase_rate_code').val(obj.data.list.purchase_rate_code);
                            $('#tbldcsmilkdispatchtxn-'+tr_key+'-rtpl').trigger('change');
                        }else{
                            bootbox.alert('<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>RTPL Not Available</span></div></div>');
                            $('#tbldcsmilkdispatchtxn-'+tr_key+'-rtpl').val('');
                            $('#tbldcsmilkdispatchtxn-'+tr_key+'-purchase_rate_code').val('');
                      }
                },
                error:function(data){

                }
            });
        }else{
            $('#tbldcsmilkdispatchtxn-'+tr_key+'-rtpl').val('');
            $('#tbldcsmilkdispatchtxn-'+tr_key+'-purchase_rate_code').val('');
        }
    }

   
    function amount(tr_key){
        var amount = 0;
        var rtpl = parseFloat($('#tbldcsmilkdispatchtxn-'+tr_key+'-rtpl').val());
        var qty = parseFloat($('#tbldcsmilkdispatchtxn-'+tr_key+'-dispatch_qty').val());
        if(rtpl == '' || isNaN(rtpl)){
            rtpl = 0;
        }
        if(qty == '' || isNaN(qty)){
            qty = 0;
        }
        amount = rtpl * qty;
        $('#tbldcsmilkdispatchtxn-'+tr_key+'-total_amount').val(amount.toFixed(2));
    }
    
     function calculateClr(tr_key){
        var union = $('#tbldcsmilkdispatch-'+tr_key+'-union_code').val();
        var fat = $('#tbldcsmilkdispatchtxn-'+tr_key+'-avg_fat').val();
        var snf = $('#tbldcsmilkdispatchtxn-'+tr_key+'-avg_snf').val();
        console.log(union);
        console.log(fat);
        console.log(snf);
            if(fat !='' && snf !=''){
                $.ajax({
                    type: 'post',
                    url:'" . Url::to(['calculate-clr']) . "',
                    data: {'union_code':union,'fat':fat,'snf':snf},
                    success: function(data) {                                        
                        var obj = $.parseJSON(data);
                        if (obj.status == 'success')
                        {
                            $('#tbldcsmilkdispatchtxn-'+tr_key+'-avg_clr').val(obj.data.toFixed(2));
                        }
                    },
                    error:function(data){

                    }
                });
            }
    };



      ";
$this->registerJs($script, View::POS_END, 'update-milk-dispatch');

<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\web\View;
use yii\helpers\Html;
use kartik\grid\GridView;
use app\modules\globalmaster\models\TblAnimalType;
use webvimark\modules\UserManagement\components\GhostHtml;
?>
<div class=""></div>
<?php
$form = ActiveForm::begin([
            'id' => 'update-milk-collection',
        ]);
?>

<div class=" no-effect table_form" >
    <?php
    $attribute = [
        ['attribute' => 'dcs_code', 'header' => Yii::t('app', 'DCS'),
            'value' => function ($model, $key, $index) use ($form) {
                echo Html::activeHiddenInput($model, '[' . $index . ']milk_collection_code', ['value' => $model->milk_collection_code]);
                echo Html::activeHiddenInput($model, '[' . $index . ']union_code', ['value' => $model->union_code]);
                echo Html::activeHiddenInput($model, '[' . $index . ']dcs_code', ['value' => $model->dcs_code]);
                return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name');
            }, 'filter' => false],
        ['header' => 'Member Code', 'attribute' => 'member_code', 'filter' => false],
        ['header' => Yii::t('app', 'Member Name'), 'attribute' => 'member_code', 'value' => function ($model, $key, $index) use ($form) {
                return Yii::$app->general->getforeignkey($model->memberCode, 'member_name');
            }, 'filter' => false],
        ['label' => 'Date', 'attribute' => 'date_time_of_collection',
            'filterType' => GridView::FILTER_DATE,
            'filterWidgetOptions' => [
                'pluginOptions' => ['format' => 'dd-mm-yyyy',
                    'autoclose' => true]
            ],
            'value' => function ($model, $key, $index) use ($form) {
                echo Html::activeHiddenInput($model, '[' . $index . ']date_time_of_collection', ['value' => Yii::$app->controls->view_date($model->date_time_of_collection, 'php:Y-m-d')]);
                return Yii::$app->controls->view_date($model->date_time_of_collection);
            }, 'filter' => false],
        ['attribute' => 'shift_code', 'value' => function ($model, $key, $index) use ($form) {
                echo Html::activeHiddenInput($model, '[' . $index . ']shift_code', ['value' => $model->shift_code]);
                return Yii::$app->general->getforeignkey($model->shiftCode, 'shift');
            }, 'filter' => false],
        ['attribute' => 'sample_no', 'filter' => false],
        ['attribute' => 'milk_type_code',
            'format' => 'raw',
            'value' => function ($model, $key, $index) use ($form, $detailModel) {
                return '<span class=\'rtpl_validate milk_type\'>' . Yii::$app->dropdown->dropdown('milk_type_code', $model, $form, '', FALSE, FALSE, '[' . $index . ']milk_type_code', FALSE, TRUE, $model->milk_type_code) . '</span>';
            },
        ],
//        ['attribute' => 'milkqtype', 'filter' => false],
        ['attribute' => 'qty',
            'format' => 'raw',
            'value' => function ($model, $key, $index) use ($form) {
                return '<span class=\'qty_change\'>' . $form->field($model, '[' . $index . ']qty')->textInput(['value' => $model->qty, 'class' => 'form-control number-validate',])->label(FALSE) . '</span>';
            },
        ],
        ['attribute' => 'fat',
            'format' => 'raw',
            'value' => function ($model, $key, $index) use ($form) {
                return '<span class=\'rtpl_validate fat_change\'>' . $form->field($model, '[' . $index . ']fat')->textInput(['value' => $model->fat, 'class' => 'form-control number-validate',])->label(FALSE) . '</span>';
            },
        ],
        ['attribute' => 'snf',
            'format' => 'raw',
            'value' => function ($model, $key, $index) use ($form) {
                return '<span class=\'rtpl_validate\'>' . $form->field($model, '[' . $index . ']snf')->textInput(['value' => $model->snf, 'class' => 'form-control number-validate',])->label(FALSE) . '</span>';
            },
        ],
        ['attribute' => 'clr',
            'format' => 'raw',
            'value' => function ($model, $key, $index) use ($form) {
                return $form->field($model, '[' . $index . ']clr')->textInput(['value' => $model->clr, 'class' => 'form-control number-validate', 'readonly' => TRUE])->label(FALSE);
            },
        ],
        ['attribute' => 'rtpl',
            'format' => 'raw',
            'value' => function ($model, $key, $index) use ($form) {
                echo Html::activeHiddenInput($model, '[' . $index . ']purchase_rate_code', ['value' => $model->purchase_rate_code]);
                return '<span class=\'qty_change\'>' . $form->field($model, '[' . $index . ']rtpl')->textInput(['class' => 'form-control', 'readonly' => TRUE])->label(FALSE);
            },
        ],
        ['attribute' => 'amount',
            'format' => 'raw',
            'value' => function ($model, $key, $index) use ($form) {
                return $form->field($model, '[' . $index . ']amount')->textInput(['class' => 'form-control', 'readonly' => TRUE])->label(FALSE);
            },
        ],
    ];

    $grid_option = [
        'id' => 'update-milk-collection-list-data',
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
        $('#update-milk-collection').submit();
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
        $('#tblmilkcollection-'+tr_key+'-rtpl').val('');
        $('#tblmilkcollection-'+tr_key+'-purchase_rate_code').val('');
        $('#tblmilkcollection-'+tr_key+'-amount').val('');
     
         var dcs = $('#tblmilkcollection-'+tr_key+'-dcs_code').val();
         var milk_type = $('#tblmilkcollection-'+tr_key+'-milk_type_code').val();
         var dt_date = $('#tblmilkcollection-'+tr_key+'-date_time_of_collection').val();
         var shift = $('#tblmilkcollection-'+tr_key+'-shift_code').val();
         var fat = $('#tblmilkcollection-'+tr_key+'-fat').val();
         var snf = $('#tblmilkcollection-'+tr_key+'-snf').val();
        var milk_quality_type = 1;
        if(dcs != '' && milk_type != '' && dt_date!= '' && shift != '' && fat != '' && snf != ''){
            $.ajax({
                type: 'post',
                url:'" . Url::to(['validate-rtpl']) . "',
                data: {'dcs_code':dcs,'milk_type':milk_type,'milk_quality_type':milk_quality_type,'dt_date':dt_date,'shift_code':shift,'fat':fat,'snf':snf},
                success: function(data) {   
                      var obj = $.parseJSON(data);
                      if (obj.status == 'success')
                      {
                            $('#tblmilkcollection-'+tr_key+'-rtpl').val(obj.data.list.rtpl);
                            $('#tblmilkcollection-'+tr_key+'-purchase_rate_code').val(obj.data.list.purchase_rate_code);
                            $('#tblmilkcollection-'+tr_key+'-rtpl').trigger('change');
                        }else{
                            bootbox.alert('<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>RTPL Not Available</span></div></div>');
                            $('#tblmilkcollection-'+tr_key+'-rtpl').val('');
                            $('#tblmilkcollection-'+tr_key+'-purchase_rate_code').val('');
                      }
                },
                error:function(data){

                }
            });
        }else{
            $('#tblmilkcollection-'+tr_key+'-rtpl').val('');
            $('#tblmilkcollection-'+tr_key+'-purchase_rate_code').val('');
        }
    }

   
    function amount(tr_key){
        var amount = 0;
        var rtpl = parseFloat($('#tblmilkcollection-'+tr_key+'-rtpl').val());
        var qty = parseFloat($('#tblmilkcollection-'+tr_key+'-qty').val());
        if(rtpl == '' || isNaN(rtpl)){
            rtpl = 0;
        }
        if(qty == '' || isNaN(qty)){
            qty = 0;
        }
        amount = rtpl * qty;
        $('#tblmilkcollection-'+tr_key+'-amount').val(amount.toFixed(2));
    }
    
     function calculateClr(tr_key){
        var union = $('#tblmilkcollection-'+tr_key+'-union_code').val();
        var fat = $('#tblmilkcollection-'+tr_key+'-fat').val();
        var snf = $('#tblmilkcollection-'+tr_key+'-snf').val();
            if(fat !='' && snf !=''){
                $.ajax({
                    type: 'post',
                    url:'" . Url::to(['calculate-clr']) . "',
                    data: {'union_code':union,'fat':fat,'snf':snf},
                    success: function(data) {                                        
                        var obj = $.parseJSON(data);
                        if (obj.status == 'success')
                        {
                            $('#tblmilkcollection-'+tr_key+'-clr').val(obj.data.toFixed(2));
                        }
                    },
                    error:function(data){

                    }
                });
            }
    };
     
    $(document).on('change','span.fat_change input', function() { 
        var tr_key = $(this).closest('tr').attr('data-key');
         checkFatRange(tr_key);
    });

    $(document).on('change','span.milk_type select', function() { 
        var tr_key = $(this).closest('tr').attr('data-key');
        checkFatRange(tr_key);
    });
    
     function checkFatRange(tr_key){
        var union = $('#tblmilkcollection-'+tr_key+'-union_code').val();
        var fat = $('#tblmilkcollection-'+tr_key+'-fat').val();
        var milk_type = $('#tblmilkcollection-'+tr_key+'-milk_type_code').val();
            if(union !='' && fat !='' && milk_type !=''){
                $.ajax({
                    type: 'post',
                    url:'" . Url::to(['check-fat-range']) . "',
                    data: {'union_code':union,'fat':fat,'milk_type':milk_type},
                    success: function(data) {                                        
                        var obj = $.parseJSON(data);
                        if (obj.status == 'success')
                        {
                            bootbox.alert('<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>'+obj.msg+'</span></div></div>');
                            $('#tblmilkcollection-'+tr_key+'-milk_type_code').val(obj.data);
                            $('#tblmilkcollection-'+tr_key+'-milk_type_code').trigger('change');
                        }
                    },
                    error:function(data){

                    }
                });
            }
    };

      ";
$this->registerJs($script, View::POS_END, 'update-milk-collection');

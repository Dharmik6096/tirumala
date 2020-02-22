<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\View;

$this->title = Yii::t('app', 'BMC Purchase Rate - Formula based');
?>
<?php
$form = ActiveForm::begin(['id' => 'manual_form',
            'validateOnBlur' => FALSE,
            'validateOnEnter' => TRUE,
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
            'fieldConfig' => [
        ]]);
?>

<div class="panel-subheading">
    <h5 class="panel-subtitle"><?php echo Yii::t('app', $this->title); ?></h5>
    <?php echo $form->errorSummary($purchaseBasedModel[0]); ?>
    <div class="row">
        <div class="col-sm-3 change">
            <div class="form-group">
                <?= Yii::$app->dropdown->dropdown('rate_type_code', $purchaseBasedModel[0], $form, '', 'Rate Type', false, '[0]rate_type'); ?>
            </div>
        </div>
        <div class="col-sm-3 change">
            <div class="form-group">
                <?= Yii::$app->dropdown->dropdown('milk_quality_type_code', $purchaseBasedModel[0], $form, '', 'Milk Quality Type', false, '[0]milk_quality_type_code'); ?>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group">
                <?= Yii::$app->dropdown->dropdown('milk_type_code', $purchaseBasedModel[0], $form, '', 'Milk Type', false, '[0]milk_type_code'); ?>
            </div>
        </div>
        <div id="range">
            <?php
            if (Yii::$app->request->post() && isset(Yii::$app->request->post()['TblDcsPurchaseRateBased'])) {
                foreach ($purchaseBasedModel as $key => $model) {

                    $names = explode('+', $model->rateType->rate_type);
                    ?>
                    <div class="col-sm-3">
                        <?= $form->field($model, '[' . $key . ']start_range')->textInput(['class' => 'form-control number-validate'])->label($names[$key] . ' Start'); ?>
                    </div>
                    <div class="col-sm-3">
                        <?= $form->field($model, '[' . $key . ']end_range')->textInput(['class' => 'form-control number-validate'])->label($names[$key] . ' End'); ?>
                    </div>
                    <?= Html::activeHiddenInput($model, '[' . $key . ']quality_param_code'); ?>
                    <?php
                }
                foreach ($purchaseBasedModel as $key => $model) {
                    $names = explode('+', $model->rateType->rate_type);
                    ?>
                    <div class="col-sm-3">
                        <?= $form->field($model, '[' . $key . ']kg_rate')->textInput(['class' => 'form-control number-validate'])->label('kg' . $names[$key]); ?>
                    </div>

                    <?php
                }
            }
            // exit;
            ?>
        </div>
        <div class="col-sm-6">
            <?= $form->field($purchaseBasedModel[0], '[0]formula_code')->dropDownList([]) ?>
        </div>
        <?= Html::hiddenInput('purchase_rate', '', ['id' => 'purchase_rate']); ?>
        <?= Html::hiddenInput('quality_param', $quality_param, ['id' => 'quality_param']); ?>
        <div class="clearfix"></div>   
        <div class="col-sm-12">
            <div class="form-group">
                <?= Yii::$app->controls->save(Yii::t('app', 'Save'), $purchaseBasedModel[0]); ?>
            </div>
        </div>
    </div>
</div>

<?php ActiveForm::end(); ?>
<div class="ex-grid">
    <?= $this->render('_manual_rate_grid', ['dataProvider' => $purchaseBasedModel[0]->search(Yii::$app->request->queryParams), 'searchModel' => $purchaseBasedModel[0]]) ?>
</div>

<?php
$script = "

    var records;
    var jsonEncoded = '" . $jsonEncoded . "';
    if(jsonEncoded==''){
        records = localStorage.getItem('purchaseRate');
    }else{
        records = jsonEncoded;
    }

    $('#purchase_rate').val(records);

    $('#tbldcspurchaseratebased-0-rate_type').on('change',function(e){
        $('#range').empty();
        $('#tbldcspurchaseratebased-0-formula').val('');
        $('#tbldcspurchaseratebased-0-formula_code').val('');
        $('#tbldcspurchaseratebased-0-milk_type_code').val('');
        var rateType = $('#tbldcspurchaseratebased-0-rate_type :selected').text();     
        var field_before = '<div class=\"col-sm-3\"><div class=\"form-group\">';
        var field_after = '<div class=\"help-block\"></div></div></div>';
        var quality_param=$.parseJSON($('#quality_param').val());

        $.each(rateType.split('+'), function(index, item)
        {
            var param=0;
            $.each(quality_param, function(p,p_value) {
                if(item==p_value){
                    return param=p;
                }                
             });          
           $('#range').append(field_before + '<label class=\"control-label\">' + item + ' Start*</label><input type=\"text\" name=\"TblDcsPurchaseRateBased['+index+'][start_range]\" class=\"form-control number-validate\">' + field_after);
           $('#range').append(field_before + '<label class=\"control-label\">' + item + ' End*</label><input type=\"text\" name=\"TblDcsPurchaseRateBased['+index+'][end_range]\" class=\"form-control number-validate\">' + field_after);
           
           $('#range').append('<input type=\"hidden\" value=\"'+param+'\" name=\"TblDcsPurchaseRateBased['+index+'][quality_param_code]\" >');
        });
        $.each(rateType.split('+'), function(index, item)
        {             
           $('#range').append(field_before + '<label class=\"control-label\">Kg' + item + '*</label><input type=\"text\" name=\"TblDcsPurchaseRateBased['+index+'][kg_rate]\" class=\"form-control number-validate\">' + field_after);           
        });
    });

 $('#tbldcspurchaseratebased-0-milk_type_code').on('change',function(e){
    var data = $('#purchase_rate').val();
    var obj = $.parseJSON(data);
    var milkType = this.value;
    var rateType = $('#tbldcspurchaseratebased-0-rate_type').val();
    var union_code= obj.union_code;
    var wefDate = obj.wef_date;

    $.ajax({
        type: 'post',
        url: '" . yii\helpers\Url::to(['formula-master/get-formula']) . "',
        data: 'wefDate='+wefDate+'&milkType='+milkType+'&rateType='+rateType+'&union_code='+union_code+'&dropdown=dropdown',
        success: function(data) {
            var obj1 = $.parseJSON(data);
            $('#tbldcspurchaseratebased-0-formula_code').empty();
            if (obj1.status == 'success')
            {
                $.each( obj1.data, function( key, value ) {
                    $('select#tbldcspurchaseratebased-0-formula_code').append('<option value='+key+'>'+value+'</option>');
                });
            }
        },
        error:function(data){
            //alert('Your data has not been submitted..Please try again');
        }
    });
});
";
$this->registerJs($script, View::POS_END, 'milk-type-code');
?>
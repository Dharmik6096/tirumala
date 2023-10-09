<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\View;

$this->title = Yii::t('app', 'Purchase Rate - Automatic');
?>
<?php
$form = ActiveForm::begin(['id' => 'manual_form',
            'validateOnBlur' => true,
            
            'validateOnChange' => true,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
            'fieldConfig' => [
        ]]);
?>
<div class="row padding_10_0 theme-box view-subtitle">
    <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 margin-bottom-10 clearfix">
        <h4 class="theme-box-heading"><?= Yii::t('app', 'Purchase Rate - Automatic') ?></h4>
    </div>
    <div class="col-sm-2 change">
        <?= Yii::$app->dropdown->dropdown('rate_type_code', $purchaseBasedModel[0], $form, '', 'Rate Type', false, '[0]rate_type_code'); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->dropdown('milk_type_code', $purchaseBasedModel[0], $form, '', 'Milk Type', false, '[0]milk_type_code'); ?>
    </div>
    <div id="range">
        <?php
        if (Yii::$app->request->post()) {
            foreach ($purchaseBasedModel as $key => $model) {

                $names = explode('+', $model->rateType->rate_type);
                ?>
                <div class="col-sm-2">
                    <?= $form->field($model, '[' . $key . ']start_range')->textInput(['class' => 'form-control number-validate'])->label($names[$key] . ' Start'); ?>
                </div>
                <div class="col-sm-2">
                    <?= $form->field($model, '[' . $key . ']end_range')->textInput(['class' => 'form-control number-validate'])->label($names[$key] . ' End'); ?>
                </div>
                <?= Html::activeHiddenInput($model, '[' . $key . ']quality_param_code'); ?>
                <?php
            }
            foreach ($purchaseBasedModel as $key => $model) {
                $names = explode('+', $model->rateType->rate_type);
                ?>
                <div class="col-sm-2">
                    <?= $form->field($model, '[' . $key . ']kg_rate')->textInput(['class' => 'form-control number-validate'])->label($names[$key] . ' KG'); ?>
                </div>

                <?php
            }
        }
        // exit;
        ?>
    </div>
    <div class="col-sm-6">
        <?= $form->field($purchaseBasedModel[0], '[0]formula')->textInput(['readOnly' => true]) ?>
    </div>
    <?= Html::activeHiddenInput($purchaseBasedModel[0], '[0]formula_code'); ?>
    <?= Html::hiddenInput('purchase_rate', '', ['id' => 'purchase_rate']); ?>
    <?= Html::hiddenInput('quality_param', $quality_param, ['id' => 'quality_param']); ?>
    <div class="clearfix"></div>   
    <div class="col-sm-12">
        <div class="form-group">
            <?= Yii::$app->controls->save(Yii::t('app', 'Save'), $purchaseBasedModel[0]); ?>
        </div>
    </div>
</div>
<hr class="hr10">


<?php ActiveForm::end(); ?>
<div class="row">
    <div class="form-grid">
        <?= $this->render('_manual_rate_grid', ['dataProvider' => $purchaseBasedModel[0]->search(Yii::$app->request->queryParams), 'searchModel' => $purchaseBasedModel[0]]) ?>
    </div>
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

    $('#tblpurchaseratebased-0-rate_type_code').on('change',function(e){
        $('#range').empty();
        $('#tblpurchaseratebased-0-formula').val('');
        $('#tblpurchaseratebased-0-formula_code').val('');
        $('#tblpurchaseratebased-0-milk_type_code').val('');
        var rateType = $('#tblpurchaseratebased-0-rate_type_code :selected').text();     
        var field_before = '<div class=\"col-sm-2\"><div class=\"form-group\">';
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
           $('#range').append(field_before + '<label class=\"control-label\">' + item + ' Start*</label><input type=\"text\" name=\"TblTankerRateBased['+index+'][start_range]\" class=\"form-control number-validate\">' + field_after);
           $('#range').append(field_before + '<label class=\"control-label\">' + item + ' End*</label><input type=\"text\" name=\"TblTankerRateBased['+index+'][end_range]\" class=\"form-control number-validate\">' + field_after);
           
           $('#range').append('<input type=\"hidden\" value=\"'+param+'\" name=\"TblTankerRateBased['+index+'][quality_param_code]\" >');
        });
        $.each(rateType.split('+'), function(index, item)
        {             
           $('#range').append(field_before + '<label class=\"control-label\">Kg' + item + '*</label><input type=\"text\" name=\"TblTankerRateBased['+index+'][kg_rate]\" class=\"form-control number-validate\">' + field_after);           
        });
    });

 $('#tblpurchaseratebased-0-milk_type_code').on('change',function(e){
    var data = $('#purchase_rate').val();
    var obj = $.parseJSON(data);
    var milkType = this.value;
    var rateType = $('#tblpurchaseratebased-0-rate_type_code').val();
    var union_code= obj.union_code;
    var wefDate = obj.wef_date;

    $.ajax({
        type: 'post',
        url: '" . yii\helpers\Url::to(['formula-master/get-formula']) . "',
        data: 'wefDate='+wefDate+'&milkType='+milkType+'&rateType='+rateType+'&union_code='+union_code,
        success: function(data) {
            var obj1 = $.parseJSON(data);
            $('#tblpurchaseratebased-0-formula').empty();
            $('#tblpurchaseratebased-0-formula_code').empty();
            if (obj1.status == 'success')
            {
                $('#tblpurchaseratebased-0-formula_code').val(obj1.data.code);
                $('#tblpurchaseratebased-0-formula').val(obj1.data.formula);
            }else{
                $('#tblpurchaseratebased-0-formula_code').val('');
                $('#tblpurchaseratebased-0-formula').val('');
            }
        },
        error:function(data){
            //alert('Your data has not been submitted..Please try again');
        }
    });
});
";
$this->registerJs($script, View::POS_END, 'auto-rate-chart');
?>
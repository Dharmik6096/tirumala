<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\jui\DatePicker;
use yii\web\View;
/* @var $this yii\web\View */
/* @var $model app\modules\dcsaccounting\models\TblFinancialYear */
/* @var $form yii\widgets\ActiveForm */

$title = Yii::$app->label->title($type, 'Financial Year');
$button = Yii::$app->label->button($type);
$this->title = Yii::t('app', $title);
$minDate = ($model->isNewRecord)?'':date('d-m-Y', strtotime($model->starting_date));
?>

    <?php
    $form = ActiveForm::begin(['options' => [
                    'field-class' => 'form-group col-sm-3'
                ], 'validateOnBlur' => FALSE,
                'validateOnEnter' => TRUE,
                'validateOnChange' => FALSE,
                'enableClientValidation' => true,
                'validateOnSubmit' => true,
                'fieldConfig' => [
                //'labelOptions' => [ 'class' => false],
            ]]);
    ?>

    <div class="panel-body">
        <div class="panel-subheading">
            <h5 class="panel-subtitle"><?php echo Yii::t('app', $title); ?></h5>

            <?php echo $form->errorSummary($model); ?>
            <div class="row">
                
                 <?=
            $form->field($model, 'code', ['options' => ['class' => 'form-group col-sm-3']])->widget(\yii\widgets\MaskedInput::className(), [
                'mask' => '9999-99',]);
            ?>
                
            <?php //$form->field($model, 'starting_date', [ 'options' => ['class' => 'form-group col-sm-3']])->textInput(['maxlength' => true,'readonly'=>true]) ?>    
                
            <?php //$form->field($model, 'ending_date', [ 'options' => ['class' => 'form-group col-sm-3']])->textInput(['maxlength' => true,'readonly'=>true]) ?>    
              
                
                <?= $form->field($model, 'starting_date', ['options' => ['class' => 'form-group col-sm-3']])->widget(DatePicker::className(), [
                       'model' => $model,
                       'attribute' => 'from_date',
                       'dateFormat' => 'dd-MM-yyyy',
                       'clientOptions' => [ 
                           'yearRange' => '2016:2999',
                           'changeYear' => true,
                           'onSelect' => new \yii\web\JsExpression('function(dateText, inst) { $("#tblfinancialyear-ending_date").datepicker( "option", "minDate", dateText ); }'),
                       ],
                       'options' => ['readonly' => true,'class' => 'form-control','placeholder'=>'DD-MM-YYYY'
                                       ]
                   ]);
                ?>
                <?= $form->field($model, 'ending_date', ['options' => ['class' => 'form-group col-sm-3']])->widget(DatePicker::className(), [
                       'model' => $model,
                       'attribute' => 'ending_date',
                       'dateFormat' => 'dd-MM-yyyy',
                       'clientOptions' => ['yearRange' => '2016:2999','changeYear' => true,'minDate'=>$minDate,'value' => date('Y-m-d')],
                       'options' => ['readonly' => true, 'class' => 'form-control']
                   ]);
                ?>
                
               
               
                <div class="clearfix"></div>
                <?= Yii::$app->controls->active($model, $form); ?>
            </div>

        </div>
    </div>

    <div class="panel-footer shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
        <?= Yii::$app->controls->save($button, $model); ?>
        <?= Yii::$app->controls->reset(); ?>
        <?= Yii::$app->controls->cancel($model); ?>
    </div>

    <?php ActiveForm::end(); ?>
<?php
$script = "
//            $('#tblfinancialyear-code').on('blur',function(e){
//                   var value = $(this).val();
//                   var ary = value.split('-');
//                   if(ary){
//                        var res = ary[0].substring(0, 2);
//                        
//                        if(ary[1]=='00'){
//                            var rs = (+ary[0])+(+1);
//                            res = rs.toString().substring(0, 2);
//                            res = res+ary[1];
//                        }else{
//                            res = (+ary[0]+(+ary[1]));
//                            var first = res.toString().substring(0, 2);
//                            var last = ary[0].substring(2, 4);
//                            //res = ()
//                            alert(first);
//                            alert(last);
//                        }
//                        $('#tblfinancialyear-starting_date').val('01-04-'+ary[0]);
//                        $('#tblfinancialyear-ending_date').val('31-03-'+res);
//                   }
//            });
";
$this->registerJs($script, View::POS_END, 'tax-calculate');
?>


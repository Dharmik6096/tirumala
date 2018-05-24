<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\web\View;
/* @var $this yii\web\View */
/* @var $model app\modules\organisation\models\TblCollectionPoint */
/* @var $form yii\widgets\ActiveForm */
$title = Yii::t('app', 'Add Vehicle KM Information');
$button = Yii::$app->label->button($type);
//$milkType = $model->getMilkTypes();
$this->title = Yii::t('app', $title);
?>

        <div class="">
            <?php
            $bmc = !empty(Yii::$app->request->queryParams['TblVehicleMasterSearch']) ? (!empty(Yii::$app->request->queryParams['TblVehicleMasterSearch']['bmc_code']) ? Yii::$app->request->queryParams['TblVehicleMasterSearch']['bmc_code'] : '') : '';
            echo $this->render('_vehicle_search', ['model' => $searchModel]);
            ?>
        </div>
        <div>
            <?php
            $form = ActiveForm::begin([
                        'validateOnBlur' => false,
                        'validateOnEnter' => TRUE,
                        'validateOnChange' => FALSE,
                        'enableClientValidation' => true,
                        'validateOnSubmit' => true,
                        'fieldConfig' => [
                    ]]);
            ?>



        <!--<h5 class="panel-subtitle"><?php echo Yii::t('app', $title); ?></h5>-->

            <?php //echo $form->errorSummary($modeltransaction); ?>

            <table  class="table table-bordered table-striped table-main table-language">
                <thead>
                    <tr>
                        <th><?= Yii::t('app', 'Vehicle') ?></th>
                        <th><?= Yii::t('app', 'Route') ?></th>
                        <th><?= Yii::t('app', 'Morning Km') ?></th>
                        <th><?= Yii::t('app', 'Evening Km') ?></th>
                        <th><?= Yii::t('app', 'Extra Km') ?></th>
                        <th><?= Yii::t('app', 'Total Km') ?></th>   
                    </tr> 
                </thead>
                <?php
                $transporter = !empty(Yii::$app->request->queryParams['TblVehicleMasterSearch']) ? (!empty(Yii::$app->request->queryParams['TblVehicleMasterSearch']['transporter_code']) ? Yii::$app->request->queryParams['TblVehicleMasterSearch']['transporter_code'] : '') : '';
                if($transporter != ''){
                $i=0;
                foreach ($dataProvider->models as $row) {
                    $bmc_code = !empty(Yii::$app->request->queryParams['TblVehicleMasterSearch']) ? (!empty(Yii::$app->request->queryParams['TblVehicleMasterSearch']['bmc_code']) ? Yii::$app->request->queryParams['TblVehicleMasterSearch']['bmc_code'] : '') : ''; 
                    ?>
                <tr>
                    <td><?= $row->parsing_no.'/'.$row->vehicleType->vehicle_type_name ?></td>
                    <td><?= Yii::$app->dropdown->bmcroutecode($model[$i], $form,'['.$i.']route_code',false,false,$bmc_code);  ?></td>
                    <td><?= $form->field($model[$i], '['.$i.']morning_kms')->textInput(['class'=>'form-control morning_km'])->label(false) ?></td>
                    <td><?= $form->field($model[$i], '['.$i.']evening_kms')->textInput(['class'=>'form-control evening_km'])->label(false) ?></td>
                    <td><?= $form->field($model[$i], '['.$i.']extra_kms')->textInput(['class'=>'form-control extra_km'])->label(false) ?></td>
                    <td><?= $form->field($model[$i], '['.$i.']total_kms')->textInput(['readonly'=>true,'class'=>'form-control total_km'])->label(false) ?></td>
                    <?= Html::activeHiddenInput($model[$i], '[' . $i . ']vehicle_code', ['value' => $row->vehicle_code]) ?>
                    <?= Html::activeHiddenInput($model[$i], '[' . $i . ']transporter_code', ['value' => $row->transporter_code]) ?>
                    <?= Html::activeHiddenInput($model[$i], '[' . $i . ']wef_date', ['value' => !empty(Yii::$app->request->queryParams['TblVehicleMasterSearch']) ? (!empty(Yii::$app->request->queryParams['TblVehicleMasterSearch']['wef_date']) ? Yii::$app->request->queryParams['TblVehicleMasterSearch']['wef_date'] : '') : '']) ?>
                    <?= Html::activeHiddenInput($model[$i], '[' . $i . ']shift_code', ['value' => !empty(Yii::$app->request->queryParams['TblVehicleMasterSearch']) ? (!empty(Yii::$app->request->queryParams['TblVehicleMasterSearch']['shift_code']) ? Yii::$app->request->queryParams['TblVehicleMasterSearch']['shift_code'] : '') : '']) ?>
                    <?= Html::activeHiddenInput($model[$i], '[' . $i . ']bmc_code', ['value' => $bmc_code]) ?>
                </tr>
                    <?php $i++; }
                }
                ?>
            </table>
            <?php  if($transporter != ''){ ?>
            <div class="shortcut-main" shortcut="true" style='margin-top:20px;' display_shortcut="false" hilight_shortcut="false">

                <?= Yii::$app->controls->save($button, $model); ?>
                <?= Yii::$app->controls->reset(); ?>
                <?= Yii::$app->controls->cancel($model); ?>
            </div>
                <?php } ?>
            <?php ActiveForm::end(); ?>
        </div>
        

<?php

$script = "
    $('input').change(function(){
        var morning_km = $(this).val();
        var parent = $(this).closest('tr');
        var morning_km = parseFloat(parent.find('.morning_km').val());
        var evening_km = parseFloat(parent.find('.evening_km').val());
        var extra_km = parseFloat(parent.find('.extra_km').val());
        if(isNaN(morning_km)){
            morning_km = 0;
        }
        if(isNaN(evening_km)){
            evening_km = 0;
        }
        if(isNaN(extra_km)){
            extra_km = 0;
        }
        total = morning_km + evening_km + extra_km;
        parent.find('.total_km').val(total);
    });";
$this->registerJs($script, View::POS_END, 'panel-before-hide');
?>
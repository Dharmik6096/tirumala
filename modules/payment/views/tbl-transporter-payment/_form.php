<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\web\View;
/* @var $this yii\web\View */
/* @var $model app\modules\organisation\models\TblCollectionPoint */
/* @var $form yii\widgets\ActiveForm */
$title = Yii::$app->label->title($type, 'Vehicle KM Information');
$button = Yii::$app->label->button($type);
//$milkType = $model->getMilkTypes();
$this->title = 'Transporter Payment : Step 1';
?>
            <?php
            $bmc = !empty(Yii::$app->request->queryParams['TblVehicleMasterSearch']) ? (!empty(Yii::$app->request->queryParams['TblVehicleMasterSearch']['bmc_code']) ? Yii::$app->request->queryParams['TblVehicleMasterSearch']['bmc_code'] : '') : '';
            echo $this->render('_vehicle_search', ['model' => $model]);
            ?>

<?php
 if(!empty($dataProvider)){ 
$form = ActiveForm::begin([
            'action' => ['transporter-payment'],
            //'method' => 'GET',
            'validateOnBlur' => false,
            
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
]);
echo $form->errorSummary($model);
?>
        <div id="list-title">
            <h4>Transporter List</h4>
            <div class="form-group">
                <div class="checkbox app-check-all">
                    <label class="route-text">
                        <?= Html::checkbox('checkall', false, ['id' => 'checkAll', 'class' => 'route-checkbox']) ?>
                        <label for="checkAll">Select All Transporters</label>
                    </label>
                </div>
            </div>
        </div>
        <div class="row" id="transporter-list">
            <?php foreach($dataProvider as $data){ ?>
            <div class="col-sm-4 dcs-checklist checklist" id="">
                <div class="checkbox">
                    <input type="checkbox" class="route-checkbox" name="TblTransporterPayment[transporter_code][]" value=<?= $data['Transporter Code'] ?> id="<?= $data['Transporter Code'] ?>" >
                    <label class="route-text" for="<?= $data['Transporter Code'] ?>"><?=$data['Transporter Name'] ?></label>
                </div>
            </div>
            
            <?php } ?>
            <?= Html::activeHiddenInput($model, 'bmc_code', ['value' => !empty(Yii::$app->request->queryParams['TblTransporterPayment']) ? (!empty(Yii::$app->request->queryParams['TblTransporterPayment']['bmc_code']) ? Yii::$app->request->queryParams['TblTransporterPayment']['bmc_code'] : '') : '']) ?>
            <?= Html::activeHiddenInput($model, 'from_date', ['value' => !empty(Yii::$app->request->queryParams['TblTransporterPayment']) ? (!empty(Yii::$app->request->queryParams['TblTransporterPayment']['from_date']) ? Yii::$app->request->queryParams['TblTransporterPayment']['from_date'] : '') : '']) ?>
            <?= Html::activeHiddenInput($model, 'to_date', ['value' => !empty(Yii::$app->request->queryParams['TblTransporterPayment']) ? (!empty(Yii::$app->request->queryParams['TblTransporterPayment']['to_date']) ? Yii::$app->request->queryParams['TblTransporterPayment']['to_date'] : '') : '']) ?>
            <?= Html::activeHiddenInput($model, 'union_code', ['value' => !empty(Yii::$app->request->queryParams['TblTransporterPayment']) ? (!empty(Yii::$app->request->queryParams['TblTransporterPayment']['union_code']) ? Yii::$app->request->queryParams['TblTransporterPayment']['union_code'] : '') : '']) ?>
        </div>
        <div class="row">
            <div class="col-sm-12 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
                <div class="form-group">
                    <?= Yii::$app->controls->save('Next', $model); ?>                
                </div>
            </div>
        </div>
<?php ActiveForm::end(); ?>
<?php } ?>    


<?php
$script = "
 $('#checkAll').click(function () {    
        $('#transporter-list').find('.route-checkbox:enabled').prop('checked', this.checked);    
    });
$('#transporter-list').find('.route-checkbox:enabled').prop('checked', 'checked');    
$('#checkAll').prop('checked', 'checked');    

";
$this->registerJs($script, View::POS_END, 'bank-select');
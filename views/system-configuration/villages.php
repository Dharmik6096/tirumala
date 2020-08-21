<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model app\models\SystemConfiguration */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="system-configuration-form">
    <h5 class="panel-subtitle"></h5>

    <?php $form = ActiveForm::begin(['validateOnBlur' => false,
                
                'validateOnChange' => FALSE,
                'enableClientValidation' => true,
                'validateOnSubmit' => true,]); ?>
    
    <div class="table-responsive" style="width: 700px;margin: 0 auto;">
         <?php echo $form->errorSummary($model); ?>
        <table class="table table-bordered table-striped tbl-portal-conf">
            <thead>
            <th colspan="3">
            <h4 class="text-center">National Portal Config for geographical masters</h4>
            </th>
            </thead>
            <tr>
                <td></td>
                <td>From Value</td>
                <td>To Value</td>
            </tr>
           
            <?php if($unions){
                foreach ($unions as $key=>$row){
                $village_from='';$village_to='';$id='';
                if(isset($row->systemConfig)){
                    $id = $row->systemConfig->id;
                    $village_from = $row->systemConfig->from_value;
                    $village_to = $row->systemConfig->to_value;
                }
                ?>
                     <tr>
                         <td >
                            <?= 'Custom Range for Village Code'."</br><b>".$row->union_name ."</b>"?>
                        </td>
                        <?php echo Html::activeHiddenInput($model, '['.$key.']id', ['value' => $id]); ?>
                        <td><?= $form->field($model, '['.$key.']village_from', [ 'options' => ['class' => 'form-group col-sm-12',]])->textInput(['value'=>$village_from])->label(false) ?></td>
                        <td><?= $form->field($model, '['.$key.']village_to', [ 'options' => ['class' => 'form-group col-sm-12',]])->textInput(['value'=>$village_to])->label(false) ?></td>
                        <?php echo Html::activeHiddenInput($model,'['.$key.']organization_id',['value'=>$row->union_code]) ?>
                    </tr>
            <?php } } ?>

            <tr>
                <td colspan="3">
                    <?= Html::submitButton('Save', ['class' => 'btn btn-success apply-shortcut pull-right', 'shortcut_key' => 'ctrl+alt+s', 'button' => 'save']) ?>
                </td>
            </tr>
        </table>
    </div>
</div>
<?php ActiveForm::end(); ?>

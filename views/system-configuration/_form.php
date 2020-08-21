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
            <?php
//         print_r($records);
//         exit;
            foreach ($records as $row) {

                //echo $model->module_name;
                switch ($row->id) {
                    case '1' :
                        $label = 'Custom Range for State Code';
                        break;
                    case '2' :
                        $label = 'Custom Range for District Code';
                        break;
                    case '3' :
                        $label = 'Custom Range for Sub - District Code';
                        break;
                }
                ?>
                <tr>
                    <td>
                        <?= $label ?>
                    </td>
                    <td>
                        <?php echo Html::activeHiddenInput($row, 'id[]', ['value' => $row->id]); ?>
                        <?= $form->field($row, 'from_value[]', [ 'options' => ['class' => 'form-group col-sm-12',]])->textInput(['value' => $row->from_value])->label(false) ?></td>
                    <td>
                        <?= $form->field($row, 'to_value[]', [ 'options' => ['class' => 'form-group col-sm-12',]])->textInput(['value' => $row->to_value])->label(false) ?></td>
                </tr>
            <?php } ?>

            <?php if($unions){foreach ($unions as $row){
                $village_from='';$village_to='';$id='';
                if(isset($row->systemConfig)){
                    $id = $row->systemConfig->id;
                    $village_from = $row->systemConfig->from_value;
                    $village_to = $row->systemConfig->to_value;
                }
                //echo $village_from;
                ?>
                     <tr>
                         <td >
                            <?= 'Custom Range for Village Code'."</br><b>".$row->union_name ."</b>"?>
                        </td>
                        <?php echo Html::activeHiddenInput($model, 'union_id[]', ['value' => $id]); ?>
                        <td><?= $form->field($model, 'village_from[]', [ 'options' => ['class' => 'form-group col-sm-12',]])->textInput(['value'=>$village_from])->label(false) ?></td>
                        <td><?= $form->field($model, 'village_to[]', [ 'options' => ['class' => 'form-group col-sm-12',]])->textInput(['value'=>$village_to])->label(false) ?></td>
                        <?php echo Html::activeHiddenInput($model,'organization_id[]',['value'=>$row->union_code]) ?>
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

</div>
<?php
$script = "
    $('#systemconfiguration-organization_id').on('change',function(){
            var id = $('#systemconfiguration-organization_id').val();
            $.ajax({
                        type: 'post',
                        url: '" . Yii::$app->request->baseUrl . "/index.php?r=system-configuration/get-village-limit&id=' + id,
                        //data: 'id='+id,
                        success: function(data) {

                            var obj1 = $.parseJSON(data);
                            $('#systemconfiguration-village_from').val(obj1.from);
                            $('#systemconfiguration-village_to').val(obj1.to);
                        },
                        error:function(data){
                                    //alert('Your data has not been submitted..Please try again');
                                }
            });
    });
";
$this->registerJs($script, View::POS_END, 'village-code');
?>

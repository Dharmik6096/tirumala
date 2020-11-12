<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\View;
?>


<?php

$form = ActiveForm::begin(['options' => [
                'class' => 'save-form',
                'field-class' => 'form-group col-sm-3',
            ],
            'validateOnBlur' => FALSE,
            
            'validateOnChange' => FALSE,
            'enableClientValidation' => FALSE,
            'validateOnSubmit' => true,
        ]);
?>


<?= Html::hiddenInput('product_req', '', ['id' => 'product_req_1']); ?>

<?php if ($model->isNewRecord && $_GET['id'] != -1) { ?>
    <p></p>
    <?= Html::submitButton(Yii::t('app', 'Submit Requisition'), ['class' => 'btn btn-default apply-shortcut', 'value' => 'submit', 'name' => 'submit']) ?>
<?php } ?>

<?php ActiveForm::end(); ?>

<?php

$selectscript = "";
$script = " 
    
    var records;
    var jsonEncoded = '" . $jsonEncoded . "';
    if(jsonEncoded==''){    
        records = localStorage.getItem('productRequisition');
    }else{
        records = jsonEncoded;
    }
    $('#product_req_1').val(records);
";
$this->registerJs($script, View::POS_END, 'req-txn-submit');
?>
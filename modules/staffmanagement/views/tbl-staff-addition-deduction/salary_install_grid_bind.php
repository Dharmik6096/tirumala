<?php

use yii\helpers\Html;
?>
<tr class="<?= $instlModel->installment_no ?>">
    <?= Html::activeHiddenInput($instlModel, '[' . $instlModel->installment_no . ']union_code'); ?> 
    <?= Html::activeHiddenInput($instlModel, '[' . $instlModel->installment_no . ']staff_installment_code'); ?> 
    <?= Html::activeHiddenInput($instlModel, '[' . $instlModel->installment_no . ']staff_addition_deduction_no'); ?> 
    <?= Html::activeHiddenInput($instlModel, '[' . $instlModel->installment_no . ']installment_no'); ?> 
    <?= Html::activeHiddenInput($instlModel, '[' . $instlModel->installment_no . ']amount'); ?> 
    <?= Html::activeHiddenInput($instlModel, '[' . $instlModel->installment_no . ']deduction_date', ['value' => date('m-Y', strtotime($instlModel->deduction_date))]) ?> 

    <td class="installment_no"><?= $instlModel->installment_no ?></td>  
    <td class="amount"><?= $instlModel->amount ?></td>
    <td class="deduction_date"><?= date('m-Y', strtotime($instlModel->deduction_date)) ?></td>
    <td class="salary_processed"><?= ($instlModel->salary_processed) == 0 ? 'Unprocessed' : '' ?></td>
    <td class="installment_no"><a href="javascript:void(0)" class='edit' onClick="editSalaryInstall('<?= $instlModel->installment_no ?>')" id='<?= $instlModel->installment_no ?>' title='Edit'><span class='glyphicon glyphicon-pencil'></span></a></td>

</tr>
<?php

use yii\helpers\Html;
use yii\web\View;
use yii\helpers\Url;
?>
<?php
if (!empty($bill_head_data)) {
    ?>
    <div id="removeBillHeadDetails">

        <div class="row">
            <div class="col-xs-12">
                <div class="table-responsive col-xs-12 reset_field" id='dispatch_form hide_help_block'>
                    <table class="table table-bordered table-striped table-main table-language">
                        <thead>
                            <tr>
                                <th><?= $bill_head_model->getAttributeLabel('bill_head_name') ?></th>
                                <th><?= Yii::t('app', 'Amount') ?></th>
                            </tr>
                        </thead>
                        <tbody class='append_data'>
                            <?php
                            foreach ($bill_head_data as $bill_head) {
                                $value = '';
                                $code = '';
                                $i=-1;
                                foreach ($detail_data as $key=>$data) {
                                    if ($bill_head->bill_head_code == $data['bill_head_code']) {
                                        $value = $detail_data[0]['amount'];
                                        $code = $detail_data[0]['bill_head_detail_code'];
                                        $i=$key;
                                        break;
                                    }
                                }
                                if($i>-1)
                                    array_splice($detail_data, $i, 1);
                                ?>
                                <tr>
                                    <td>                                   
                                        <?= $bill_head->bill_head_name ?>
                                    </td>
                                    <td>

                                        <?= Html::input('text', 'amount[]', $value, ['class' => 'form-control', 'min' => 0]) ?>
                                        <?= Html::input('hidden', 'bill_head[]', $bill_head->bill_head_code, ['class' => 'form-control', 'min' => 0]) ?>
                                        <?= Html::input('hidden', 'bill_head_detail[]', $code, ['class' => 'form-control', 'min' => 0]) ?>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>  
                </div>

            </div>
        </div>
    </div>
<?php } ?>
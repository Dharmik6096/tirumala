<?php

use yii\helpers\Html;
use yii\web\View;
use yii\helpers\Url;
?>

<div class="modal modal-default fade" id="testTax" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close close-import" data-bs-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo Yii::t('app', 'Tax Test'); ?></h4>
            </div>

            <div class="modal-body">
                <div class="panel panel-main">
                    <!--<div class="panel-heading"><?php // echo Yii::t('app', 'Tax Test'); ?></div>-->
                    <div class="panel-body">
                        <div class="panel-subheading">
                            <div class="row">
                                <div class="col-sm-6">
                                    <?= Html::label(Yii::t('app','Tax Setting Name')); ?>
                                    <?= Html::dropDownList('tax', $selected, $taxes, ['class' => 'form-control disabled', 'id' => 'tax_setting']) ?>
                                </div>
                                <div class="col-sm-6">
                                    <?= Html::label(Yii::t('app','Enter Gross Amount')); ?>
                                    <?= Html::textInput('amount', '', ['class' => 'form-control number-validate']) ?>
                                </div>
                            </div>
                        </div>
                        <div class="ex2-grid">
                            <div class='table-responsive mt15'>
                                <table id="test-table" class='table table-bordered table-striped table-main'>
                                    <tr id="test-header-tr">
                                        <th class='width15'><?=Yii::t('app','Tax Code')?></th>
                                        <th class='width15'><?=Yii::t('app','Tax Name')?></th>
                                        <th class='width10'><?=Yii::t('app','Tax Value')?></th>
                                        <th class='width15'><?=Yii::t('app','Operation')?></th>
                                        <th class='width15'><?=Yii::t('app','Calculated')?></th>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer">
                        <button type="button" class="btn btn-default btn-raised close-import" data-bs-dismiss="modal"><?= Yii::t('app', 'Close') ?></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$script = "
            $('.number-validate').on('blur',function(e){
                    var tax = $('#tax_setting').val();
                    var amount = $(this).val();
                   if(amount.trim()){
                        $.ajax({
                            type: 'POST',
                            url: '" . Url::to(['/dcsaccounting/tbl-tax-detail/get-calculation']) . "',     
                            data: 'amount='+amount+'&tax='+tax,
                            success: function(data)
                            {
                                var obj1 = $.parseJSON(data);
                                if (obj1.status == 'success')
                                {
                                   var content='';
                                   $.each(obj1.data, function( index, value ) {
                                        content += '<tr><td class=\'width15\'>'+value.tax_code+'</td><td class=\'width15\'>'+value.tax_name+'</td><td class=\'width10\'>'+value.tax_val+'</td><td class=\'width15\'>'+value.operation+'</td><td class=\'width15\'>'+value.amount+'</td></tr>' 
                                   });
                                   content += '<tr><td class=\'width15\'></td><td class=\'width15\'></td><td class=\'width10\'></td><td class=\'width15\'><b>Total</b></td><td class=\'width15\'>'+obj1.total+'</td></tr>';
                                   $('#test-table tr:not(:first)').remove();
                                   $(content).insertAfter('#test-header-tr');
                                }
                            }
                          });
                    }else{
                        $('#test-table tr:not(:first)').remove();
                    }
                   
            });
";
$this->registerJs($script, View::POS_END, 'tax-calculate');
?>
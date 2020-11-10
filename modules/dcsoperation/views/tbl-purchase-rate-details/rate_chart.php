<?php

use yii\web\View;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = Yii::t('app', 'Rate Chart');
?>

<div class="panel panel-default panel-grid panel-main">
    <div class="panel-heading">
        <?= Yii::$app->controls->cancel($model, 'tbl-purchase-rate/index'); ?>
        <?= $this->title ?>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-sm-12">
                <div class="grid-search large-search">
                    <?php $form = ActiveForm::begin(); ?>
                    <?= Html::activeHiddenInput($model, 'purchase_rate_code', ['value' => $model->purchase_rate_code]); ?>
                    <div class="col-sm-2">
                        <?= Yii::$app->dropdown->dropdown('milk_type_code', $model, $form, '', false); ?>
                    </div>
                    <div class="col-sm-2">
                        <?= Yii::$app->dropdown->dropdownStatic('rate_class', $model, $form, 'form-group padding-right-5', false, false, 'rate_class') ?> 
                    </div>
                    <div class="col-sm-2">
                        <?php //Yii::$app->controls->save('Submit', $model); ?>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="table-responsive table-rate-chart">
                    <table class="table table-bordered table-striped table-input" id="table">
                        <?php
                        $cnt = 0;
                        foreach ($fat as $key => $attr) {
                            if ($key == 0) {
                                if (count($snf) == 1 && $snf[0]->snf == NULL) {
                                    echo "<tr><th>" . $attr->rateTypeCode->rate_type . "</th><th>RTPL</th></tr> ";
                                } else {
                                    echo "<tr><th class='width100px'>" . $attr->rateTypeCode->rate_type . "</th>";
                                    foreach ($snf as $s) {
                                        echo "<th class='width100px'>" . $s->snf . "</th>";
                                    }
                                    echo "</tr>";
                                }
                            }
                            ?>
                            <tr>
                                <th><?= $attr->fat; ?></th>
                                <?php
                                if (count($snf) == 1 && $snf[0]->snf == NULL) {
                                    echo "<td class='$class' id=" . $rate[$cnt]->code . ">" . round($rate[$cnt]->rtpl, 2) . "</td>";
                                    $cnt++;
                                } else {
                                    foreach ($snf as $s) {
                                        if (isset($rate[$cnt]) && $s->snf == $rate[$cnt]->snf) {
                                            echo "<td class='$class' id=" . $rate[$cnt]->code . ">" . round($rate[$cnt]->rtpl, 2) . "</td>";
                                            $cnt++;
                                        } else {
                                            echo "<td></td>";
                                        }
                                    }
                                }
                                ?>
                            </tr>
                        <?php } ?>

                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$script = "
    $('#tblpurchaseratedetails-milk_type_code').on('change', function(e){
        this.form.submit()
    });
    $('#tblpurchaseratedetails-rate_class').on('change', function(e){
        this.form.submit()
    });
    $( document ).ready(function() {
        localStorage.removeItem('purchaseRate');
    });
     $('#table').on('click', '.edit', function() {
            // replace the existing text with a textbox containing that text
            var existingVal = $(this).text();
            $(this).html('<input type=\'text\' class=\'form-control\' value=\''+existingVal + '\' id=\'data\' >');
            $('#data').focus().select();

    });

    $('td').on('blur', 'input', function(event) {
        var id = $(this).parent().prop('id');
        if(this.value != ''){
        $.ajax({
                        type: 'post',
                        url: '" . Url::to(['update-rate-chart']) . "' ,
                        data: 'id='+id+'&rtpl='+this.value,
                        success: function(data) {
                            data=JSON.parse(data);
                            $('#'+id).text(data.rtpl);
                            this.value=data.rtpl;
                            if(data.status=='error')      
                                bootbox.alert(\"<div class=\'row\'><div class=\'col-sm-2\'><i class=\'fa fa-3x fa-times-circle text-danger\'></i></div><div class=\'col-sm-10 padding-left-0\'>\"+data.message+\"</div></div>\");
                              //  bootbox.alert(\"<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-danger\'><i class=\'fa fa-times\'></i></div><span>\"+data.message+\"</span></div></div>\");
                          
                        },
                        error:function(data){
                                    //alert('Your data has not been submitted..Please try again');
                                }
            });
         }
           
    });
    ";
$this->registerJs($script, View::POS_END, 'change-manager');

<?php

use yii\web\View;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = Yii::t('app', 'Rate Chart');
?>

<div class="panel panel-default panel-grid panel-main">
    <div class="panel-heading">
        <?= Yii::$app->controls->cancel($model, 'tbl-tanker-rate/index'); ?>
        <?= $this->title ?>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-sm-12">
                <div class="pt5 large-search">
                    <?php $form = ActiveForm::begin(); ?>
                    <?= Html::activeHiddenInput($model, 'tanker_rate_code', ['value' => $model->tanker_rate_code]); ?>
                    <div class="col-sm-2">
                        <?= Yii::$app->dropdown->dropdown('milk_type_code', $model, $form, '', false); ?>
                    </div>
                    <div class="col-sm-2">
                        <?= Yii::$app->dropdown->dropdown('milk_quality_type_code', $model, $form, '', false); ?>
                    </div>
                    <div class="col-sm-2">
                        <?php //Yii::$app->controls->save('Submit', $model); ?>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="table-responsive table-rate-chart">
                    <div id="fixed-table-container" class="table-responsive table-rate-chart fixed-table-container">
                        <table class="table table-bordered table-striped table-main table-language table-rate-chart" id="table">
                            <?php
                            $cnt = 0;
                            echo "<thead>";
                            foreach ($fat as $key => $attr) {
                                if ($key == 0) {
                                    if (count($snf) == 1 && $snf[0]->snf == NULL) {
                                        echo "<tr><th>" . $attr->rateTypeCode . "</th><th>RTPL</th></tr> ";
                                    } else {
                                        echo "<tr><th class='w50'>" . $attr->rateTypeCode . "</th>";
                                        foreach ($snf as $s) {
                                            echo "<th class='w50'>" . $s->snf . "</th>";
                                        }
                                        echo "</tr>";
                                    }
                                }
                            }
                            echo "</thead>";
                            echo "<tbody>";
                            foreach ($fat as $key => $attr) {
                                ?>
                                <tr>
                                    <td><?= $attr->fat; ?></td>
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
                            <?php } echo "</tbody>"; ?>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$script = "
    $('#tbltankerratedetails-milk_type_code').on('change', function(e){
        this.form.submit()
    });
    $('#tbltankerratedetails-milk_quality_type_code').on('change', function(e){
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
    var fixedTable1 = fixTable(document.getElementById('fixed-table-container'));
    ";
$this->registerJs($script, View::POS_END, 'change-manager');

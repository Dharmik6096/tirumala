<?php
use yii\web\View;
$this->title = Yii::t('app', Yii::$app->label->title('list', 'Release Hold Amount  (Member) '));
?>

<div class="panel panel-default panel-grid panel-main hide-grid-settings">
    <div class="panel-heading">
        <?= $this->title; ?>
    </div>
    <div class="">
        <?php echo $this->render('_release_search', ['model' => $searchModel, 'dataProvider' => $dataProvider]); ?>
        <div class="clearfix"></div>
        <?=
        $this->render('_release_grid', [
        'dataProvider' => $dataProvider,
        'searchModel' => $searchModel,
        ])
        ?>
    </div>
</div>
<?php
$script = "
$(document).ready(function() {
    $('#release').click(function() {
        var checkBoxCount = $('.kv-row-checkbox:checked').length;
        var pay_cycle = $('#payment_cycle_code').val();
        var err = '';
        if(pay_cycle == ''){
            err = 'Please select payament cycle<br>';
        }
        if(checkBoxCount <= 0){
            err = err + 'Please Select atleast one Record'
        }
        if(err == '') {
            $('#release-member-payment-form').submit();
        } else {
            bootbox.alert('<div class=\'bg-danger\'><i class=\'fa fa-times-circle\'></i></div><span>'+err+'</span>');
        }
    });
    
    $('#tblpermanentholdamountsearch-payment_cycle_code').on('change', function(){
        var payment_cycle_code = $(this).val();
        $('#payment_cycle_code').val(payment_cycle_code);
    });
});";
$this->registerJs($script, View::POS_END, 'release-script');
?>
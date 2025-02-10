<?php
use yii\web\View;
$this->title = Yii::t('app', Yii::$app->label->title('list', 'Release Hold Amount  (Member) '));
?>

<div class="panel panel-default panel-grid panel-main hide-grid-settings">
    <div class="panel-heading">
        <?= $this->title; ?>
    </div>
    <div class="panel-body">
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
        var err = '';
        var releaseDate = $('#release_date').val();
        if(releaseDate == ''){
            err = 'Please select release date<br>';
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
    
    $('#tblpermanentholdamountsearch-release_date').on('change', function(){
        var release_date = $(this).val();
        $('#release_date').val(release_date);
    });

    $('.release-amount').on('change', function(){
        var amount = 0;
        var parent = $(this).parents('tr');
        var releaseAmount = parseFloat(parent.find('.release-amount').val());
        var holdAmount = parseFloat(parent.find('.hold-amount').text());
        if(releaseAmount == '' || isNaN(releaseAmount)){
            releaseAmount = 0;
        }
        if(holdAmount == '' || isNaN(holdAmount)){
            holdAmount = 0;
        }
        amount = holdAmount - releaseAmount;
        if(amount < 0){
            parent.find('.release-amount').val(0);
            bootbox.alert(\"<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-danger\'><i class=\'fa fa-times\'></i></div><span>Release amount should not be greater than the hold amount.</span></div></div>\");
            return false;
        }
        parent.find('.pending-hold-amount').text(amount);
    });
});";
$this->registerJs($script, View::POS_END, 'release-script');
?>
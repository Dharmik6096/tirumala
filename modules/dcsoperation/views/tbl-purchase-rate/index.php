<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use webvimark\modules\UserManagement\components\GhostHtml;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\dcsoperation\models\TblMeetingAgendaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', Yii::$app->label->title('list', 'purchase rate'));
if (Yii::$app->general->checkAccess('/dcsoperation/tbl-purchase-rate/create')) {
    $this->params['menu'][] = Yii::$app->controls->add('Purchase Rate');
    $this->params['menu'][] = GhostHtml::a_alert('<i class="fa fa-download"></i>' . Yii::t('app', 'Download Template'), '/dcsoperation/tbl-purchase-rate/create', ['class' => 'btn btn-danger btn-block apply-shortcut show-sample', 'shortcut_key' => 'ctrl+alt+c']);
}
$this->params['menu'][] = Yii::$app->controls->import('applicability-import', $this);
?>
<div class="panel panel-default panel-grid panel-main">
    <div class="panel-heading">
        <?= $this->title; ?>
    </div>
    <div class="panel-body">
        <?=
        $this->render('_form_grid', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ])
        ?>
    </div>
    <?= $this->render('_sample_popup', ['purchaseBasedModel' => $purchaseBasedModel]) ?>
</div>
<?php
$script = "
     localStorage.removeItem('transactionsArray');
     $('#test-table tr:not(:first)').remove();
     $('#error-summary').hide();
     $('#range_table').val('');
     $('.show-sample').on('click',function(e){
                localStorage.removeItem('transactionsArray');
                $('#test-table tr:not(:first)').remove();
                $('#error-summary').hide();
                $('#range_table').val('');
             $('#range').empty();
            $('#tblpurchaseratebased-0-rate_type_code').val('');
            $('#tblpurchaseratebased-0-milk_type_code').val('');
                $('#sampleModal').modal('toggle');
            });
";
$this->registerJs($script, View::POS_END, 'import-sample');
?>
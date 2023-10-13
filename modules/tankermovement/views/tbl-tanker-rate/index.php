<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use webvimark\modules\UserManagement\components\GhostHtml;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\tankermovement\models\TblMeetingAgendaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', Yii::$app->label->title('list', 'Tanker Rate'));
if (Yii::$app->general->checkAccess('/tankermovement/tbl-tanker-rate/create')) {
    $this->params['menu'][] = Yii::$app->controls->add('Tanker Rate');
   // $this->params['menu'][] = GhostHtml::a_alert('<i class="fa fa-download"></i>' . Yii::t('app', 'Download Template'), '/tankermovement/tbl-tanker-rate/create', ['class' => 'btn btn-danger btn-block apply-shortcut show-sample', 'shortcut_key' => 'ctrl+alt+c']);
}
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
            $('#tbltankerratebased-0-rate_type_code').val('');
            $('#tbltankerratebased-0-milk_type_code').val('');
                $('#sampleModal').modal('toggle');
            });
";
$this->registerJs($script, View::POS_END, 'import-sample');
?>
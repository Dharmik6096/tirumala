<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\components\GeneralFunctions;
/* @var $this yii\web\View */
/* @var $model app\modules\dcsoperation\models\TblMeetingAgenda */

$this->title = Yii::$app->label->title('view', 'Meeting Agenda');
?>
<div class="tbl-meeting-agenda-view">

    <div class="panel panel-main">
        <div class="panel-heading"><?= Html::encode($this->title) ?></div>
        <div class="panel-body">
            <div class="panel-subheading padding-0">
                <div class="table-responsive">

                <?= DetailView::widget([
                    'model' => $model,
                    'options' => ['class' => 'table detail-view'],
                    'attributes' => [
                        'meeting_agenda_code',
                        'meetingTypeCode.meeting_type_name',
                        'subject_line',
                        'detailed_agenda',
                        [
                            'attribute' => 'meeting_date',
                            'format' => 'html',
                            'value' => Yii::$app->controls->view_date($model->meeting_date)
                        ],
                        [
                            'attribute' => 'date',
                            'format' => 'html',
                            'value' => Yii::$app->controls->view_date($model->date)
                        ],
                        'dcsCode.dcs_name',
                        'unionCode.union_name',
                        [
                            'attribute' => 'is_active',
                            'label' => 'Status',
                            'format' => 'html',
                            'value' => GeneralFunctions::getRecordStatus($model->is_active)
                        ],
                    ],
                ]) ?>
              </div>
                <div class="panel-table-subtitle">Minutes of Meeting</div>            
        </div>
        <?php echo $this->render('_mom_grid', ['dataProvider' => $dataProvider, 'searchModel' => $searchModel]); ?>
           
        </div>
        <div class="panel-footer shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
            <?= Yii::$app->controls->cancel($model); ?>
        </div>
    </div>        
</div>

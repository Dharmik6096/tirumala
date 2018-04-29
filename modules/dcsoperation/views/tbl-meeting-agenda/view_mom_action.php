<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\helpers\Html;
use app\components\GeneralFunctions;
use yii\widgets\DetailView;

$this->title = Yii::t('app', Yii::$app->label->title('view', 'Minutes of Meeting'));
?>
<div class="panel panel-main">
        <div class="panel-heading"><?= Html::encode($this->title) ?></div>
        <div class="panel-body">
            <div class="panel-subheading padding-0">
                <div class="table-responsive">

                <?= DetailView::widget([
                    'model' => $model,
                    'options' => ['class' => 'table detail-view'],
                    'attributes' => [
                        'mom_code',
                        'mom',
                        'status',
                        [
                            'attribute' => 'dcs_code',
                            'label' => 'DCS',
                            'format' => 'html',
                            'value' => $model->dcsCode->dcs_name,
                        ],
                        [
                            'attribute' => 'is_active',
                            'label' => 'Meeting Agenda Subject',
                            'format' => 'html',
                            'value' => $model->meetingAgendaCode->subject_line,
                        ],
                        [
                            'attribute' => 'is_active',
                            'label' => 'Status',
                            'format' => 'html',
                            'value' => GeneralFunctions::getRecordStatus($model->is_active)
                        ],
                    ],
                ]) ?>
              </div>
                <div class="panel-table-subtitle">MOM Action</div>            
        </div>
            <?php echo $this->render('_action_grid', ['dataProvider' => $dataProvider, 'searchModel' => $searchModel]); ?>
            
        </div>
        
        <div class="panel-footer shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
            <?= Html::a('cancel', ['/dcsoperation/tbl-meeting-agenda/view','id'=>$model->mom_code], ['class' => 'btn btn-default apply-shortcut', 'shortcut_key' => 'ctrl+alt+c']); ?>
        </div>
    </div>  

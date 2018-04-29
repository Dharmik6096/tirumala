<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\components\GeneralFunctions;

/* @var $this yii\web\View */
/* @var $model app\modules\dcsoperation\models\TblHeadLoad */

$this->title = Yii::$app->label->title('view', 'Head Load');
?>
<div class="tbl-head-load-view">
<div class="panel panel-main">
        <div class="panel-heading"><?= Html::encode($this->title) ?></div>
        <div class="panel-body">
            <div class="panel-subheading padding-0">
                <div class="table-responsive">

                <?= DetailView::widget([
                    'model' => $model,
                    'options' => ['class' => 'table detail-view'],
                    'attributes' => [
                        'head_load_code',
                        'criteria_description',
                        'criteriaTypeCode.criteria_name',
                        'dcsCode.dcs_name',
                        'unionCode.union_name',
                        [
                            'attribute' => 'org',
                            'label' => 'Dcs/SubCenter',
                            'format' => 'html',
                            'value' => $model->getOrganizationList()
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
                <div class="panel-table-subtitle">Head Load Transaction</div>
        </div>
            
            <?php echo $this->render('@app/modules/dcsoperation/views/tbl-head-load-transaction/_form_grid', ['dataProvider' => $dataProvider, 'searchModel' => $searchModel]); ?>
         </div>
        <div class="panel-footer shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
            <?= Yii::$app->controls->cancel($model); ?>
        </div>
    </div>            
</div>

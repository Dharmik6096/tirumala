<?php
$this->title = Yii::$app->label->title('create', 'Medicine Stock Transfer');
?>

<div class="panel panel-default panel-main theme_border_left theme_border_right theme_border_bottom">
    <div class="panel-heading"><?= $this->title ?></div>
    <h4 class="theme-box-heading"><?= Yii::t('app', 'Medicine Stock Transactions') ?></h4>
    <div class="panel-body">
        <div class="col-sm-12">
            <?php $hideSearchBtn = (!empty($searchModel->from_user_code) && !empty($searchModel->to_user_code) && $searchModel->from_user_code != $searchModel->to_user_code) && ( ($searchModel->medicine_wise == '1') || (isset($bulkSearchModel) && isset($bulkDataProvider) && !empty($bulkDataProvider->getModels())) ); ?>
            <div class="grid-search large-search hidden-print padding_10_0">
                <?= $this->render('_search', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider, 'hideSearchBtn' => $hideSearchBtn]); ?>
            </div>
        </div>

        <?php
        if (!empty($searchModel->from_user_code) && !empty($searchModel->to_user_code) && $searchModel->from_user_code != $searchModel->to_user_code) {
            if ($searchModel->medicine_wise == '0') {
                $bulkParams = ['model' => $model, 'type' => 'create', 'txModel' => $txModel, 'searchModel' => $searchModel];
                if (isset($bulkSearchModel) && isset($bulkDataProvider) && !empty($bulkDataProvider->getModels())) {
                    $bulkParams['bulkSearchModel'] = $bulkSearchModel;
                    $bulkParams['bulkDataProvider'] = $bulkDataProvider;
                }
                ?>
                <div class="col-sm-1"></div>
                <div class="col-sm-10 theme_border_left theme_border_right theme_border_bottom padding_10_0">
                    <h4 class="theme-box-heading"><?= Yii::t('app', 'Medicine Stock Txn Transfer Details') ?></h4>

                    <div id="maincontent">
                        <?= $this->render('bulk_list_grid', $bulkParams) ?>
                    </div>
                </div>
                <div class="col-sm-1"></div>
                <?php } elseif ($searchModel->medicine_wise == '1') { ?>
                <div id="maincontent">
                <?= $this->render('medicine_transfer_form', ['model' => $model, 'type' => 'create', 'txModel' => $txModel, 'searchModel' => $searchModel]) ?>
                </div>
                <?php
            }
        }
        ?>

        <div class="col-md-12 padding_10_0 theme-box view-subtitle">
            <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 margin-bottom-10 clearfix">
                <h4 class="theme-box-heading"><?= Yii::t('app', 'Medicine Stock Transactions') ?></h4>
            </div>
            <div class="form-grid">
                <?= $this->render('_list_grid', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider, 'txModel' => $txModel]) ?>
            </div>
        </div>
    </div>
</div>
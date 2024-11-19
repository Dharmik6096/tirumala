<?php

use yii\helpers\Html;

$this->title = Yii::$app->label->title('view', 'Manual Collection Approval');
$approval_detail = $model->collectionApproval;
?>
<div class="panel panel-default panel-grid panel-main">
    <div class="panel-body">
        <div class="panel-heading">
            <?= Html::encode($this->title) ?>
        </div>
        <div class="col-md-12 padding_10_0 theme-box mt10">
            <div class="col-sm-12 col-md-12 padding_left_0 padding_right_0 margin-bottom-10 clearfix">
                <h4 class="theme-box-heading"><?php echo Yii::t('app', 'Manual Collection Approval Detail') ?></h4>
            </div>
            <div class="form-grid">
                <div class="col-sm-12">
                    <table class="table table-bordered table-striped table-main table-language table-rate">
                        <tbody>
                        <thead>
                            <tr>
                                <th><?= Yii::t('app', 'Level') ?></th>
                                <th><?= Yii::t('app', 'Mode') ?></th>
                                <th><?= Yii::t('app', 'User') ?></th>
                                <th><?= Yii::t('app', 'Login Type') ?></th>
                                <th><?= Yii::t('app', 'Status By') ?></th>
                                <th><?= Yii::t('app', 'Status') ?></th>
                                <th><?= Yii::t('app', 'Date') ?></th>
                                <th><?= Yii::t('app', 'Remarks') ?></th>

                            </tr>
                        </thead>
                        <?php foreach ($approval_detail as $approval) { ?>
                            <tr>
                                <td><?= $approval->level; ?></td>
                                <td><?= $approval->approval_mode; ?></td>
                                <?php if ($model->originating_org_type = 'HO') { ?>
                                    <td><?= Yii::$app->general->getmultiforeignkey($approval->manualCollectionUserCode, ['collectionUserCode'], 'name') ?></td>
                                <?php } else { ?>
                                    <td><?= Yii::$app->general->getforeignkey($approval->userCode, 'name') ?></td>
                                <?php } ?>
                                <td><?= $approval->login_type; ?></td>
                                <?php if ($model->originating_org_type = 'HO') { ?>
                                    <td><?= Yii::$app->general->getmultiforeignkey($approval->manualCollectionUpdatedBy, ['collectionUserCode'], 'name') ?></td>
                                <?php } else { ?>
                                    <td><?= Yii::$app->general->getforeignkey($approval->updatedBy, 'name') ?></td>
                                <?php } ?>
                                <td>
                                    <?php
                                    if ($approval->status == '1') {
                                        $approval->status = 'Approve';
                                    } else if ($approval->status == '2') {
                                        $approval->status = 'Reject';
                                    } else {
                                        $approval->status = 'Pending';
                                    }
                                    echo $approval->status;
                                    ?>
                                </td>
                                <td><?= Yii::$app->controls->view_datetime($approval->created_at); ?></td>
                                <td><?= $approval->remarks; ?></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>       
        </div>
    </div>
</div>

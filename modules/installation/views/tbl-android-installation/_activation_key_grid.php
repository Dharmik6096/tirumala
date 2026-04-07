<?php
$this->title = Yii::t('app', 'Activation Detail Of ') . $title;
?>
<div class="modal modal-default fade" id="activation-key-content" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="modal-title"><?= $this->title ?></h4>
            </div>
            <div class='row pad-10'>
                <div class="col-md-12">
                    <div class="row">
                        <table class="table table-striped table-bordered">
                            <tbody>
                                <tr>
                                    <td><strong><?= Yii::t('app', 'Activation Key'); ?></strong></td>
                                    <td><?= $hashKey ?></td>
                                </tr>
                                <tr>
                                    <td><strong><?= Yii::t('app', 'Passcode'); ?></strong></td>
                                    <td><?= $syncKey ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
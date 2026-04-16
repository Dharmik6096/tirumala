<?php

namespace app\modules\androiddpu\jobs;

use app\models\GeneralModel;
use Exception;
use yii\base\BaseObject;
use yii\queue\JobInterface;
use app\modules\syncutility\models\TblInbox;

class InboxJob extends BaseObject implements JobInterface {

    public $transaction_data;
    public $sync_timestamp;

    /**
     * @param \yii\queue\Queue $queue
     * @return bool|null
     */
    public function execute($queue) {
        try {
            if (!empty($this->transaction_data['uuid'])) {
                $model = new TblInbox();
                $model->setAttributes($this->transaction_data);
                $model->sync_timestamp = $this->sync_timestamp;

                $generalModel = new GeneralModel();
                $transaction = $generalModel->saveDeleteTransaction([$model], [], [], ['transactional data', 'create'], true);
                \Yii::info("AMCS Inbox Queue Fill : " . $this->transaction_data['uuid']);
                if ($transaction !== 'customRedirect') {
                    $errorData = !empty($transaction) ? (string) $transaction : 'error_occured';
                    if (strstr(strtolower($errorData), 'cannot insert duplicate key')) {
                        \Yii::info("AMCS Inbox Queue Parse : " . $this->transaction_data['uuid'] . " : " . $errorData);
                        return true;
                    } else {
                        \Yii::info("AMCS Inbox Queue Parse : Re-processing UUID-" . $this->transaction_data['uuid'] . " : " . $errorData);
                        $queue->push(new self([
                            'transaction_data' => $this->transaction_data,
                            'sync_timestamp' => $this->sync_timestamp,
                        ]));
                    }
                }
            } else {
                \Yii::info("AMCS Inbox Queue Fill : uuid missing in InboxJob");
                $queue->push(new self([
                    'transaction_data' => $this->transaction_data,
                    'sync_timestamp' => $this->sync_timestamp,
                ]));
            }
        } catch (\Exception $e) {
            \Yii::info("AMCS Inbox Queue Fill : Exception in InboxJob: " . $this->transaction_data['uuid'] . " : " . $e->getMessage());
            $queue->push(new self([
                'transaction_data' => $this->transaction_data,
                'sync_timestamp' => $this->sync_timestamp,
            ]));
        } catch (\Throwable $e) {
            \Yii::info("AMCS Inbox Queue Fill : Exception in InboxJob: " . $this->transaction_data['uuid'] . " : " . $e->getMessage());
            $queue->push(new self([
                'transaction_data' => $this->transaction_data,
                'sync_timestamp' => $this->sync_timestamp,
            ]));
        }
    }

}

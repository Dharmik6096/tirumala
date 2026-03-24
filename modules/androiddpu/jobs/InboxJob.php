<?php
namespace app\modules\androiddpu\jobs;

use app\models\GeneralModel;
use Exception;
use yii\base\BaseObject;
use yii\queue\JobInterface;
use app\modules\syncutility\models\TblInbox;

class InboxJob extends BaseObject implements JobInterface
{
    public $transaction_data;
    public $sync_timestamp;

    /**
     * @param \yii\queue\Queue $queue
     * @return bool|null
     */
    public function execute($queue)
    {
        try {
            if (!empty($this->transaction_data['uuid'])) {
                $model = new TblInbox();
                $model->setAttributes($this->transaction_data);
                $model->sync_timestamp = $this->sync_timestamp;

                $generalModel = new \app\models\GeneralModel();
                $transaction = $generalModel->saveDeleteTransaction([$model], [], [], ['transactional data', 'create'], true);

                if ($transaction !== 'customRedirect') {
                    $errorData = (string)$transaction;
                    if (str_contains(strtolower($errorData), 'duplicate key') || str_contains(strtolower($errorData), 'primary key')) {
                        return true;
                    }
                    throw new \Exception("Database Error in Queue: " . $errorData);
                }
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
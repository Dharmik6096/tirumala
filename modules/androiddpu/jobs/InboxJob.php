<?php
namespace app\modules\androiddpu\jobs;

use Yii;
use yii\base\BaseObject;
use yii\queue\JobInterface;
use app\modules\syncutility\models\TblInbox;

class InboxJob extends BaseObject implements JobInterface
{
    public $transaction_data;

    /**
     * @param \yii\queue\Queue $queue
     * @return bool|null
     */
    public function execute($queue)
    {
        $uuid = $this->transaction_data['uuid'] ?? null;
        if (!$uuid) return true;

        $model = new TblInbox();
        $model->setAttributes($this->transaction_data);
        $model->sync_timestamp = date('Y-m-d H:i:s');

        $generalModel = new \app\models\GeneralModel();
        $transaction = $generalModel->saveDeleteTransaction([$model], [], [], ['transactional data', 'create'], true);

        if ($transaction !== 'customRedirect') {
            $errorData = (string)$transaction;
            if (str_contains(strtolower($errorData), 'duplicate key') || str_contains(strtolower($errorData), 'primary key')) {
                return true;
            }
            throw new \Exception("Database Error in Queue: " . $errorData);
        }

        return true;
    }

    /*public function execute($queue) {
        $data = $this->transaction_data;
        $uuid = $data['uuid'] ?? null;
        if (!$uuid) {
            return;
        }

        $model = new TblInbox();
        $model->setAttributes($data);
        $model->sync_timestamp = date('Y-m-d H:i:s');

        $generalModel = new \app\models\GeneralModel();
        $result = $generalModel->saveDeleteTransaction([$model], [], [], ['transactional data', 'create'], true);
        if ($result !== 'customRedirect') {
            $error = (string)$result;
            if (stripos($error, 'duplicate key') === false) {
                throw new \Exception("DB Error: " . $error);
            }
        }
    }*/
}
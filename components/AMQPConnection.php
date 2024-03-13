<?php

namespace app\components;

use yii\base\Component;
use Yii;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class AMQPConnection extends Component {

    private $connection = '';
    private $channel = '';
    public $queueName = '';
    public $queueData = '';

    public function ConnectServer() {
        try {
            $params = Yii::$app->params['amqp_detail'];
            if (!empty($params)) {
                $this->connection = new AMQPStreamConnection($params['url'], $params['port'], $params['username'], $params['password']);
                $this->channel = $this->connection->channel();
                return TRUE;
            }
        } catch (\Throwable $ex) {
            return FALSE;
        }
    }

    public function DeclareQueue() {
        try {
            $this->channel->queue_declare($this->queueName, false, true, false, false);
            return TRUE;
        } catch (\Throwable $ex) {
            return FALSE;
        }
    }

    public function PublishToQueue() {
        try {
            $this->channel->basic_publish(new AMQPMessage($this->queueData), '', $this->queueName);
            return TRUE;
        } catch (\Throwable $ex) {
            return FALSE;
        }
    }

    public function CloseConnection() {
        try {
            $this->channel->close();
            $this->connection->close();
            return TRUE;
        } catch (\Throwable $ex) {
            return FALSE;
        }
    }

}

?> 
<?php

namespace app\components;

use yii\base\Component;
use Yii;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class AMQPConnection extends Component {

    private $url = '192.168.3.109';
    private $port = 5672;
    private $username = 'device';
    private $password = 'device';
    private $connection = '';
    private $channel = '';
    public $queueName = '';
    public $queueData = '';

    public function ConnectServer() {
        try {
            $this->connection = new AMQPStreamConnection($this->url, $this->port, $this->username, $this->password);
            $this->channel = $this->connection->channel();
            return TRUE;
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
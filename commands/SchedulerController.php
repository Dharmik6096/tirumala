<?php

namespace app\commands;

use common\services\DataExchangeService;
use common\services\InboxParseService;
use common\services\ImportFilesService;
use common\services\ImportFilesBackgroudService;
use Yii;

class SchedulerController extends \yii\console\Controller {

    public function init() {
        parent::init();

        if (php_sapi_name() === 'cli') {
            //Only flush if an active buffer actually exists
            if (ob_get_level() > 0) {
                ob_end_flush();
            }
            // Turn on implicit flushing so all future echos stream out instantly
            ob_implicit_flush(true);
        }
    }

    public function actionAmcsParseInboxData() {
        $inboxParseService = new InboxParseService();
        while (true) {
            try {
                $inboxParseService->InboxParsing() ? sleep(20) : sleep(60);
            } catch (\Throwable $ex) {
//                $cmd = (Yii::$app->controller->id ?? "") . "/" . (Yii::$app->controller->action->id ?? "");
//                fwrite(STDERR, '[' . date('Y-m-d H:i:s') . '][' . $cmd . ']: ' . $ex->getMessage() . PHP_EOL);
                sleep(60);
            }
        }
    }

    public function actionProcessImportFiles() {
        $importFilesService = new ImportFilesService();
        while (true) {
            $importFilesService->ProcessImportFiles() ? sleep(20) : sleep(120);
        }
    }

    public function actionProcessImportFilesBackground() {
        $importFilesBackgroundService = new ImportFilesBackgroudService();
        while (true) {
            $importFilesBackgroundService->ProcessImportFilesBackground() ? sleep(20) : sleep(120);
        }
    }

    public function actionProcessComfedDataExchange() {
        $dataExchangeService = new DataExchangeService();
        while (true) {
            try {
                echo "[" . date('Y-m-d H:i:s') . "] Comfed data exchange service Start : Success: " . PHP_EOL;
                $dataExchangeService->processComfedCollection() ? sleep(20) : sleep(120);
            } catch (\Throwable $ex) {
                echo "[" . date('Y-m-d H:i:s') . "] Comfed data exchange service End : Error: " . $ex->getMessage() . PHP_EOL;
                sleep(120);
            }
        }
    }

}

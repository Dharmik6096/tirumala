<?php

namespace app\commands;

use common\services\InboxParseService;
use common\services\ImportFilesService;
use common\services\ImportFilesBackgroudService;
use Yii;

class SchedulerController extends \yii\console\Controller {

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

}

<?php

namespace app\commands;

use common\services\InboxParseService;
use common\services\ImportFilesService;
use common\services\ImportFilesBackgroudService;

class SchedulerController extends \yii\console\Controller {

    public function actionAmcsParseInboxData() {
        $inboxParseService = new InboxParseService();
        while (true) {
            $inboxParseService->InboxParsing();
            sleep(2);
        }
    }

    public function actionProcessImportFiles() {
        $importFilesService = new ImportFilesService();
        while (true) {
            $importFilesService->ProcessImportFiles();
            sleep(2);
        }
    }

    public function actionProcessImportFilesBackground() {
        $importFilesBackgroundService = new ImportFilesBackgroudService();
        while (true) {
            $importFilesBackgroundService->ProcessImportFilesBackground();
            sleep(2);
        }
    }

}

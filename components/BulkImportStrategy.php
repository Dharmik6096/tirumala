<?php

namespace app\components;

use yii;
use yii\base\Exception;
use yii\base\UserException;
use yii\widgets\ActiveForm;
use yii\base\Model;
use app\modules\import\models\TblImportFileLog;
use common\services\ImportFilesService;

class BulkImportStrategy extends \ruskid\csvimporter\ARImportStrategy {

    public function import(&$data) {

        $importedPks = [];
        $importedData = [];
        $errors = [];
        $count = 0;
        try {
            $union_code = explode(',', Yii::$app->session->get('Unions'));
            if (Yii::$app->session->get('organizations_type') !== 'UNION' || count($union_code) > 1) {
                return ['total' => 0, 'status' => 'error', 'pk' => 0, 'msg' => 'Please use single union login.'];
            }
            foreach ($data as $key => $row) {
                if ($key == 0)
                    continue;
                $model = new $this->className;
                $model->scenario = $this->scenario;
                foreach ($this->configs as $config) {
                    $value = call_user_func($config['value'], $row);
                    $model->{$config['attribute']} = $value;
                }
                if (!$model->validate()) {
                    $message = '';
                    foreach ($model->getErrors() as $errorkey => $value) {
                        $message .= $value[0] . '<br/>';
                    }
                    return ['total' => 0, 'status' => 'error', 'pk' => 0, 'msg' => 'There is error in Record No : ' . $key . '<br>' . $message];
                }
                $count++;
            }

            if (!empty($this->details['bkg_scenario']) && count($data) == 1) {
                return ['total' => 0, 'status' => 'error', 'pk' => 0, 'msg' => 'Import file with data.'];
            } else if ($count == count($data) - 1) {
                $path = Yii::$app->basePath . '/web/bulkdata/' . $this->scenario . '/';
                $path = str_replace('\\', '/', $path);
                if (Yii::$app->general->checkDirectory($path . 'archive/')) {
                    $file_path = $path . $this->file_name;
                    if (copy($this->file_path, $file_path)) {
                        $process_status = 0;
                        if (in_array($this->scenario, ['member_payment_shortage_recovery'])) {
                            $process_status = 2;
                        }
                        $model = new TblImportFileLog();
                        $model->scenario = $this->scenario;
                        $model->file_type = $this->scenario;
                        $model->file_name = $this->file_name;
                        $model->file_path = $file_path;
                        $model->union_code = $union_code[0];
                        $model->status = $process_status;
                        if ($model->save()) {
                            unlink($this->file_path);
                            $msg = 'File Imported Successfully.';
                            if ($process_status == 2) {
                                $model->pick_datetime = $model->cron_pick_datetime = date('Y-m-d H:i:s');
                                $import = new ImportFilesService();
                                $import->process_files_data($model);
                                $msg = $model->response_msg;
                            }
                            return ['total' => $count, 'status' => 'success', 'msg' => $msg, 'pk' => $count];
                        }
                    }

                    return ['total' => 0, 'status' => 'error', 'pk' => 0, 'msg' => 'There is error while file upload.'];
                }
            }
        } catch (UserException $e) {
            return ['total' => 0, 'status' => 'error', 'msg' => $e->getMessage(), 'pk' => 0];
        }
    }

}

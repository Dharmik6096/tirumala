<?php

namespace app\modules\bkgprocess\models;

use Yii;
use app\modules\organisation\models\TblDcs;
use app\modules\dcsoperation\models\TblMember;
use app\modules\dcsoperation\models\TblPurchaseRateApplicability;

/**
 * This is the model class for table "tbl_org_file_log".
 *
 * @property integer $org_file_log_id
 * @property string $file_type
 * @property string $file_path
 * @property integer $total_count
 * @property integer $success_count
 * @property integer $error_count
 * @property string $module_name
 * @property string $module_code
 * @property string $local_path
 * @property string $ftp_type
 * @property string $ftp_host
 * @property string $ftp_username
 * @property string $ftp_password
 * @property string $ftp_port
 * @property string $ftp_path
 * @property integer $file_status
 * @property string $updated_at
 * @property string $updated_by
 * @property string $file_name
 * @property integer $status
 * @property string $pick_datetime
 * @property string $created_at
 * @property string $created_by
 * @property string $actual_file
 */
class TblOrgFileLog extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_org_file_log';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['file_type', 'file_path', 'module_name', 'module_code', 'local_path', 'ftp_type', 'ftp_host', 'ftp_username', 'ftp_password', 'ftp_port', 'ftp_path', 'updated_by', 'file_name', 'created_by', 'actual_file'], 'safe'],
            [['total_count', 'success_count', 'error_count', 'file_status', 'status'], 'safe'],
            [['updated_at', 'pick_datetime', 'created_at', 'ref_code'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'org_file_log_id' => Yii::t('app', 'Org File Log ID'),
            'file_type' => Yii::t('app', 'File Type'),
            'file_path' => Yii::t('app', 'File Path'),
            'total_count' => Yii::t('app', 'Total Count'),
            'success_count' => Yii::t('app', 'Success Count'),
            'error_count' => Yii::t('app', 'Error Count'),
            'module_name' => Yii::t('app', 'Module Name'),
            'module_code' => Yii::t('app', 'Module Code'),
            'local_path' => Yii::t('app', 'Local Path'),
            'ftp_type' => Yii::t('app', 'Ftp Type'),
            'ftp_host' => Yii::t('app', 'Ftp Host'),
            'ftp_username' => Yii::t('app', 'Ftp Username'),
            'ftp_password' => Yii::t('app', 'Ftp Password'),
            'ftp_port' => Yii::t('app', 'Ftp Port'),
            'ftp_path' => Yii::t('app', 'Ftp Path'),
            'file_status' => Yii::t('app', 'File Status'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'file_name' => Yii::t('app', 'File Name'),
            'status' => Yii::t('app', 'Status'),
            'pick_datetime' => Yii::t('app', 'Pick Datetime'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'actual_file' => Yii::t('app', 'Actual File'),
        ];
    }

    public function getDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'module_code']);
    }

    public function generateBiplFiles($org_code, $file_type, $rate_id = '') {
        $cp_code = $this->dcsCode->ref_code;
        $crnt_dir = getcwd();
        $ftp_conn_code = $this->dcsCode->mcc_plant_code;
        $path = Yii::$app->params['biplDirPath'] . $cp_code . '/' . 'MASFILES';
        if (Yii::$app->general->checkDirectory($path)) {
            if ($file_type == 'MEMBER') {
                $utility_path = \Yii::getAlias('@webroot') . '/' . Yii::$app->params['biplMemberUtilityPath'];
                $csvPath = Yii::$app->params['rateFilesPath'] . '/members/' . $cp_code;
                if (!empty($org_code) && Yii::$app->general->checkDirectory($csvPath)) {
                    $file = \Yii::getAlias('@webroot') . '/' . $csvPath . '/' . 'members.csv';
                    $model = new TblMember();
                    $flag = $model->generateCsvFile($file, $this->dcsCode);
                    if ($flag) {
                        chdir($utility_path);
                        $enc_path = $path . '/myvendor.VEN';
                        $command = 'milkvendor_cmd_i386-win32_B.exe -i ' . $file . ' -o ' . $enc_path;
                        exec($command);
                        chdir($crnt_dir);
                        $this->saveLog($org_code, 'TblDcs', $cp_code, $ftp_conn_code, $enc_path, $file_type, $file);
                    }
                }
            } else if ($file_type == 'RATE') {
                $utility_path = \Yii::getAlias('@webroot') . '/' . Yii::$app->params['biplRateUtilityPath'];
                $model = new TblPurchaseRateApplicability();
                $model->dcs_code = $org_code;
                $model->purchase_rate_code = $rate_id;
                $files = [];
                $rfiles = $model->generateBiplRateFiles($cp_code);
                if ($rfiles != false) {
                    $files = $rfiles;
                }
                $files = array_values($files);
                foreach ($files as $key => $value) {
                    $ratefile = $value[key($value)];
                    $ratefile = \Yii::getAlias('@webroot') . '/' . $ratefile;
                    chdir($utility_path);
                    $command = 'rfgb ' . $ratefile . ' ' . $path;
                    exec($command);
                    chdir($crnt_dir);
                    $enc_file = (strstr($ratefile, 'cow')) ? 'v1.RC1' : (strstr($ratefile, 'mix') ? 'v1.RM1' : 'v1.RB1');
                    $enc_path = $path . '/' . $enc_file;
                    $this->saveLog($org_code, 'TblDcs', $cp_code, $ftp_conn_code, $enc_path, $file_type, $ratefile);
                }
            }
        }
    }

    private function saveLog($module_code, $module_name, $cp_code, $ftp_conn_code, $path, $file_type, $actual_file) {
        $ftpModel = new TblFtpDetail();
        $ftpModel->ftp_connection_code = $ftp_conn_code;
        $ftpData = $ftpModel->getData();
        if (!empty($ftpData)) {
            $upfiles = is_array($path) ? $path : [$path];
            foreach ($upfiles as $file) {
                $org_files = new TblOrgFileLog();
                $org_files->attributes = $ftpData->attributes;
                $org_files->file_type = $file_type;
                $org_files->module_name = $module_name;
                $org_files->module_code = (string) $module_code;
                $org_files->ref_code = $cp_code;
                $local_path = explode('/', $file);
                $upload_file_name = $local_path[count($local_path) - 1];
                $org_files->local_path = $file;
                $org_files->file_name = $upload_file_name;
                $org_files->file_path = $ftpData->ftp_path . '/' . $cp_code . '/MASFILES/' . $upload_file_name;
                $org_files->file_path = str_replace('//', '/', $org_files->file_path);
                $org_files->status = 0;
                $org_files->file_status = 0;
                $org_files->actual_file = $actual_file;
                $org_files->save();
            }
        }
    }

    public function getPendingData() {
        return $this->find()
                        ->where(['file_status' => $this->file_status, 'status' => $this->status])
                        ->limit(100)
                        ->orderBy(['ftp_host' => SORT_ASC, 'ftp_username' => SORT_ASC, 'ftp_password' => SORT_ASC, 'ftp_port' => SORT_ASC, 'ftp_type' => SORT_ASC])
                        ->all();
    }

    public function updateFileStatus($value) {
        return $this->updateAll(['status' => 1, 'pick_datetime' => date('Y-m-d H:i:s')], ['org_file_log_id' => $value]);
    }

    public function CheckDirectory() {
        return $this->find()->where(['status' => 2, 'module_code' => $this->module_code])->count();
    }

}

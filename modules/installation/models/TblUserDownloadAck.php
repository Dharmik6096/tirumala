<?php

namespace app\modules\installation\models;

use Yii;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\organisation\models\TblDcs;
use app\modules\organisation\models\TblMccPlant;
use app\modules\organisation\models\TblPlant;

/**
 * This is the model class for table "tbl_user_download_ack".
 *
 * @property integer $ack_id
 * @property string $user_code
 * @property string $device_id
 * @property string $hash_key
 * @property integer $download_pending
 * @property string $download_date_time
 * @property string $union_code
 * @property string $plant_code
 * @property string $mcc_plant_code
 * @property string $bmc_code
 * @property string $dcs_code
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 * @property string $x_col1
 * @property string $x_col2
 * @property string $x_col3
 * @property string $x_col4
 * @property string $x_col5
 */
class TblUserDownloadAck extends \app\models\ChildModel {

    public static $cacheExistDataAck = [];

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_user_download_ack';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['download_pending', 'originating_type'], 'integer'],
                [['download_date_time', 'created_at', 'updated_at'], 'safe'],
                [['user_code', 'created_by', 'updated_by'], 'safe'],
                [['device_id'], 'string', 'max' => 500],
                [['hash_key'], 'string', 'max' => 100],
                [['union_code'], 'string', 'max' => 3],
                [['plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code'], 'safe'],
                [['originating_org_code', 'originating_org_type'], 'string', 'max' => 15],
                [['x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'ack_id' => Yii::t('app', 'Ack ID'),
            'user_code' => Yii::t('app', 'User Code'),
            'device_id' => Yii::t('app', 'Device ID'),
            'hash_key' => Yii::t('app', 'Hash Key'),
            'download_pending' => Yii::t('app', 'Download Pending'),
            'download_date_time' => Yii::t('app', 'Download Date Time'),
            'union_code' => Yii::t('app', 'Union Code'),
            'plant_code' => Yii::t('app', 'Plant Code'),
            'mcc_plant_code' => Yii::t('app', 'Mcc Plant Code'),
            'bmc_code' => Yii::t('app', 'Bmc Code'),
            'dcs_code' => Yii::t('app', 'Dcs Code'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'x_col1' => Yii::t('app', 'X Col1'),
            'x_col2' => Yii::t('app', 'X Col2'),
            'x_col3' => Yii::t('app', 'X Col3'),
            'x_col4' => Yii::t('app', 'X Col4'),
            'x_col5' => Yii::t('app', 'X Col5'),
        ];
    }

    public function getExistData($org_type) {
        $query = $this->find()->where(['union_code' => $this->union_code, 'download_pending' => 1]);
        if (strtoupper($org_type == 'PLANT')) {
            $query->andWhere(['plant_code' => $this->plant_code])->andWhere(['=', 'ISNULL(mcc_plant_code,\'\')', '']);
        } elseif (strtoupper($org_type == 'MCC')) {
            $query->andWhere(['plant_code' => $this->plant_code, 'mcc_plant_code' => $this->mcc_plant_code])->andWhere(['=', 'ISNULL(bmc_code,\'\')', '']);
        } elseif ($org_type == 'BMC') {
            $query->andWhere(['plant_code' => $this->plant_code, 'mcc_plant_code' => $this->mcc_plant_code, 'bmc_code' => $this->bmc_code])->andWhere(['=', 'ISNULL(dcs_code,\'\')', '']);
        } elseif ($org_type == 'VLC') {
            $query->andWhere(['plant_code' => $this->plant_code, 'mcc_plant_code' => $this->mcc_plant_code, 'bmc_code' => $this->bmc_code, 'dcs_code' => $this->dcs_code]);
        }
        $bmc = $query->all();
        return $bmc;
    }

    public function getOrgDetail($org_type, $org_code) {
        if (strtoupper($org_type == 'PLANT')) {
            $this->plant_code = $org_code;
            $this->union_code = Yii::$app->general->getforeignkey($this->plantCode, 'union_code');
        } else if (strtoupper($org_type == 'MCC')) {
            $this->mcc_plant_code = $org_code;
            $this->plant_code = Yii::$app->general->getforeignkey($this->mccCode, 'plant_code');
            $this->union_code = Yii::$app->general->getforeignkey($this->mccCode, 'union_code');
        } elseif ($org_type == 'BMC') {
            $this->bmc_code = $org_code;
            $this->mcc_plant_code = Yii::$app->general->getforeignkey($this->bmcCode, 'mcc_plant_code');
            $this->plant_code = Yii::$app->general->getforeignkey($this->bmcCode, 'plant_code');
            $this->union_code = Yii::$app->general->getforeignkey($this->bmcCode, 'union_code');
        } elseif ($org_type == 'VLC') {
            $this->dcs_code = $org_code;
            $this->bmc_code = Yii::$app->general->getforeignkey($this->dcsCode, 'bmc_code');
            $this->mcc_plant_code = Yii::$app->general->getforeignkey($this->dcsCode, 'mcc_plant_code');
            $this->plant_code = Yii::$app->general->getforeignkey($this->dcsCode, 'plant_code');
            $this->union_code = Yii::$app->general->getforeignkey($this->dcsCode, 'union_code');
        }
    }

    public function getBmcCode() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'bmc_code']);
    }

    public function getDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'dcs_code']);
    }

    public function getMccCode() {
        return $this->hasOne(TblMccPlant::className(), ['mcc_plant_code' => 'mcc_plant_code']);
    }

    public function getPlantCode() {
        return $this->hasOne(TblPlant::className(), ['plant_code' => 'plant_code']);
    }

    public function getExistDataAck($org_type, $check_device_id = false) {
        $query = $this->find()->from(static::tableName() . ' WITH (NOLOCK)')->where(['download_pending' => 1]);
        if ($check_device_id) {
            $query->andWhere(['device_id' => $this->device_id]);
        }

        if (strtoupper($org_type) == 'MCC') {
            $query->andWhere(['=', 'ISNULL(bmc_code,\'\')', ''])->andWhere(['mcc_plant_code' => $this->mcc_plant_code, 'plant_code' => $this->plant_code]);
        } elseif (strtoupper($org_type) == 'BMC') {
            $query->andWhere(['=', 'ISNULL(dcs_code,\'\')', ''])->andWhere(['bmc_code' => $this->bmc_code, 'mcc_plant_code' => $this->mcc_plant_code, 'plant_code' => $this->plant_code]);
        } elseif (strtoupper($org_type) == 'VLC') {
            $query->andWhere(['dcs_code' => $this->dcs_code, 'bmc_code' => $this->bmc_code, 'mcc_plant_code' => $this->mcc_plant_code, 'plant_code' => $this->plant_code]);
        } elseif (strtoupper($org_type) == 'PLANT') {
            $query->andWhere(['=', 'ISNULL(mcc_plant_code,\'\')', ''])->andWhere(['plant_code' => $this->plant_code]);
        }
        $bmc = $query->andWhere(['union_code' => $this->union_code])->all();
        return $bmc;
    }
}

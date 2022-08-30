<?php

namespace app\modules\globalmaster\models;

use Yii;
use app\modules\globalmaster\models\TblDeviceMasterMapping;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\globalmaster\models\TblMasterType;
use app\modules\organisation\models\TblDcs;
use app\modules\globalmaster\models\TblDeviceMaster;

/**
 * This is the model class for table "tbl_device_master".
 *
 * @property string $device_master_code
 * @property string $tab_type
 * @property string $sr_no
 * @property string $mac_address
 * @property string $remarks
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_type
 * @property string $originating_org_code
 * @property integer $originating_type
 */
class TblDeviceMaster extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_device_master';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['tab_type', 'sr_no', 'mac_address'], 'required'],
            [['tab_type'], function ($attribute, $params) {
                    Yii::$app->general->validateGlobalStatic($this, $attribute, 'tab_type');
                }, 'on' => 'importCsv'],
            [['tab_type'], 'integer', 'on' => 'importCsv'],
            [['created_at', 'updated_at', 'device_master_code', 'collection_center_type', 'collection_center_code', 'eff_date', 'sr_no'], 'safe'],
            [['originating_type'], 'integer'],
            [['originating_org_type', 'originating_org_code'], 'string', 'max' => 25],
            [['sr_no', 'created_by', 'updated_by'], 'string', 'max' => 20],
            [['mac_address', 'remarks'], 'string', 'max' => 250],
            [['sr_no', 'mac_address'], 'unique', 'message' => Yii::t('app/validation', '{attribute} has already been taken.')],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'device_master_code' => Yii::t('app', 'Device Master Code'),
            'tab_type' => Yii::t('app', 'Tab Type'),
            'sr_no' => Yii::t('app', 'Sr No'),
            'mac_address' => Yii::t('app', 'Mac Address'),
            'remarks' => Yii::t('app', 'Remarks'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_type' => Yii::t('app', 'Originating Type'),
        ];
    }

    public function DeviceList() {
        $data = $this->find()
                ->distinct()
                ->all();
        $array = \yii\helpers\ArrayHelper::map($data, 'device_master_code', function ($data) {
                    $type = Yii::$app->dropdown->getRecords('tab_type')['data'][$data->tab_type];
                    return $type . '(' . $data->mac_address . ')';
                });
        return $array;
    }

    public function disableDelete() {
        $app = TblDeviceMasterMapping::find()->where(['device_master_code' => $this->device_master_code])->one();
        if (!empty($app))
            return false;
        else
            return true;
    }

}

<?php

namespace app\modules\installation\models;
use app\modules\organisation\models\TblDcs;
use app\modules\organisation\models\TblUnions;



use Yii;

/**
 * This is the model class for table "dpu_station_detail".
 *
 * @property integer $id
 * @property string $station_code
 * @property string $flag_key
 * @property integer $flag_value
 * @property string $other_value
 * @property string $vendor_code
 * @property string $company_code
 * @property string $created_at
 * @property string $created_by
 * @property string $transferred_datetime
 * @property string $transferred_by
 * @property string $download_datetime
 * @property string $download_by
 * @property string $ref_code
 * @property string $dpu_header
 */
class DpuStationDetail extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dpu_station_detail';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['station_code', 'flag_key', 'vendor_code', 'company_code'], 'required','except' => ['forcesent']],
            [['flag_value'], 'integer'],
            [['created_at', 'transferred_datetime', 'download_datetime'], 'safe'],
            [['station_code'], 'string', 'max' => 16],
            [['flag_key', 'ref_code'], 'string', 'max' => 20],
            [['other_value'], 'string', 'max' => 50],
            [['vendor_code'], 'string', 'max' => 7],
            [['company_code'], 'string', 'max' => 3],
            [['created_by', 'transferred_by', 'download_by'], 'string', 'max' => 25],
            [['dpu_header'], 'string', 'max' => 204],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'station_code' => Yii::t('app', 'Station Code'),
            'flag_key' => Yii::t('app', 'Flag Key'),
            'flag_value' => Yii::t('app', 'Flag Value'),
            'other_value' => Yii::t('app', 'Other Value'),
            'vendor_code' => Yii::t('app', 'Vendor Code'),
            'company_code' => Yii::t('app', 'Company Code'),
            'created_at' => Yii::t('app', 'Created Date'),
            'created_by' => Yii::t('app', 'Created By'),
            'transferred_datetime' => Yii::t('app', 'Transferred Date'),
            'transferred_by' => Yii::t('app', 'Transferred By'),
            'download_datetime' => Yii::t('app', 'Download Datetime'),
            'download_by' => Yii::t('app', 'Download By'),
            'ref_code' => Yii::t('app', 'Ref Code'),
            'dpu_header' => Yii::t('app', 'Dpu Header'),
        ];
    }
    
    public function getDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'ref_code']);
    }
    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'company_code']);
    }
}

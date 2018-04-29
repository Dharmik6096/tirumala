<?php

namespace app\modules\hardwareconfigutation\models;

use Yii;
use app\modules\organisation\models\TblUnions;
/**
 * This is the model class for table "tbl_union_config".
 *
 * @property string $union_config_code
 * @property double $perc_disp_recp_milk
 * @property integer $min_member_age
 * @property integer $manual_days_collection
 * @property integer $audit_response_time
 * @property double $auto_audit_resolution
 * @property double $range_end
 * @property integer $is_delete
 * @property integer $is_active
 * @property string $created_by
 * @property string $created_at
 * @property string $updated_by
 * @property string $updated_at
 * @property string $deleted_by
 * @property string $deleted_at
 * @property string $flg_sentbox_entry
 * @property string $sync_status
 * @property string $sync_timestamp
 * @property string $union_code
 */
class TblUnionConfig extends \app\models\ChildModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_union_config';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['union_config_code','perc_disp_recp_milk','auto_audit_resolution','audit_response_time','range_end','manual_days_collection','min_member_age'], 'required'],
            //[['perc_disp_recp_milk', 'auto_audit_resolution', 'range_end'], 'number'],
            [['perc_disp_recp_milk', 'range_end'], 'match', 'pattern' => '/^\d{1,4}+(?:\.\d{1,2})?$/', 'message' => 'Please enter valid {attribute}. e.g "5" OR "5.5"'],
//            [['min_member_age',], 'match', 'pattern' => '/^[1-9][0-9]*$/', 'message' => 'Please enter valid {attribute}. e.g "25"'],
            [['min_member_age','manual_days_collection','audit_response_time','auto_audit_resolution'], function ($attribute, $params) {
                    Yii::$app->general->vaildateNumericField($this, $attribute,$params);
                },'skipOnEmpty'=> false],
            //[['min_member_age'], 'integer','message'=> Yii::t('app/validation', '{attribute} must be a digit. e.g. "01"')],
            [['perc_disp_recp_milk', 'auto_audit_resolution', 'range_end'], 'safe'],
            [['created_at', 'updated_at', 'deleted_at', 'sync_timestamp','union_code', 'is_delete', 'is_active'], 'safe'],
            [['union_config_code'], 'string', 'max' => 11],
            [['created_by', 'updated_by', 'deleted_by'], 'string', 'max' => 14],
            [['flg_sentbox_entry', 'sync_status'], 'string', 'max' => 1],
            [['union_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblUnions::className(), 'targetAttribute' => ['union_code' => 'union_code']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'union_config_code' => Yii::t('app', 'Union Config Code'),
            'perc_disp_recp_milk' => Yii::t('app', 'Perc Disp Recp Milk'),
            'min_member_age' => Yii::t('app', 'Minimum Member Age'),
            'manual_days_collection' => Yii::t('app', 'Manual Days Collection'),
            'audit_response_time' => Yii::t('app', 'Audit Response Time'),
            'auto_audit_resolution' => Yii::t('app', 'Auto Audit Resolution'),
            'range_end' => Yii::t('app', 'Range End'),
            'is_delete' => Yii::t('app', 'Is Delete'),
            'is_active' => Yii::t('app', 'Is Active'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'deleted_by' => Yii::t('app', 'Deleted By'),
            'deleted_at' => Yii::t('app', 'Deleted At'),
            'flg_sentbox_entry' => Yii::t('app', 'Flg Sentbox Entry'),
            'sync_status' => Yii::t('app', 'Sync Status'),
            'sync_timestamp' => Yii::t('app', 'Sync Timestamp'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUnionCode()
    {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }
    
    /**
     * @inheritdoc
     * @return TblUnionConfigQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblUnionConfigQuery(get_called_class());
    }
    
    public function getCode(){
        $orgCode = (Yii::$app->session->get('organizations_type')=='UNION')?Yii::$app->session->get('organizations_code'):'000';
	
        $len = strlen($orgCode);
	$val = (new \yii\db\Query)
                ->select("MAX(CAST(trim(SUBSTRING(`union_config_code` FROM ".$len." +1)) AS UNSIGNED)) as union_config_code")
                ->from('tbl_union_config')
                ->where('(CAST(trim(SUBSTRING(union_config_code, 1,'.$len.')) AS UNSIGNED))="'.trim($orgCode).'"')
                ->one();
        $code1 = (int)$val['union_config_code'] + 1 ;
        
	$value = $orgCode.$code1;
        
        return $value;
    }
}

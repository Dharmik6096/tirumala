<?php

namespace app\modules\vsp\models;

use Yii;

/**
 * This is the model class for table "tbl_head_load_applicability_history".
 *
 * @property integer $id
 * @property string $code
 * @property string $created_at
 * @property string $flg_sentbox_entry
 * @property string $history_created_at
 * @property integer $is_delete
 * @property string $operation_type
 * @property string $sync_status
 * @property string $sync_timestamp
 * @property string $updated_at
 * @property string $wef_date
 * @property string $created_by
 * @property string $dcs_code
 * @property string $head_load_code
 * @property string $sub_center_code
 * @property string $updated_by
 *
 * @property User $createdBy
 * @property TblDcs $dcsCode
 * @property User $deletedBy
 * @property TblHeadLoad $headLoadCode
 * @property TblSubCenter $subCenterCode
 * @property User $updatedBy
 */
class TblHeadLoadApplicabilityHistory extends \yii\db\ActiveRecord {

    public $product_group_id;
    public $language_code;
    public $local_code;
    public $local_name;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_head_load_applicability_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['dcs_code', 'head_load_code', 'sub_center_code', 'code', 'created_at', 'is_delete', 'operation_type', 'created_by', 'updated_by', 'flg_sentbox_entry', 'sync_status', 'history_created_at', 'sync_timestamp', 'updated_at', 'wef_date', 'shift_code', 'shift_for','union_code'], 'safe'],
                //[['is_delete'], 'integer'],
//            [['flg_sentbox_entry', 'sync_status'], 'string', 'max' => 1],
//            [['operation_type'], 'string', 'max' => 10],
//            [['created_by',  'updated_by'], 'string', 'max' => 14],
//            [['dcs_code', 'sub_center_code'], 'string', 'max' => 9],
//            [['dcs_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblDcs::className(), 'targetAttribute' => ['dcs_code' => 'dcs_code']],
//            [['head_load_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblHeadLoad::className(), 'targetAttribute' => ['head_load_code' => 'head_load_code']],
//            [['sub_center_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblSubCenter::className(), 'targetAttribute' => ['sub_center_code' => 'sub_center_code']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'code' => Yii::t('app', 'Code'),
            'created_at' => Yii::t('app', 'Created At'),
            'flg_sentbox_entry' => Yii::t('app', 'Flg Sentbox Entry'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'is_delete' => Yii::t('app', 'Is Delete'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'sync_status' => Yii::t('app', 'Sync Status'),
            'sync_timestamp' => Yii::t('app', 'Sync Timestamp'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'wef_date' => Yii::t('app', 'Wef Date'),
            'created_by' => Yii::t('app', 'Created By'),
            'dcs_code' => Yii::t('app', 'Dcs Code'),
            'head_load_code' => Yii::t('app', 'Head Load Code'),
            'sub_center_code' => Yii::t('app', 'Sub Center Code'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy() {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'dcs_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHeadLoadCode() {
        return $this->hasOne(TblHeadLoad::className(), ['head_load_code' => 'head_load_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubCenterCode() {
        return $this->hasOne(TblSubCenter::className(), ['sub_center_code' => 'sub_center_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy() {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

    /**
     * @inheritdoc
     * @return TblHeadLoadApplicabilityHistoryQuery the active query used by this AR class.
     */
    public static function find() {
        return new TblHeadLoadApplicabilityHistoryQuery(get_called_class());
    }

}

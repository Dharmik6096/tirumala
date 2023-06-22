<?php

namespace app\modules\globalmaster\models;

use Yii;
use app\models\ChildModel;
use app\modules\organisation\models\TblUnions;
use app\modules\syncutility\models\TblSentbox;

/**
 * This is the model class for table "tbl_units".
 *
 * @property integer $unit_code
 * @property string $created_at
 * @property integer $is_active
 * @property string $unit_name
 * @property string $local_name
 * @property string $short_name
 * @property string $local_short_name
 * @property string $updated_at
 * @property string $created_by
 * @property string $updated_by
 *
 * @property TblUsers $updatedBy
 * @property TblUsers $createdBy
 * @property TblUsers $deletedBy
 */
class TblUnits extends ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_units';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['created_at', 'updated_at', 'short_name'], 'safe'],
            [['is_active'], 'safe'],
            [['unit_name', 'union_code'], 'required'],
//                [['unit_name', 'short_name'], 'unique'],
            [['unit_name'], 'unique', 'targetAttribute' => ['unit_name', 'union_code'], 'message' => Yii::t('app/validation', '{attribute} has already been taken.')],
            [['short_name'], 'unique', 'targetAttribute' => ['short_name', 'union_code'], 'message' => Yii::t('app/validation', '{attribute} has already been taken.')],
            [['unit_name', 'short_name'], function ($attribute, $params) {
            Yii::$app->general->validateName($this, $attribute, $params);
        }, 'skipOnEmpty' => false],
            [['unit_name'], 'string', 'max' => 20],
            [['local_name', 'local_short_name'], function ($attribute, $params) {
            Yii::$app->general->vaildateLocalField($this, $attribute, $params);
        }, 'skipOnEmpty' => false],
            [['created_by', 'updated_by'], 'string', 'max' => 14],
            [['union_code', 'originating_org_code', 'originating_org_type', 'originating_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
            [['union_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblUnions::className(), 'targetAttribute' => ['union_code' => 'union_code']],
                //     [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => TblUsers::className(), 'targetAttribute' => ['updated_by' => 'user_id']],
                //    [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => TblUsers::className(), 'targetAttribute' => ['created_by' => 'user_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'unit_code' => Yii::t('app', 'Unit Code'),
            'created_at' => Yii::t('app', 'Created At'),
            'is_active' => Yii::t('app', 'Is Active'),
            'unit_name' => Yii::t('app', 'Unit Name'),
            'local_name' => Yii::t('app', 'Local Name'),
            'short_name' => Yii::t('app', 'Short Name'),
            'local_short_name' => Yii::t('app', 'Local Short Name'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'union_code' => Yii::t('app', 'Union'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    /*    public function getUpdatedBy()
      {
      return $this->hasOne(TblUsers::className(), ['user_id' => 'updated_by']);
      }
     */
    /**
     * @return \yii\db\ActiveQuery
     */
    /*   public function getCreatedBy()
      {
      return $this->hasOne(TblUsers::className(), ['user_id' => 'created_by']);
      }
     */
    /**
     * @return \yii\db\ActiveQuery
     */
    /*   public function getDeletedBy()
      {
      return $this->hasOne(TblUsers::className());
      }
     */

    /**
     * @inheritdoc
     * @return TblUnitsQuery the active query used by this AR class.
     */
    public static function find() {
        return new TblUnitsQuery(get_called_class());
    }

    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function afterSave($insert, $changedAttributes) {
        $sentboxArray = [];
        $sentboxArray = Yii::$app->general->getSentBoxCodes('', '', '', $this->union_code);
        foreach ($sentboxArray as $sent) {
            $flag = (((isset($this->operation) && $this->operation == true)) ? $this->operation : ($insert)) ? 'INSERT' : 'UPDATE';
            $sentbox = $this->sentboxModel($sent['code'], $sent['type']);
            if (!isset($this->is_sentbox) || (isset($this->is_sentbox) && $this->is_sentbox === TRUE)) {
                if (!($sentbox->setSentbox($this, $flag))) {
                    throw new UserException("SentBox Entry is not created so transaction is rollback!");
                }
            }
        }
    }

    public function afterDelete() {
        $sentboxArray = [];
        $sentboxArray = Yii::$app->general->getSentBoxCodes('', '', '', $this->union_code);
        foreach ($sentboxArray as $sent) {
            $sentbox = $this->sentboxModel($sent['code'], $sent['type']);
            if (!isset($this->is_sentbox) || (isset($this->is_sentbox) && $this->is_sentbox === TRUE)) {
                if (!($sentbox->setSentbox($this, 'DELETE'))) {
                    throw new UserException("SentBox Entry is not created so transaction is rollback!");
                }
            }
        }
    }

    private function sentboxModel($code, $type) {
        $sentbox = new TblSentbox();
        $sentbox->dest_org_id = $code;
        $sentbox->source_org_id = $this->union_code;
        $sentbox->dest_org_type = $type;
        return $sentbox;
    }

}

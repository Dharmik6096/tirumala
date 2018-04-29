<?php

namespace app\modules\general\models;

use Yii;

/**
 * This is the model class for table "tbl_relationship".
 *
 * @property integer $relationship_code
 * @property string $relationship
 * @property integer $is_active
 */
class TblRelationship extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_relationship';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['relationship_code'], 'required'],
            [['relationship_code', 'is_active'], 'integer'],
            [['relationship'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'relationship_code' => Yii::t('app', 'Relationship Code'),
            'relationship' => Yii::t('app', 'Relationship'),
            'is_active' => Yii::t('app', 'Is Active'),
        ];
    }

    /**
     * @inheritdoc
     * @return TblRelationshipQuery the active query used by this AR class.
     */
    public static function find() {
        return new TblRelationshipQuery(get_called_class());
    }

    public function getRelationship($relationship) {
        return $this->find()->where(['relationship_code' => $relationship])->count();
    }

}

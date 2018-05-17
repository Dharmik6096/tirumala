<?php

namespace app\modules\organisation\models;

use Yii;

/**
 * This is the model class for table "tbl_cluster".
 *
 * @property string $cluster_code
 * @property string $plant_code
 * @property string $name
 * @property string $local_name
 * @property string $address
 * @property string $local_address
 * @property string $description
 * @property integer $is_active
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $union_code
 *
 * @property TblUnions $unionCode
 * @property TblPlant $plantCode
 */
class TblCluster extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_cluster';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['cluster_code', 'address', 'plant_code', 'name'], 'required'],
            [['cluster_code', 'plant_code', 'name', 'local_name', 'address', 'local_address', 'description', 'created_by', 'updated_by', 'union_code'], 'string'],
            [['is_active'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['union_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblUnions::className(), 'targetAttribute' => ['union_code' => 'union_code']],
            [['plant_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblPlant::className(), 'targetAttribute' => ['plant_code' => 'plant_code']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'cluster_code' => Yii::t('app', 'Cluster Code'),
            'plant_code' => Yii::t('app', 'Plant'),
            'name' => Yii::t('app', 'Cluster Name'),
            'local_name' => Yii::t('app', 'Hindi Name'),
            'address' => Yii::t('app', 'Address'),
            'local_address' => Yii::t('app', 'Hindi Address'),
            'description' => Yii::t('app', 'Description'),
            'is_active' => Yii::t('app', 'Is Active'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'union_code' => Yii::t('app', 'Union'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlantCode() {
        return $this->hasOne(TblPlant::className(), ['plant_code' => 'plant_code']);
    }

    public function getCode() {
        $data = $this->find()->select(["MAX(CONVERT(bigint,cluster_code)) as cluster_code"])->one();
        return str_pad(((int) $data['cluster_code'] + 1), 6, '0', STR_PAD_LEFT);
    }

}

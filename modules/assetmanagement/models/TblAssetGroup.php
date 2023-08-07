<?php

namespace app\modules\assetmanagement\models;

use Yii;
use app\modules\organisation\models\TblUnions;

/**
 * This is the model class for table "tbl_asset_group".
 *
 * @property string $asset_group_code
 * @property string $asset_group_name
 * @property integer $is_active
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $local_name
 */
class TblAssetGroup extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_asset_group';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['asset_group_name', 'union_code'], 'required'],
            [['asset_group_name'], function ($attribute, $params) {
                    Yii::$app->general->validateName($this, $attribute, $params);
                }, 'skipOnEmpty' => false,],
            [['reference_code'], 'unique'],
            [['asset_group_name', 'created_by', 'updated_by'], 'string'],
            [['is_active'], 'default', 'value' => 1],
            [['created_at', 'updated_at', 'reference_code'], 'safe'],
            [['local_name'], function ($attribute, $params) {
                    Yii::$app->general->vaildateLocalField($this, $attribute, $params);
                }, 'skipOnEmpty' => false],
            [['union_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblUnions::className(), 'targetAttribute' => ['union_code' => 'union_code']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'asset_group_code' => Yii::t('app', 'Asset Group Code'),
            'asset_group_name' => Yii::t('app', 'Asset Group Name'),
            'is_active' => Yii::t('app', 'Is Active'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'local_name' => Yii::t('app', 'Local Name'),
            'union_code' => Yii::t('app', 'Union'),
            'reference_code' => Yii::t('app', 'SAP Code'),
        ];
    }

    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

}

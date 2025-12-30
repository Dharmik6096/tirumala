<?php

namespace app\modules\assetmanagement\models;

use Yii;

/**
 * This is the model class for table "tbl_asset_type".
 *
 * @property integer $asset_type_code
 * @property string $asset_type_name
 */
class TblAssetType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_asset_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['asset_type_code'], 'required'],
            [['asset_type_code'], 'integer'],
            [['asset_type_name'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'asset_type_code' => Yii::t('app', 'Asset Type Code'),
            'asset_type_name' => Yii::t('app', 'Asset Type Name'),
        ];
    }
}

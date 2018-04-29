<?php

namespace app\modules\hardwareconfigutation\models;

/**
 * This is the ActiveQuery class for [[TblDeviceManufacturer]].
 *
 * @see TblDeviceManufacturer
 */
class TblDeviceManufacturerQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblDeviceManufacturer[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblDeviceManufacturer|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}

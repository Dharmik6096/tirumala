<?php

namespace app\modules\hardwareconfigutation\models;

/**
 * This is the ActiveQuery class for [[TblInterfacingDevice]].
 *
 * @see TblInterfacingDevice
 */
class TblInterfacingDeviceQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblInterfacingDevice[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblInterfacingDevice|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}

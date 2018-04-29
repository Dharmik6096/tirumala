<?php

namespace app\modules\organisation\models;

/**
 * This is the ActiveQuery class for [[TblVehicleMaster]].
 *
 * @see TblVehicleMaster
 */
class TblVehicleMasterQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblVehicleMaster[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblVehicleMaster|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}

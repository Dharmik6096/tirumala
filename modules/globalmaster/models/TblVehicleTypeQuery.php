<?php

namespace app\modules\globalmaster\models;

/**
 * This is the ActiveQuery class for [[TblVehicleType]].
 *
 * @see TblVehicleType
 */
class TblVehicleTypeQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblVehicleType[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblVehicleType|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}

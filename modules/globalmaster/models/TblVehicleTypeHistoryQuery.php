<?php

namespace app\modules\globalmaster\models;

/**
 * This is the ActiveQuery class for [[TblVehicleTypeHistory]].
 *
 * @see TblVehicleTypeHistory
 */
class TblVehicleTypeHistoryQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblVehicleTypeHistory[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblVehicleTypeHistory|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}

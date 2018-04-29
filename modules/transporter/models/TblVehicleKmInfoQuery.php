<?php

namespace app\modules\transporter\models;

/**
 * This is the ActiveQuery class for [[TblVehicleKmInfo]].
 *
 * @see TblVehicleKmInfo
 */
class TblVehicleKmInfoQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblVehicleKmInfo[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblVehicleKmInfo|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}

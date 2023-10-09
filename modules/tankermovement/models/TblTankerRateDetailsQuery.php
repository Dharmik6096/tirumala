<?php

namespace app\modules\tankermovement\models;

/**
 * This is the ActiveQuery class for [[TblTankerRateAuto]].
 *
 * @see TblTankerRateAuto
 */
class TblTankerRateDetailsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblTankerRateAuto[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblTankerRateAuto|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}

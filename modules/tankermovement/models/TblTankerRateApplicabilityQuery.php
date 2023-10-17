<?php

namespace app\modules\tankermovement\models;

/**
 * This is the ActiveQuery class for [[TblTankerRateApplicability]].
 *
 * @see TblTankerRateApplicability
 */
class TblTankerRateApplicabilityQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblTankerRateApplicability[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblTankerRateApplicability|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}

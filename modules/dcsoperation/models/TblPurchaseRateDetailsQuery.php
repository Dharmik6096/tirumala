<?php

namespace app\modules\dcsoperation\models;

/**
 * This is the ActiveQuery class for [[TblPurchaseRateAuto]].
 *
 * @see TblPurchaseRateAuto
 */
class TblPurchaseRateDetailsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblPurchaseRateAuto[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblPurchaseRateAuto|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}

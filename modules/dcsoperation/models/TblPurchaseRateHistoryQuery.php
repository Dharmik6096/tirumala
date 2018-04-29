<?php

namespace app\modules\dcsoperation\models;

/**
 * This is the ActiveQuery class for [[TblPurchaseRateHistory]].
 *
 * @see TblPurchaseRateHistory
 */
class TblPurchaseRateHistoryQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblPurchaseRateHistory[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblPurchaseRateHistory|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}

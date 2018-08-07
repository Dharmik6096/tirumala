<?php

namespace app\modules\dcsoperation\models;

/**
 * This is the ActiveQuery class for [[TblDcsPurchaseRateHistory]].
 *
 * @see TblDcsPurchaseRateHistory
 */
class TblDcsPurchaseRateHistoryQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblDcsPurchaseRateHistory[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblDcsPurchaseRateHistory|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}

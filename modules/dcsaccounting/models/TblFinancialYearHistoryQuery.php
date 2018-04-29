<?php

namespace app\modules\dcsaccounting\models;

/**
 * This is the ActiveQuery class for [[TblFinancialYearHistory]].
 *
 * @see TblFinancialYearHistory
 */
class TblFinancialYearHistoryQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblFinancialYearHistory[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblFinancialYearHistory|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}

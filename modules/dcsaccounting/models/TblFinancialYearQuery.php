<?php

namespace app\modules\dcsaccounting\models;

/**
 * This is the ActiveQuery class for [[TblFinancialYear]].
 *
 * @see TblFinancialYear
 */
class TblFinancialYearQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblFinancialYear[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblFinancialYear|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}

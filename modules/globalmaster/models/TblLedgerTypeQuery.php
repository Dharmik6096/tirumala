<?php

namespace app\modules\globalmaster\models;
/**
 * This is the ActiveQuery class for [[TblLedgerType]].
 *
 * @see TblLedgerType
 */
class TblLedgerTypeQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblLedgerType[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblLedgerType|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}

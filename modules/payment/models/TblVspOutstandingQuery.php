<?php

namespace app\modules\payment\models;

/**
 * This is the ActiveQuery class for [[TblVspOutstanding]].
 *
 * @see TblVspOutstanding
 */
class TblVspOutstandingQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblVspOutstanding[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblVspOutstanding|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}

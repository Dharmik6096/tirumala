<?php

namespace app\modules\globalmaster\models;

/**
 * This is the ActiveQuery class for [[TblMiscellaneous]].
 *
 * @see TblMiscellaneous
 */
class TblMiscellaneousQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblMiscellaneous[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblMiscellaneous|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}

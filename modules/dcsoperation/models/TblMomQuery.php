<?php

namespace app\modules\dcsoperation\models;

/**
 * This is the ActiveQuery class for [[TblMom]].
 *
 * @see TblMom
 */
class TblMomQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblMom[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblMom|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}

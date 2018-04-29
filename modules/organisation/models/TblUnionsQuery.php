<?php

namespace app\modules\organisation\models;

/**
 * This is the ActiveQuery class for [[TblUnions]].
 *
 * @see TblUnions
 */
class TblUnionsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblUnions[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblUnions|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}

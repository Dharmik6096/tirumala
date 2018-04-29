<?php

namespace app\modules\organisation\models;

/**
 * This is the ActiveQuery class for [[TblFederations]].
 *
 * @see TblFederations
 */
class TblFederationsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblFederations[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblFederations|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}

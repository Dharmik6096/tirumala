<?php

namespace app\modules\organisation\models;

/**
 * This is the ActiveQuery class for [[TblFederationsStateMapping]].
 *
 * @see TblFederationsStateMapping
 */
class TblFederationsStateMappingQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblFederationsStateMapping[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblFederationsStateMapping|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}

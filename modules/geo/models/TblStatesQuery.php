<?php

namespace app\modules\geo\models;

/**
 * This is the ActiveQuery class for [[TblStates]].
 *
 * @see TblStates
 */
class TblStatesQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblStates[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblStates|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}

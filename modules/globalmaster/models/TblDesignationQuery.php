<?php

namespace app\modules\globalmaster\models;
/**
 * This is the ActiveQuery class for [[TblDesignation]].
 *
 * @see TblDesignation
 */
class TblDesignationQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblDesignation[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblDesignation|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}

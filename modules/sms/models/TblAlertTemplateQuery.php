<?php

namespace app\modules\sms\models;

/**
 * This is the ActiveQuery class for [[TblAlertTemplate]].
 *
 * @see TblAlertTemplate
 */
class TblAlertTemplateQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return TblAlertTemplate[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return TblAlertTemplate|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}

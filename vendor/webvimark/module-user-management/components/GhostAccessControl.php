<?php

namespace webvimark\modules\UserManagement\components;

use webvimark\modules\UserManagement\models\rbacDB\Route;
use webvimark\modules\UserManagement\models\User;
use yii\base\Action;
use Yii;
use yii\base\ActionFilter;
use yii\web\ForbiddenHttpException;

class GhostAccessControl extends ActionFilter
{
	/**
	 * @var callable a callback that will be called if the access should be denied
	 * to the current user. If not set, [[denyAccess()]] will be called.
	 *
	 * The signature of the callback should be as follows:
	 *
	 * ~~~
	 * function ($rule, $action)
	 * ~~~
	 *
	 * where `$rule` is the rule that denies the user, and `$action` is the current [[Action|action]] object.
	 * `$rule` can be `null` if access is denied because none of the rules matched.
	 */
	public $denyCallback;

        private function isRecordSecurity($action){
          /*  if($action->id=='delete' || $action->id=='update'){
                                    $controllerName= $action->controller->id;
                    $controllerName=str_replace('-',' ',$controllerName);
                    $modelName=  ucwords($controllerName);
                    $modelName=str_replace(' ','',$modelName);
                    if($modelName=='Permission' || $modelName=='Role' || $modelName=='AuthItemGroup' || $modelName=='User')
                        return true;
                 /*   if($modelName=='User')
                        $path ='\\webvimark\modules\UserManagement\models\\';*/
              /*      else
                        $path='\\app\models\\';
                    $modelName=$path.$modelName;
                    $model=new $modelName();
                    $data=$model->findone(Yii::$app->getRequest()->getQueryParam('id'));
                    if($data){

                        $map=new \app\models\TblUserOrganizationMapping();
                        $mapData=$map->find()->where(['userId'=>$data->created_by])->all();
                        $organisation=  explode(',',Yii::$app->session->get('Organizations'));
                        foreach ($mapData as $m){
                            if(in_array($m->organizationCode,$organisation))
                                return true;
                        }
                        return FALSE;

                    }
                    return false;
            }*/
            return true;
        }



        /**
	 * Check if user has access to current route
	 *
	 * @param Action $action the action to be executed.
	 *
	 * @return boolean whether the action should continue to be executed.
	 */
	public function beforeAction($action)
	{
		if ( $action->id == 'captcha' )
		{
			return true;
		}

		$route = '/' . $action->uniqueId;

		if ( Route::isFreeAccess($route, $action) )
		{
			//return true;
                    if($this->isRecordSecurity($action))
                            return true;
                        $this->denyAccess();
		}

		if ( Yii::$app->user->isGuest )
		{
			$this->denyAccess();
		}

		// If user has been deleted, then destroy session and redirect to home page
		if ( ! Yii::$app->user->isGuest AND Yii::$app->user->identity === null )
		{
			Yii::$app->getSession()->destroy();
			$this->denyAccess();
		}

		// Superadmin owns everyone
		if ( Yii::$app->user->isSuperadmin )
		{
			if($this->isRecordSecurity($action))
                            return true;
                        $this->denyAccess();
		}

		if ( Yii::$app->user->identity AND Yii::$app->user->identity->status != User::STATUS_ACTIVE)
		{
			Yii::$app->user->logout();
			Yii::$app->getResponse()->redirect(Yii::$app->getHomeUrl());
		}

		if ( User::canRoute($route) )
		{
                    if($this->isRecordSecurity($action))
                        return true;
                    $this->denyAccess();
		}

		if ( isset($this->denyCallback) )
		{

			call_user_func($this->denyCallback, null, $action);
		}
		else
		{
                    $this->denyAccess();
		}

		return false;
	}


	/**
	 * Denies the access of the user.
	 * The default implementation will redirect the user to the login page if he is a guest;
	 * if the user is already logged, a 403 HTTP exception will be thrown.
	 *
	 * @throws ForbiddenHttpException if the user is already logged in.
	 */
	protected function denyAccess()
	{
		if ( Yii::$app->user->getIsGuest() )
		{
			if (isset($_SERVER['HTTP_USER_AGENT']) && (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false)){
				Yii::$app->user->loginRequired(Yii::$app->request->isAjax, (strpos(Yii::$app->request->headers->get('Accept'), 'html') !== false));
			} else {
				Yii::$app->user->loginRequired();
			}

		}
		else
		{
			throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
		}
	}

}

<?php
namespace common\components;
use yii\filters\auth\QueryParamAuth;

class MoetQueryParamAuth extends QueryParamAuth
{
    public $optional = ['login','request-password-reset','reset-password'];

    public function authenticate($user, $request, $response)
    {
        $headers = $request->headers;
        $accessToken = $headers->get('authToken');
        if (is_string($accessToken)) {
            $identity = $user->loginByAccessToken($accessToken, get_class($this));	
            if ($identity !== null && isset($identity->status) && $identity->status === 1) { // check user is active
                return $identity;
            }
        }
        if ($accessToken !== null) {
            $this->handleFailure($response);
        }

        return null;
    }
}
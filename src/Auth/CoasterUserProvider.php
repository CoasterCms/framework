<?php namespace CoasterCms\Auth;

use Illuminate\Auth\EloquentUserProvider;

class CoasterUserProvider extends EloquentUserProvider
{

    /**
     * Add active flag check to authentication provider
     *
     * @param  \Illuminate\Database\Eloquent\Model|null  $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function newModelQuery($model = null)
    {
        return parent::newModelQuery($model)->where('active', '=', 1);
    }

    /**
     * Fallback code to rename username input to email
     *
     * @param array $credentials
     * @return \Illuminate\Database\Eloquent\Model|null|static
     * @throws \ErrorException
     */
    public function retrieveByCredentials(array $credentials)
    {
        if (array_key_exists('username', $credentials)) {
            $credentials['email'] = $credentials['username'];
            unset($credentials['username']);
        }
        return parent::retrieveByCredentials($credentials);
    }

}

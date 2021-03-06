<?php
/**
 * Copyright 2010 - 2015, Cake Development Corporation (http://cakedc.com)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright 2010 - 2015, Cake Development Corporation (http://cakedc.com)
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

namespace CakeDC\Users\Model\Behavior;

use Cake\Datasource\EntityInterface;
use Cake\I18n\Time;
use Cake\ORM\Behavior as BaseBehavior;

/**
 * Covers the user registration
 */
class Behavior extends BaseBehavior
{
    /**
     * DRY for update active and token based on validateEmail flag
     *
     * @param EntityInterface $user User to be updated.
     * @param bool $validateEmail email user to validate.
     * @param type $tokenExpiration token to be updated.
     * @return EntityInterface
     */
    protected function _updateActive(EntityInterface $user, $validateEmail, $tokenExpiration)
    {
        $emailValidated = $user['validated'];
        if (!$emailValidated && $validateEmail) {
            $user['active'] = false;
            $user->updateToken($tokenExpiration);
        } else {
            $user['active'] = true;
            $user['activation_date'] = new Time();
        }

        return $user;
    }

    /**
     * Remove user token for validation
     *
     * @param EntityInterface $user user object.
     * @return EntityInterface
     */
    protected function _removeValidationToken(EntityInterface $user)
    {
        $user->token = null;
        $user->token_expires = null;
        $result = $this->_table->save($user);

        return $result;
    }
}

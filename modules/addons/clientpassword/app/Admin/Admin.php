<?php

namespace LMTech\ClientPassword\Admin;

use WHMCS\User\User;
use WHMCS\Database\Capsule;
use LMTech\ClientPassword\Config\Config;
use LMTech\ClientPassword\Helpers\RedirectHelper;
use LMTech\ClientPassword\Helpers\AdminPageHelper;
use LMTech\ClientPassword\Helpers\PaginationHelper;

/**
 * WHMCS Client Password Changer
 *
 * Allows admins to change a users password manually without the need to send an email
 * to the client and reset it that way.
 *
 * @package    WHMCS
 * @author     Lee Mahoney <lee@leemahoney.dev>
 * @copyright  Copyright (c) Lee Mahoney 2022
 * @license    MIT License
 * @version    1.0.0
 * @link       https://leemahoney.dev
 */

if (!defined("WHMCS")) {
    exit("This file cannot be accessed directly");
}

class Admin {

    public static function output($vars) {

        $passThru = [
            'moduleLink'        => $vars['modulelink'],
            'updateAvailable'   => Config::checkForUpdate(),
            'alert'             => [],
            'formData'          => [],
        ];

        if(AdminPageHelper::getCurrentPage() == 'dashboard') {

            $where = [];

            if (isset($_POST['search']) && !empty($_POST['search'])) {

                $query = htmlentities($_POST['search']);
                $where = [
                    [Capsule::raw('concat(`first_name`, " ", `last_name`)'), 'like', '%' . $query . '%'],
                    ['email', 'like', '%' . $query . '%'],
                ];

            }

            $pagination = new PaginationHelper('p', [], Config::get('paginationLimit'), \WHMCS\User\User::class, [], $where, [Config::get('paginationSortOrder'), Config::get('paginationSortField')]);

            $passThru['users']              = $pagination->data();
            $passThru['paginationLinks']    = $pagination->links();

        }

        if (AdminPageHelper::getCurrentPage() == 'change') {
            
            $id = (int) AdminPageHelper::getAttribute('id');

            $user = User::where('id', $id)->first();

            if (!count($user)) {
                RedirectHelper::page('dashboard', ['error' => 'nouser']);
            }

            $passThru['user'] = $user;

            if (isset($_POST['newPw']) && !empty($_POST['newPw'])) {

                User::where('id', $user->id)->first()->updatePassword(trim($_POST['newPw']));

                if (Config::get('enableLogging')) {
                    foreach ($user->ownedClients() as $client) {
                        logActivity("User password manually changed by admin (User ID: {$user->id})", $client->id);
                    }
                }

                RedirectHelper::page('dashboard', ['success' => 'changed']);

            } else if(isset($_POST['newPw']) && empty($_POST['newPw'])) {

                $passThru['alert'] = [
                    'type'      => 'error',
                    'message'   => 'Please enter a password'
                ];

            }

            $passThru['generatorScript'] = '
                <script type="text/javascript">
                    function generatePassword() {
                        var length  = ' . Config::get('passwordLength') . ',
                            charset = "abcdefghijklmnopqrstuvwxyz",
                            retVal  = "";
            
                        ' . (Config::get('capitalLetters') ? 'charset += "ABCDEFGHIJKLMNOPQRSTUVWXYZ";' : '') . '
                        ' . (Config::get('numbers') ? 'charset += "0123456789";' : '') . '
                        ' . (Config::get('specialChars') ? 'charset += "+-*/$£%!&";' : '') . '

                        for (var i = 0, n = charset.length; i < length; ++i) {
                            retVal += charset.charAt(Math.floor(Math.random() * n));
                        }
            
                        document.getElementById("newPw").setAttribute("value", retVal);
                
                    }
                </script>
            ';

        }

        AdminPageHelper::outputPage($passThru);

    }

}
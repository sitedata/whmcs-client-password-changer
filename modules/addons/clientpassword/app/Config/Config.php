<?php

namespace LMTech\ClientPassword\Config;

use WHMCS\Module\Addon\Setting as AddonSetting;

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
 * @version    1.0.3
 * @link       https://leemahoney.dev
 */

if (!defined("WHMCS")) {
    exit("This file cannot be accessed directly");
}

class Config {

    public static function populate() {

        return [
            'name'          => 'Client Password Changer',
            'description'   => 'Easily change your clients/users passwords from within the admin area',
            'version'       => '1.0.3',
            'author'        => '<a href="https://leemahoney.dev">Lee Mahoney</a>',
            'fields'        => [
                'showButtons'           => [
                    'FriendlyName'      => 'Show Buttons on Manage Users screens',
                    'Type'              => 'yesno',
                    'Size'              => '25',
                    'Description'       => 'Whether or not to show a "Change Password" button each user in the user management screens',
                    'Default'           => 'yes',
                ],
                'enableLogging'         => [
                    'FriendlyName'      => 'Enable Logging',
                    'Type'              => 'yesno',
                    'Size'              => '25',
                    'Description'       => 'Log that the password has been manually changed on owned client accounts',
                    'Default'           => 'yes',
                ],
                'showModal'             => [
                    'FriendlyName'      => 'Show Modals',
                    'Type'              => 'yesno',
                    'Size'              => '25',
                    'Description'       => 'Show modal for password change on user management pages, if disabled you will be redirected to the module',
                    'Default'           => 'yes',
                ],
                'passwordLength'        => [
                    'FriendlyName'      => 'Generated Password Length',
                    'Type'              => 'text',
                    'Size'              => '25',
                    'Description'       => 'Length of the password generated by the password generator',
                    'Default'           => '12',
                ],
                'specialChars'          => [
                    'FriendlyName'      => 'Allow Special Characters',
                    'Type'              => 'yesno',
                    'Size'              => '25',
                    'Description'       => 'Allow special characters in the password generated by the password generator',
                    'Default'           => 'yes',
                ],
                'numbers'               => [
                    'FriendlyName'      => 'Allow Numbers',
                    'Type'              => 'yesno',
                    'Size'              => '25',
                    'Description'       => 'Allow numbers in the password generated by the password generator',
                    'Default'           => 'yes',
                ],
                'capitalLetters'        => [
                    'FriendlyName'      => 'Allow Capital Letters',
                    'Type'              => 'yesno',
                    'Size'              => '25',
                    'Description'       => 'Allow capital letters in the password generated by the password generator',
                    'Default'           => 'yes',
                ],
                'paginationLimit'       => [
                    'FriendlyName'      => 'Pagination Record Limit',
                    'Type'              => 'text',
                    'Size'              => '25',
                    'Description'       => 'Number of records to show per page in the user list on the dashboard',
                    'Default'           => '10',
                ],
                'paginationSortOrder'   => [
                    'FriendlyName'      => 'Pagination Sort Order',
                    'Type'              => 'dropdown',
                    'Options'           => 'ASC,DESC',
                    'Description'       => 'The sort order of the user list on the dashboard',
                    'Default'           => 'DESC',
                ],
                'paginationSortField'   => [
                    'FriendlyName'      => 'Pagination Sort Field',
                    'Type'              => 'dropdown',
                    'Options'           => 'id,first_name,last_name,email,last_login,created_at,updated_at',
                    'Description'       => 'The field used to sort the user list on the dashboard',
                    'Default'           => 'created_at',
                ],
                'showUpdateMessage'     => [
                    'FriendlyName'      => 'Show Update Message',
                    'Type'              => 'yesno',
                    'Size'              => '25',
                    'Description'       => 'Whether or not to show the update alert when a new version of this script is available',
                    'Default'           => 'yes',
                ],
            ],
        ];

    }

    
    public static function get($setting) {
        return AddonSetting::where('module', 'clientpassword')->where('setting', $setting)->first()->value;
    }

    public static function checkForUpdate() {
        
        $latestVersion = file_get_contents("https://raw.githubusercontent.com/leemahoney3/whmcs-client-password-changer/main/version");

        return (self::get('showUpdateMessage') && $latestVersion != self::populate()['version']) ? true : false;

    }

}
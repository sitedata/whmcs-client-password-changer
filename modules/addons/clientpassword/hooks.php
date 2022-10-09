<?php

use LMTech\ClientPassword\Config\Config;

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

require_once __DIR__ . '/vendor/autoload.php';


function add_clientpassword_buttons($vars) {

    
    if(Config::get('showButtons') && (preg_match("/\/client\/([1-9])\/users\b/", $_SERVER['REQUEST_URI']) || preg_match("/\/user\/list\b/", $_SERVER['REQUEST_URI']))) {
        
        return '
            <script>
                $(".btn-permissions").each(function (i, obj) {
                    $(this).after(\'<a href="addonmodules.php?module=clientpassword&page=change&id=\' + $(this).attr("data-user-id") + \'" class="btn btn-default">Change Password</a>\');
                });

                $(".open-modal.manage-user").each(function (i, obj) {

                    let id = $(this).attr("href").replace( /^\D+/g, "");

                    $(this).after(\'<a href="addonmodules.php?module=clientpassword&page=change&id=\' + id + \'" class="btn btn-default btn-sm">Change Pass</a>\');
                });
            </script>
        ';

    }

}

add_hook('AdminAreaFooterOutput', 1, 'add_clientpassword_buttons');
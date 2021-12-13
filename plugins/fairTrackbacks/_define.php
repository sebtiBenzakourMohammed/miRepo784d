<?php
/**
 * @brief fairTrackbacks, an antispam filter plugin for Dotclear 2
 *
 * @package Dotclear
 * @subpackage Plugins
 *
 * @copyright Olivier Meunier & Association Dotclear
 * @copyright GPL-2.0-only
 */
if (!defined('DC_RC_PATH')) {
    return;
}

$this->registerModule(
    'Fair Trackbacks',          // Name
    'Trackback validity check', // Description
    'Olivier Meunier',          // Author
    '1.1.1',                    // Version
    [
        'permissions' => 'usage,contentadmin',
        'priority'    => 200,
        'type'        => 'plugin'
    ]
);

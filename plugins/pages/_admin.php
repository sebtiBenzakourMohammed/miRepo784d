<?php
/**
 * @brief pages, a plugin for Dotclear 2
 *
 * @package Dotclear
 * @subpackage Plugins
 *
 * @copyright Olivier Meunier & Association Dotclear
 * @copyright GPL-2.0-only
 */
if (!defined('DC_CONTEXT_ADMIN')) {
    return;
}

$core->addBehavior('adminColumnsLists', ['pagesUserPref', 'adminColumnsLists']);
$core->addBehavior('adminFiltersLists', ['pagesUserPref', 'adminFiltersLists']);
$core->addBehavior('adminDashboardFavorites', ['pagesDashboard', 'pagesDashboardFavs']);
$core->addBehavior('adminUsersActionsHeaders', 'pages_users_actions_headers');

class pagesUserPref
{
    public static function adminColumnsLists($core, $cols)
    {
        // Set optional columns in pages lists
        $cols['pages'] = [__('Pages'), [
            'date'       => [true, __('Date')],
            'author'     => [true, __('Author')],
            'comments'   => [true, __('Comments')],
            'trackbacks' => [true, __('Trackbacks')]
        ]];
    }

    public static function adminFiltersLists($core, $sorts)
    {
        $sorts['pages'] = [
            __('Pages'),
            null,
            null,
            null,
            [__('entries per page'), 30]
        ];
    }
}

class pagesDashboard
{
    public static function pagesDashboardFavs($core, $favs)
    {
        $favs->register('pages', [
            'title'        => __('Pages'),
            'url'          => $core->adminurl->get('admin.plugin.pages'),
            'small-icon'   => dcPage::getPF('pages/icon.png'),
            'large-icon'   => dcPage::getPF('pages/icon-big.png'),
            'permissions'  => 'contentadmin,pages',
            'dashboard_cb' => ['pagesDashboard', 'pagesDashboardCB'],
            'active_cb'    => ['pagesDashboard', 'pagesActiveCB']
        ]);
        $favs->register('newpage', [
            'title'       => __('New page'),
            'url'         => $core->adminurl->get('admin.plugin.pages', ['act' => 'page']),
            'small-icon'  => dcPage::getPF('pages/icon-np.png'),
            'large-icon'  => dcPage::getPF('pages/icon-np-big.png'),
            'permissions' => 'contentadmin,pages',
            'active_cb'   => ['pagesDashboard', 'newPageActiveCB']
        ]);
    }

    public static function pagesDashboardCB($core, $v)
    {
        $params              = new ArrayObject();
        $params['post_type'] = 'page';
        $page_count          = $core->blog->getPosts($params, true)->f(0);
        if ($page_count > 0) {
            $str_pages  = ($page_count > 1) ? __('%d pages') : __('%d page');
            $v['title'] = sprintf($str_pages, $page_count);
        }
    }

    public static function pagesActiveCB($request, $params)
    {
        return ($request == 'plugin.php') && isset($params['p']) && $params['p'] == 'pages'
                                          && !(isset($params['act']) && $params['act'] == 'page');
    }

    public static function newPageActiveCB($request, $params)
    {
        return ($request == 'plugin.php') && isset($params['p']) && $params['p'] == 'pages'
                                          && isset($params['act'])                                   && $params['act']                               == 'page';
    }
}

function pages_users_actions_headers()
{
    return dcPage::jsLoad('index.php?pf=pages/js/_users_actions.js');
}

$_menu['Blog']->addItem(__('Pages'),
    $core->adminurl->get('admin.plugin.pages'),
    dcPage::getPF('pages/icon.png'),
    preg_match('/plugin.php(.*)$/', $_SERVER['REQUEST_URI']) && !empty($_REQUEST['p']) && $_REQUEST['p'] == 'pages',
    $core->auth->check('contentadmin,pages', $core->blog->id));

$core->auth->setPermissionType('pages', __('manage pages'));

require dirname(__FILE__) . '/_widgets.php';

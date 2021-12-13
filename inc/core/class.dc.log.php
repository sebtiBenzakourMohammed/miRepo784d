<?php
/**
 * @package Dotclear
 * @subpackage Core
 *
 * @copyright Olivier Meunier & Association Dotclear
 * @copyright GPL-2.0-only
 */
if (!defined('DC_RC_PATH')) {
    return;
}

class dcLog
{
    protected $core;
    protected $prefix;

    /**
     * Constructs a new instance.
     *
     * @param      dcCore  $core   The core
     */
    public function __construct(dcCore $core)
    {
        $this->core   = &$core;
        $this->prefix = $core->prefix;
    }

    /**
     * Retrieves logs. <b>$params</b> is an array taking the following
     * optionnal parameters:
     *
     * - blog_id: Get logs belonging to given blog ID
     * - user_id: Get logs belonging to given user ID
     * - log_ip: Get logs belonging to given IP address
     * - log_table: Get logs belonging to given log table
     * - order: Order of results (default "ORDER BY log_dt DESC")
     * - limit: Limit parameter
     *
     * @param      array   $params      The parameters
     * @param      bool    $count_only  Count only resultats
     *
     * @return     record  The logs.
     */
    public function getLogs($params = [], $count_only = false)
    {
        if ($count_only) {
            $f = 'COUNT(log_id)';
        } else {
            $f = 'L.log_id, L.user_id, L.log_table, L.log_dt, ' .
                'L.log_ip, L.log_msg, L.blog_id, U.user_name, ' .
                'U.user_firstname, U.user_displayname, U.user_url';
        }

        $strReq = 'SELECT ' . $f . ' FROM ' . $this->prefix . 'log L ';

        if (!$count_only) {
            $strReq .= 'LEFT JOIN ' . $this->prefix . 'user U ' .
                'ON U.user_id = L.user_id ';
        }

        if (!empty($params['blog_id'])) {
            if ($params['blog_id'] === 'all') {
                $strReq .= 'WHERE NULL IS NULL ';
            } else {
                $strReq .= "WHERE L.blog_id = '" . $this->core->con->escape($params['blog_id']) . "' ";
            }
        } else {
            $strReq .= "WHERE L.blog_id = '" . $this->core->blog->id . "' ";
        }

        if (!empty($params['user_id'])) {
            $strReq .= 'AND L.user_id' . $this->core->con->in($params['user_id']);
        }
        if (!empty($params['log_ip'])) {
            $strReq .= 'AND log_ip' . $this->core->con->in($params['log_ip']);
        }
        if (!empty($params['log_table'])) {
            $strReq .= 'AND log_table' . $this->core->con->in($params['log_table']);
        }

        if (!$count_only) {
            if (!empty($params['order'])) {
                $strReq .= 'ORDER BY ' . $this->core->con->escape($params['order']) . ' ';
            } else {
                $strReq .= 'ORDER BY log_dt DESC ';
            }
        }

        if (!empty($params['limit'])) {
            $strReq .= $this->core->con->limit($params['limit']);
        }

        $rs = $this->core->con->select($strReq);
        $rs->extend('rsExtLog');

        return $rs;
    }

    /**
     * Creates a new log. Takes a cursor as input and returns the new log ID.
     *
     * @param      cursor  $cur    The current
     *
     * @return     integer
     */
    public function addLog($cur)
    {
        $this->core->con->writeLock($this->prefix . 'log');

        try {
            # Get ID
            $rs = $this->core->con->select(
                'SELECT MAX(log_id) ' .
                'FROM ' . $this->prefix . 'log '
            );

            $cur->log_id  = (integer) $rs->f(0) + 1;
            $cur->blog_id = (string) $this->core->blog->id;
            $cur->log_dt  = date('Y-m-d H:i:s');

            $this->getLogCursor($cur, $cur->log_id);

            # --BEHAVIOR-- coreBeforeLogCreate
            $this->core->callBehavior('coreBeforeLogCreate', $this, $cur);

            $cur->insert();
            $this->core->con->unlock();
        } catch (Exception $e) {
            $this->core->con->unlock();

            throw $e;
        }

        # --BEHAVIOR-- coreAfterLogCreate
        $this->core->callBehavior('coreAfterLogCreate', $this, $cur);

        return $cur->log_id;
    }

    /**
     * Deletes a log.
     *
     * @param      integer  $id     The identifier
     * @param      bool     $all    Remove all logs
     */
    public function delLogs($id, $all = false)
    {
        $strReq = $all ?
        'TRUNCATE TABLE ' . $this->prefix . 'log' :
        'DELETE FROM ' . $this->prefix . 'log WHERE log_id' . $this->core->con->in($id);

        $this->core->con->execute($strReq);
    }

    /**
     * Gets the log cursor.
     *
     * @param      cursor     $cur     The current
     * @param      mixed      $log_id  The log identifier
     *
     * @throws     Exception
     */
    private function getLogCursor($cur, $log_id = null)
    {
        if ($cur->log_msg === '') {
            throw new Exception(__('No log message'));
        }

        if ($cur->log_table === null) {
            $cur->log_table = 'none';
        }

        if ($cur->user_id === null) {
            $cur->user_id = 'unknown';
        }

        if ($cur->log_dt === '' || $cur->log_dt === null) {
            $cur->log_dt = date('Y-m-d H:i:s');
        }

        if ($cur->log_ip === null) {
            $cur->log_ip = http::realIP();
        }

        $log_id = is_int($log_id) ? $log_id : $cur->log_id;
    }
}

/**
 * Extent log record class.
 */
class rsExtLog
{
    /**
     * Gets the user cn.
     *
     * @param      record  $rs     Invisible parameter
     *
     * @return     string  The user cn.
     */
    public static function getUserCN($rs)
    {
        $user = dcUtils::getUserCN($rs->user_id, $rs->user_name,
            $rs->user_firstname, $rs->user_displayname);

        if ($user === 'unknown') {
            $user = __('unknown');
        }

        return $user;
    }
}

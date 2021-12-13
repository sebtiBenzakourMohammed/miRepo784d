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

class dcUpdate
{
    const ERR_FILES_CHANGED    = 101;
    const ERR_FILES_UNREADABLE = 102;
    const ERR_FILES_UNWRITALBE = 103;

    protected $url;
    protected $subject;
    protected $version;
    protected $cache_file;

    protected $version_info = [
        'version'  => null,
        'href'     => null,
        'checksum' => null,
        'info'     => null,
        'php'      => '7.3',
        'notify'   => true
    ];

    protected $cache_ttl    = '-6 hours';
    protected $forced_files = [];

    /**
     * Constructor
     *
     * @param string $url           Versions file URL
     * @param string $subject       Subject to check
     * @param string $version       Version type
     * @param string $cache_dir     Directory cache path
     */
    public function __construct($url, $subject, $version, $cache_dir)
    {
        $this->url        = $url;
        $this->subject    = $subject;
        $this->version    = $version;
        $this->cache_file = $cache_dir . '/' . $subject . '-' . $version;
    }

    /**
     * Checks for Dotclear updates.
     * Returns latest version if available or false.
     *
     * @param   string  $version    Current version to compare
     * @param   boolean $nocache    Force checking
     *
     * @return  mixed   Latest version if available
     */
    public function check($version, $nocache = false)
    {
        $this->getVersionInfo($nocache);
        $v = $this->getVersion();
        if ($v && version_compare($version, $v, '<')) {
            return $v;
        }

        return false;
    }

    public function getVersionInfo($nocache = false)
    {
        # Check cached file
        if (is_readable($this->cache_file) && filemtime($this->cache_file) > strtotime($this->cache_ttl) && !$nocache) {
            $c = @file_get_contents($this->cache_file);
            $c = @unserialize($c);
            if (is_array($c)) {
                $this->version_info = $c;

                return;
            }
        }

        $cache_dir = dirname($this->cache_file);
        $can_write = (!is_dir($cache_dir) && is_writable(dirname($cache_dir)))
        || (!file_exists($this->cache_file) && is_writable($cache_dir))
        || is_writable($this->cache_file);

        # If we can't write file, don't bug host with queries
        if (!$can_write) {
            return;
        }

        if (!is_dir($cache_dir)) {
            try {
                files::makeDir($cache_dir);
            } catch (Exception $e) {
                return;
            }
        }

        # Try to get latest version number
        try {
            $path   = '';
            $status = 0;

            $http_get = function ($http_url) use (&$status, $path) {
                $client = netHttp::initClient($http_url, $path);
                if ($client !== false) {
                    $client->setTimeout(DC_QUERY_TIMEOUT);
                    $client->setUserAgent($_SERVER['HTTP_USER_AGENT']);
                    $client->get($path);
                    $status = (int) $client->getStatus();
                }

                return $client;
            };

            $client = $http_get($this->url);
            if ($status >= 400) {
                // If original URL uses HTTPS, try with HTTP
                $url_parts = parse_url($client->getRequestURL());
                if (isset($url_parts['scheme']) && $url_parts['scheme'] == 'https') {
                    // Replace https by http in url
                    $this->url = preg_replace('/^https(?=:\/\/)/i', 'http', $this->url);
                    $client    = $http_get($this->url);
                }
            }
            if (!$status || $status >= 400) {
                throw new Exception();
            }
            $this->readVersion($client->getContent());
        } catch (Exception $e) {
            return;
        }

        # Create cache
        file_put_contents($this->cache_file, serialize($this->version_info));
    }

    public function getVersion()
    {
        return $this->version_info['version'];
    }

    public function getFileURL()
    {
        return $this->version_info['href'];
    }

    public function getInfoURL()
    {
        return $this->version_info['info'];
    }

    public function getChecksum()
    {
        return $this->version_info['checksum'];
    }

    public function getPHPVersion()
    {
        return $this->version_info['php'];
    }

    public function getNotify()
    {
        return $this->version_info['notify'];
    }

    public function getForcedFiles()
    {
        return $this->forced_files;
    }

    public function setForcedFiles(...$args)
    {
        $this->forced_files = $args;
    }

    /**
     * Sets notification flag.
     */
    public function setNotify($n)
    {
        if (!is_writable($this->cache_file)) {
            return;
        }

        $this->version_info['notify'] = (boolean) $n;
        file_put_contents($this->cache_file, serialize($this->version_info));
    }

    public function checkIntegrity($digests_file, $root)
    {
        if (!$digests_file) {
            throw new Exception(__('Digests file not found.'));
        }

        $changes = $this->md5sum($root, $digests_file);

        if (!empty($changes)) {
            $e            = new Exception('Some files have changed.', self::ERR_FILES_CHANGED);
            $e->bad_files = $changes;   // @phpstan-ignore-line

            throw $e;
        }

        return true;
    }

    /**
     * Downloads new version to destination $dest.
     */
    public function download($dest)
    {
        $url = $this->getFileURL();

        if (!$url) {
            throw new Exception(__('No file to download'));
        }

        if (!is_writable(dirname($dest))) {
            throw new Exception(__('Root directory is not writable.'));
        }

        try {
            $path   = '';
            $status = 0;

            $http_get = function ($http_url) use (&$status, $dest, $path) {
                $client = netHttp::initClient($http_url, $path);
                if ($client !== false) {
                    $client->setTimeout(DC_QUERY_TIMEOUT);
                    $client->setUserAgent($_SERVER['HTTP_USER_AGENT']);
                    $client->useGzip(false);
                    $client->setPersistReferers(false);
                    $client->setOutput($dest);
                    $client->get($path);
                    $status = (int) $client->getStatus();
                }

                return $client;
            };

            $client = $http_get($url);
            if ($status >= 400) {
                // If original URL uses HTTPS, try with HTTP
                $url_parts = parse_url($client->getRequestURL());
                if (isset($url_parts['scheme']) && $url_parts['scheme'] == 'https') {
                    // Replace https by http in url
                    $url    = preg_replace('/^https(?=:\/\/)/i', 'http', $url);
                    $client = $http_get($url);
                }
            }
            if ($status != 200) {
                @unlink($dest);

                throw new Exception();
            }
        } catch (Exception $e) {
            throw new Exception(__('An error occurred while downloading archive.'));
        }
    }

    /**
     * Checks if archive was successfully downloaded.
     */
    public function checkDownload($zip)
    {
        $cs = $this->getChecksum();

        return $cs && is_readable($zip) && md5_file($zip) == $cs;
    }

    /**
     * Backups changed files before an update.
     */
    public function backup($zip_file, $zip_digests, $root, $root_digests, $dest)
    {
        if (!is_readable($zip_file)) {
            throw new Exception(__('Archive not found.'));
        }

        if (!is_readable($root_digests)) {
            @unlink($zip_file);

            throw new Exception(__('Unable to read current digests file.'));
        }

        # Stop everything if a backup already exists and can not be overrided
        if (!is_writable(dirname($dest)) && !file_exists($dest)) {
            throw new Exception(__('Root directory is not writable.'));
        }

        if (file_exists($dest) && !is_writable($dest)) {
            return false;
        }

        $b_fp = @fopen($dest, 'wb');
        if ($b_fp === false) {
            return false;
        }

        $zip   = new fileUnzip($zip_file);
        $b_zip = new fileZip($b_fp);

        if (!$zip->hasFile($zip_digests)) {
            @unlink($zip_file);

            throw new Exception(__('Downloaded file does not seem to be a valid archive.'));
        }

        $opts        = FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES;
        $cur_digests = file($root_digests, $opts);
        $new_digests = explode("\n", $zip->unzip($zip_digests));
        $new_files   = $this->getNewFiles($cur_digests, $new_digests);
        $zip->close();
        unset($opts, $cur_digests, $new_digests, $zip);

        $not_readable = [];

        if (!empty($this->forced_files)) {
            $new_files = array_merge($new_files, $this->forced_files);
        }

        foreach ($new_files as $file) {
            if (!$file || !file_exists($root . '/' . $file)) {
                continue;
            }

            try {
                $b_zip->addFile($root . '/' . $file, $file);
            } catch (Exception $e) {
                $not_readable[] = $file;
            }
        }

        # If only one file is not readable, stop everything now
        if (!empty($not_readable)) {
            $e            = new Exception('Some files are not readable.', self::ERR_FILES_UNREADABLE);
            $e->bad_files = $not_readable;  // @phpstan-ignore-line

            throw $e;
        }

        $b_zip->write();
        fclose($b_fp);
        $b_zip->close();

        return true;
    }

    /**
     * Upgrade process.
     */
    public function performUpgrade($zip_file, $zip_digests, $zip_root, $root, $root_digests)
    {
        if (!is_readable($zip_file)) {
            throw new Exception(__('Archive not found.'));
        }

        if (!is_readable($root_digests)) {
            @unlink($zip_file);

            throw new Exception(__('Unable to read current digests file.'));
        }

        $zip = new fileUnzip($zip_file);

        if (!$zip->hasFile($zip_digests)) {
            @unlink($zip_file);

            throw new Exception(__('Downloaded file does not seem to be a valid archive.'));
        }

        $opts        = FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES;
        $cur_digests = file($root_digests, $opts);
        $new_digests = explode("\n", $zip->unzip($zip_digests));
        $new_files   = self::getNewFiles($cur_digests, $new_digests);

        if (!empty($this->forced_files)) {
            $new_files = array_merge($new_files, $this->forced_files);
        }

        $zip_files    = [];
        $not_writable = [];

        foreach ($new_files as $file) {
            if (!$file) {
                continue;
            }

            if (!$zip->hasFile($zip_root . '/' . $file)) {
                @unlink($zip_file);

                throw new Exception(__('Incomplete archive.'));
            }

            $dest = $dest_dir = $root . '/' . $file;
            while (!is_dir($dest_dir = dirname($dest_dir)));

            if ((file_exists($dest) && !is_writable($dest)) || (!file_exists($dest) && !is_writable($dest_dir))) {
                $not_writable[] = $file;

                continue;
            }

            $zip_files[] = $file;
        }

        # If only one file is not writable, stop everything now
        if (!empty($not_writable)) {
            $e            = new Exception('Some files are not writable', self::ERR_FILES_UNWRITALBE);
            $e->bad_files = $not_writable;  // @phpstan-ignore-line

            throw $e;
        }

        # Everything's fine, we can write files, then do it now
        $can_touch = function_exists('touch');
        foreach ($zip_files as $file) {
            $zip->unzip($zip_root . '/' . $file, $root . '/' . $file);
            if ($can_touch) {
                @touch($root . '/' . $file);
            }
        }
        @unlink($zip_file);
    }

    protected function getNewFiles($cur_digests, $new_digests)
    {
        $cur_md5 = $cur_path = $cur_digests;
        $new_md5 = $new_path = $new_digests;

        array_walk($cur_md5, [$this, 'parseLine'], 1);
        array_walk($cur_path, [$this, 'parseLine'], 2);
        array_walk($new_md5, [$this, 'parseLine'], 1);
        array_walk($new_path, [$this, 'parseLine'], 2);

        $cur = array_combine($cur_md5, $cur_path);
        $new = array_combine($new_md5, $new_path);

        return array_values(array_diff_key($new, $cur));
    }

    protected function readVersion($str)
    {
        try {
            $xml = new SimpleXMLElement($str, LIBXML_NOERROR);
            $r   = $xml->xpath("/versions/subject[@name='" . $this->subject . "']/release[@name='" . $this->version . "']");

            if (!empty($r) && is_array($r)) {
                $r                              = $r[0];
                $this->version_info['version']  = isset($r['version']) ? (string) $r['version'] : null;
                $this->version_info['href']     = isset($r['href']) ? (string) $r['href'] : null;
                $this->version_info['checksum'] = isset($r['checksum']) ? (string) $r['checksum'] : null;
                $this->version_info['info']     = isset($r['info']) ? (string) $r['info'] : null;
                $this->version_info['php']      = isset($r['php']) ? (string) $r['php'] : null;
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    protected function md5sum($root, $digests_file)
    {
        if (!is_readable($digests_file)) {
            throw new Exception(__('Unable to read digests file.'));
        }

        $opts     = FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES;
        $contents = file($digests_file, $opts);

        $changes = [];

        foreach ($contents as $digest) {
            if (!preg_match('#^([\da-f]{32})\s+(.+?)$#', $digest, $m)) {
                continue;
            }

            $md5      = $m[1];
            $filename = $root . '/' . $m[2];

            # Invalid checksum
            if (!is_readable($filename) || !self::md5_check($filename, $md5)) {
                $changes[] = substr($m[2], 2);
            }
        }

        # No checksum found in digests file
        if (empty($md5)) {
            throw new Exception(__('Invalid digests file.'));
        }

        return $changes;
    }

    protected function parseLine(&$v, $k, $n)
    {
        if (!preg_match('#^([\da-f]{32})\s+(.+?)$#', $v, $m)) {
            return;
        }

        $v = $n == 1 ? md5($m[2] . $m[1]) : substr($m[2], 2);
    }

    protected static function md5_check($filename, $md5)
    {
        if (md5_file($filename) == $md5) {
            return true;
        }
        $filecontent = file_get_contents($filename);
        $filecontent = str_replace("\r\n", "\n", $filecontent);
        $filecontent = str_replace("\r", "\n", $filecontent);
        if (md5($filecontent) == $md5) {
            return true;
        }

        return false;
    }
}

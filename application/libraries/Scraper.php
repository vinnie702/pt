<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Scraper
{
    private $ci;

    public function __construct()
    {
        $this->ci =& get_instance();

        // if connected to DB
        if (class_exists('CI_DB'))
        {

        }
    }

    /**
     * extracs the amazon ID from the URL (only for amazon right now)
     *
     * @param mixed $url 
     *
     * @return TODO
     */
    public function getIDFromURL($url)
    {
        // echo "<pre>";

        // echo $url . PHP_EOL;

        $exp = explode('/', $url);

        // print_r($exp);

        // if (empty($exp[5])) return false;

        return $exp[5];
    }

    /**
     * TODO: short description.
     *
     * @param mixed $urL 
     *
     * @return TODO
     */
    public function downloadHTML ($id)
    {
        $id = intval($id);

        if (empty($id)) throw new Exception("ID is empty!");

        // create new html file
        $path = "public/uploads/html/{$id}/";

        $this->ci->functions->createDir($path);

        $filename = $id . '_' . date("YmdGis") . '.html';

        $touch = touch($path . $filename);

        if ($touch === false) throw new Exception("Unable to create file  ({$path}{$filename})");

        $fp = fopen($path.$filename, 'w');

        if ($fp === false) throw new Exception("Unable to open file to write to ({$path}{$filename})");

        $url = $this->getTrackingItemUrl($id);

        // gets html
        $html = file_get_contents($url);

        if ($html === false) throw new Exception("Unable to get HTML content for the following url: {$url}");

        $write = fwrite($fp, $html);

        if ($write === false) throw new Exception("Unable to write HTML to file  ({$path}{$filename})");

        @fclose($fp);

        $this->_insertTrackingItemHtml($id, $filename);

        return $filename;
    }

    /**
     * TODO: short description.
     *
     * @param mixed $id       
     * @param mixed $filename 
     *
     * @return TODO
     */
    private function _insertTrackingItemHtml($id, $filename)
    {
        $id = intval($id);

        if (empty($id)) throw new Exception("ID is empty!");
        if (empty($filename)) throw new Exception("File name is empty!");

        $data = array
            (
                'trackingItemID' => $id,
                'datestamp' => DATESTAMP,
                'filename' => $filename
            );

        $this->ci->db->insert('trackingItemsHtml', $data);

        return $this->ci->db->insert_id();
    }

    /**
     * TODO: short description.
     *
     * @param mixed $item 
     *
     * @return TODO
     */
    public function checkRequireDownload ($id)
    {
        $utOneDay = 86400; // 1 day in seconds

        $now = strtotime("now");

        $lsDatestamp = $this->getLastScrapeDate($id);

        // item has never been scanned
        if ($lsDatestamp === false) return true;

        $lsd = strtotime($lsDatestamp);

        // its been over 24 hours since last download
        if (($now - $utOneDay) >= $lsd)
        {
            return true;
        }

        return false;
    }

    /**
     * TODO: short description.
     *
     * @param mixed $id 
     *
     * @return TODO
     */
    public function getLastScrapeDate ($id)
    {
        $id = intval($id);
        if (empty($id)) throw new Exception("ID is empty!");

        $mtag = "lastScrapeDate-{$id}";

        $data = $this->ci->cache->memcached->get($mtag);

        if (!$data)
        {
            $this->ci->db->select('datestamp');
            $this->ci->db->from('trackingItemsHtml');
            $this->ci->db->where('trackingItemID', $id);
            $this->ci->db->order_by('datestamp', 'desc');
            $this->ci->db->limit(1);

            $query = $this->ci->db->get();

            $results = $query->result();

            $data = $results[0]->datestamp;

            $this->ci->cache->memcached->save($mtag, $data, $this->ci->config->item('cache_timeout'));
        }
        
        if (empty($data)) return false;

        return $data;
    }

    /**
     * TODO: short description.
     *
     * @param mixed $id 
     *
     * @return TODO
     */
    public function getTrackingItemUrl ($id)
    {
        $id = intval($id);

        if (empty($id)) throw new Exception("ID is empty!");

        $mtag = "trackingItemUrl-{$id}";

        $data = $this->ci->cache->memcached->get($mtag);

        if (!$data)
        {
            $this->ci->db->select('url');
            $this->ci->db->from('trackingItems');
            $this->ci->db->where('id', $id);

            $query = $this->ci->db->get();

            $results = $query->result();

            $data = $results[0]->url;
            
            $this->ci->cache->memcached->save($mtag, $data, $this->ci->config->item('cache_timeout'));
        }

        return $data;
    }
}

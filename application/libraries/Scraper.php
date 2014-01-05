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

        $filename = uniqid() . '_' . date("YmdGis") . '.html';

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

    /**
     * TODO: short description.
     *
     * @param mixed $trackingItemID 
     *
     * @return TODO
     */
    private function _getLatestHtml ($trackingItemID)
    {
        $trackingItemID = intval($trackingItemID);

        if (empty($trackingItemID)) throw new Exception("Tracking Item ID is empty!");

        $mtag = "lastestHtmlFile-{$trackingItemID}";

        $data = $this->ci->cache->memcached->get($mtag);

        if (!$data)
        {
            $this->ci->db->select('fileName');
            $this->ci->db->from('trackingItemsHtml');
            $this->ci->db->where('trackingItemID', $trackingItemID);

            $query = $this->ci->db->get();

            $results = $query->result();

            $data = $results[0]->fileName;

            $this->ci->cache->memcached->save($mtag, $data, $this->ci->config->item('cache_timeout'));
        }

        return $data;
    }


    /**
     * TODO: short description.
     *
     * @param mixed $id 
     *
     * @return TODO
     */
    public function scrapeLatestData ($trackingItemID)
    {
        $fileName = $this->_getLatestHtml($trackingItemID);

        echo 'FileName: ' . $fileName;

        $contents = file_get_contents('public/uploads/html/' . $trackingItemID . '/'  . $fileName);

        /*
        $startTag = '<span id="btAsinTitle" >';

        // first gets title
        $titleStart = stripos($contents, $startTag);

        $titleEnd = stripos($contents, "</span>", $titleStart + 1);

        $diff = $titleEnd - $titleStart;

        // echo "\nTitle Start: {$titleStart}\n";
        // echo "Title End: {$titleEnd}\n";
        // echo "DIFF: " . ($titleEnd - $titleStart) . PHP_EOL;

        // $title = substr($contents, ($titleStart + strlen($startTag)), $diff);
        $title = substr($contents, ($titleStart), $diff);

        $title = str_replace($startTag, '', $title);
        */
        // gets product title
        // $title = $this->_getTagVal($contents, '<span id="btAsinTitle" >', '</span>');

        $title = $this->_getTitle($contents);
        echo "Title: {$title}\n";

        $img =  $this->_getImage($contents);
        echo "src: {$img}<br><img src='{$img}'>";

        // gets product details
        // $details = $this->_getTagVal($contents, '<h2>Product Details</h2>', '</div>');
    
        // $details = str_replace('<div class="content">', '', $details);

echo "<hr>" . PHP_EOL;
        $details =  $this->_getDetails($contents);

        echo $details;
        
        $price = $this->_getPrice($contents);

        echo 'Price: ' . $price . PHP_EOL;
    }

    /**
     * TODO: short description.
     *
     * @param mixed $html     
     * @param mixed $startTag 
     * @param mixed $endTag   
     *
     * @return TODO
     */
    private function _getTagVal($html, $startTag, $endTag)
    {
        if (is_numeric($startTag)) $start = $startTag;
        else $start = stripos($html, $startTag);

        $end = stripos($html, $endTag, $start);

        // if (empty($start) || empty($end)) throw new Exception("Unable to find tags \"{$startTag}\" and \"{$endTag}\"");

        $diff = $end - $start;

        // echo "Start <xmp>{$startTag}</xmp>: $start\n";
        echo "End: $end\n";

        $content = substr($html, $start, $diff);

        // echo 'CONTENT : ' . $content;
        // $content = str_replace($startTag, '', $content);

        // $content = $this->ci->functions->stripTags($content);

        return $content;
    }

    /**
     * TODO: short description.
     *
     * @param mixed $html 
     *
     * @return TODO
     */
    private function _getPrice ($html)
    {
        echo "<hr>";

        $price = stripos($html, '<span class="price">');
        $priceLarge = stripos($html, '<b class="priceLarge">');

        if (!empty($price))
        {
            $price = $this->_getTagVal($html, '<span class="price">', '</span>');

            // echo 'found price';
        }
        elseif (!empty($priceLarge))
        {
            $price = $this->_getTagVal($html, '<b class="priceLarge">', '</b>');
            // echo 'price Large found';
        }
        else
        {
            // echo 'no price found';
        }
    
        $price = str_replace('$', '', $price);

        return $price;
    }

    /**
     * TODO: short description.
     *
     * @param mixed $html 
     *
     * @return TODO
     */
    private function _getTitle ($html)
    {
        echo "<br>";

        (string) $titlePos = stripos($html, 'span id="btAsinTitle"');

        $firstGT = stripos($html, '>', $titlePos);

        // echo "TITLE POS:{$titlePos} | first >: {$firstGT}";

        if (!empty($titlePos))
        {
            $title = $this->_getTagVal($html, $firstGT, '</span>');

            $title = str_replace('>', null, $title);
            // echo 'title found!';
        }
        
    
        return $title;
    }

    /**
     * TODO: short description.
     *
     * @param mixed $html 
     *
     * @return TODO
     */
    private function _getDetails ($html)
    {
        $details = $this->_getTagVal($html, '<h2>Product Details</h2>', '</div>');

        return $details;
    }

    /**
     * TODO: short description.
     *
     * @param mixed $html 
     *
     * @return TODO
     */
    private function _getImage ($html)
    {
        $imgPos = stripos($html, '<img id="main-image-nonjs" src="');
        echo "ImgPOS: {$imgPos}\n";

        // $start = stripos($html, ''
        
        $img = $this->_getTagVal($html, $imgPos, '" alt="" >');

        $img = str_replace('<img id="main-image-nonjs" src="', null, $img);
        return $img;
    }
}

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

        // error_log("Getting info from {$url}");

        // gets html
        $html = file_get_contents($url);

        if ($html === false) throw new Exception("Unable to get HTML content for the following url: {$url}");

        $write = fwrite($fp, $html);

        if ($write === false) throw new Exception("Unable to write HTML to file  ({$path}{$filename})");

        @fclose($fp);

        $this->_insertTrackingItemHtml($id, $filename);

        // $this->scrapeLatestData($id);

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
        $ttd = (int) $this->ci->config->item('TTD');

        $now = strtotime("now");

        $lsDatestamp = $this->getLastScrapeDate($id);

        // item has never been scanned
        if ($lsDatestamp === false) return true;

        $lsd = strtotime($lsDatestamp);

        // its been over 20 hours since last download
        if (($now - $ttd) >= $lsd)
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
    public function _getLatestHtml ($trackingItemID)
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
            $this->ci->db->order_by('datestamp', 'desc');
            $this->ci->db->limit(1);

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
        
        // error_log('FileName: ' . $fileName);

        $path = $_SERVER['DOCUMENT_ROOT'] . 'public/uploads/html/' . $trackingItemID . '/';

        if (!file_exists($path . $fileName)) throw new Exception("File to scrape does not exists! ({$path}{$fileName})");

        $contents = file_get_contents($path  . $fileName);

        $title = $this->_getTitle($contents);
        // echo "Title: {$title}\n";

        $this->updateTrackItemTitle($trackingItemID, $title);

        $img =  $this->_getImage($contents);
        // echo "src: {$img}<br><img src='{$img}'>";
        
        $this->updateImgUrl($trackingItemID, $img);

// echo "<hr>" . PHP_EOL;
        $details =  $this->_getDetails($contents);
        // echo $details;
        $this->updateTrackItemDesc($trackingItemID, $details);

        
        $price = $this->_getPrice($contents);

        // check if there is a price already saved for today
        // $saved = $this->checkPriceSaved($trackingItemID);

        // always updates price
        $this->savePrice($trackingItemID, $price);

        // echo 'Price: ' . $price . PHP_EOL;
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
        // echo "End: $end\n";

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
        // $opPrice = stripos($html, 'priceblock_ourprice');

        if (empty($html)) throw new Exception("HTML is empty! No HTML to get price from!");

        $bxgyItemPrice = strpos($html, 'bxgy-item-price');
        $price = stripos($html, '<span class="price">');
        $priceLarge = stripos($html, '<b class="priceLarge">');

        $priceTxt = stripos($html, ">Price:");

        if (!empty($bxgyItemPrice))
        {
            // error_log('Found bxgy-item-price');
            $price = $this->_getTagVal($html, 'bxgy-item-price', '</span>');

            // error_log("PRICE: {$price}");

            $price = str_replace('bxgy-item-price', '', $price);
            $price = str_replace('"', '', $price);
            $price = str_replace('\'', '', $price);
            $price = str_replace('>', '', $price);

            // return $price;

        }
        elseif (!empty($price))
        {
            $price = $this->_getTagVal($html, '<span class="price">', '</span>');

            $price = str_replace('<span class="price">', '', $price);

            // echo 'found price';
        }
        elseif (!empty($priceLarge))
        {
            $price = $this->_getTagVal($html, '<b class="priceLarge">', '</b>');
            
            $price = str_replace('<b class="priceLarge">', '', $price);
            // echo 'price Large found';
        }
        elseif (!empty($priceTxt))
        {
            $dsPos = stripos($html, '$', $priceTxt);

            $price = substr($html, $dsPos, stripos($html, '</span>', $dsPos));
            // error_log('PRICE FOUND ' . $price);
        }
        else
        {
            // error_log("*** NO PRICE FOUND ***");
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
        // echo "<br>";

        (string) $titlePos = stripos($html, 'span id="btAsinTitle"');

        $firstGT = stripos($html, '>', $titlePos);

        // echo "TITLE POS:{$titlePos} | first >: {$firstGT}";

        if (!empty($titlePos))
        {
            $title = $this->_getTagVal($html, $firstGT, '</span>');

            $title = str_replace('>', null, $title);

            // echo 'title found!';
        }

        // unable to get title from previous attempt
        if (empty($title))
        {
            $titlePos = stripos($html, '<h1');

            $firstGT = stripos($html, '>', $titlePos);

            if (!empty($titlePos))
            {
                $title = $this->_getTagVal($html, $firstGT, '</h1>');

                $title = str_replace('</h1>', null, $title);
                $title = str_replace('>', null, $title);

                $title = trim($title);
                // echo 'title found!';
            }
        }
        // error_log('TITLE:' . $title);
        
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
        // echo "ImgPOS: {$imgPos}\n";

        // $start = stripos($html, ''

        $img = $this->_getTagVal($html, $imgPos, '" alt="" >');

        $img = str_replace('<img id="main-image-nonjs" src="', null, $img);

        if (empty($img))
        {
            $imgPos = stripos($html, 'id="rwImages_hidden"');
            $firstGT = stripos($html, '>', $imgPos); // first > symbol after it finds that tag

            if (!empty($imgPos))
            {
                $imgHtml = $this->_getTagVal($html, $firstGT, '</div>');

                $imgHtml = str_replace('>', null, $imgHtml);

                $imgHtml = trim($imgHtml);

                // now get src of image

                // error_log("Img HTML: {$imgHtml}");

                $imgHtml = str_replace("<img src=\"", null, $imgHtml);

                $img = substr((string) $imgHtml, 0, stripos($imgHtml, '"'));

                $img = trim($img);
            }
        }

        return $img;
    }

    /**
     * TODO: short description.
     *
     * @param mixed $trackingItemID 
     * @param mixed $title          
     *
     * @return TODO
     */
    public function updateTrackItemTitle ($trackingItemID, $title)
    {
        $trackingItemID = intval($trackingItemID);

        if (empty($trackingItemID)) throw new Exception("Tracking Item ID is empty!");

        $data = array
            (
                'itemName' => $title,
                'lastUpdated' => DATESTAMP
            );

        $this->ci->db->where('id', $trackingItemID);
        $this->ci->db->update('trackingItems', $data);

        return true;
    }

    public function updateTrackItemDesc ($trackingItemID, $desc)
    {
        $trackingItemID = intval($trackingItemID);

        if (empty($trackingItemID)) throw new Exception("Tracking Item ID is empty!");

        $data = array
            (
                'description' => $desc,
                'lastUpdated' => DATESTAMP
            );

        $this->ci->db->where('id', $trackingItemID);
        $this->ci->db->update('trackingItems', $data);

        return true;
    }

    public function updateImgUrl ($trackingItemID, $imgUrl)
    {
        $trackingItemID = intval($trackingItemID);

        if (empty($trackingItemID)) throw new Exception("Tracking Item ID is empty!");

        $data = array
            (
                'imgUrl' => $imgUrl,
                'lastUpdated' => DATESTAMP
            );

        $this->ci->db->where('id', $trackingItemID);
        $this->ci->db->update('trackingItems', $data);

        return true;
    }

    /**
     * TODO: short description.
     *
     * @param mixed $trackingItemID 
     * @param mixed $price          
     *
     * @return TODO
     */
    public function savePrice ($trackingItemID, $price)
    {
        $trackingItemID = intval($trackingItemID);

        if (empty($trackingItemID)) throw new Exception("Tracking Item ID is empty!");

        if (empty($price)) throw new Exception("Price is empty. Pretty sure they are not giving it away");

        $data = array
            (
                'trackingItemID' => $trackingItemID,
                'datestamp'=> DATESTAMP,
                'priceDay' => date("Y-m-d", strtotime(DATESTAMP)),
                'price' => $price
            );

        $this->ci->db->insert('trackingItemPrices', $data);

        return $this->ci->db->insert_id();
    }


    /**
     * TODO: short description.
     *
     * @param mixed $trackingItemID 
     *
     * @return TODO
     */
    private function _getLastPriceDate ($trackingItemID)
    {
            $trackingItemID = intval($trackingItemID);

        if (empty($trackingItemID)) throw new Exception("Tracking Item ID is empty!");

        $mtag = "lastPriceDate-{$trackingItemID}";

        $data = $this->ci->cache->memcached->get($mtag);

        if (!$data)
        {
            $this->ci->db->select('datestamp');
            $this->ci->db->from('trackingItemPrices');
            $this->ci->db->where('trackingItemID', $trackingItemID);
            $this->ci->db->order_by('datestamp', 'desc');
            $this->ci->db->limit(1);

            $query = $this->ci->db->get();

            $results = $query->result();

            $data = $results[0]->datestamp;

            $this->ci->cache->memcached->save($mtag, $data, $this->ci->config->item('cache_timeout'));
        }

        return $data;
    }

    /**
     * check there is alredy a price saved for current day8
     *
     * @param mixed $trackingItemID 
     *
     * @return boolean  - triue if there data is already saved for today
     */
    public function checkPriceSaved ($trackingItemID)
    {
        $trackingItemID = intval($trackingItemID);

        if (empty($trackingItemID)) throw new Exception("Tracking Item ID is empty!");

        $lastPriceDate = $this->_getLastPriceDate($trackingItemID);

        $lastPriceDateFormated = date("Y-m-d", strtotime($lastPriceDate));

        $today = date("Y-m-d", strtotime("now"));

        if ($today == $lastPriceDateFormated) return true;

        return false;
    }
}

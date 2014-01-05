<?php  if(!defined('BASEPATH')) die('Direct access not allowed');

class Alerts
{

    public function alert ($msg, $title = "<i class='fa fa-exclamation-triangle'></i> Alert")
    {
        return $this->generateHTML($title, $msg, 'alert-warning');
    }

    public function error ($msg, $title = "<i class='fa fa-times-circle-o'></i> Error")
    {
        return $this->generateHTML($title, $msg, 'alert-danger');
    }

    public function info ($msg, $title = "<i class='fa fa-exclamation-circle'></i> Information")
    {
        return $this->generateHTML($title, $msg, 'alert-info');
    }

    public function success ($msg, $title = "<i class='fa fa-thumbs-up'></i> Success")
    {
        return $this->generateHTML($title, $msg, 'alert-success');
    }

    /**
     * Generates the HTML code for the error
     *
     * @param mixed $title 
     * @param mixed $msg   
     *
     * @return String (html)
     */
    private function generateHTML ($title, $msg, $type = null)
    {
        $html = "<div class='alert {$type}'>";

        $html .= "<button type='button' class='close' data-dismiss='alert'>&times;</button>";

        $html .= "<h4>{$title}</h4> ";

        $html .= "<p>{$msg}</p>";

        $html .= "</div>";

        return $html;
    }
}

<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Category;
use App\Document;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function __construct()
    {
    }    
    
    public function index()
    {
        $content['nav']  = null;
        
        $data = array('data' => $content) ;
        
        return view('admin.home', $data);
    }

    public function upload()
    {
        return;
        $ext    = "htm";
        // $ext    = "html";
        // $ext    = "xhtml";
        $dir    = 'D:\xampp\_tmp\Y';
        $files  = glob($dir."\*." . $ext);
        $length = count($files);

        echo "Total: ".$length."<br>";
        if ($length == 0) return;

        for ($i = 0; $i < $length; $i++) {
            echo ($i+1) . ". " . basename($files[$i]) . "<br><br>";

            $name = str_replace(['.htm', '.html', '.xhtml'], "", basename($files[$i]));
            $str  = file_get_contents($files[$i]);

            $str = $this->saveHTM($str);
            
            if (!empty($str)) {
                $doc = new Document;
                $doc->id            = "";
                $doc->title         = $name;
                $doc->slug          = str_slug($name);
                $doc->content       = "Updating";
                $doc->content       = $str;
                $doc->category      = 8;
                $doc->updated_at    = time();
                $doc->save();
            }

            // var_dump($str);
            // $fi  = fopen("D:\\a.html", 'w');
            // fwrite($fi, $str);
            // fclose($fi);
        }
    }

    private function saveHTM($strOrigin)
    {
        $i = 1;
        while ($i) {
            if ($i == 1) {
                $str = utf8_encode($strOrigin);
            } elseif($i == 2) {
                $str = mb_convert_encoding($strOrigin, 'UTF-8', 'UCS-2LE'); 
            } else {
                echo "---------Failed--------------<br/>";
                return null;
            }
            preg_match("/<div[^>]*class=WordSection1>(.*?)<\\/div>/si", $str, $match);
            if (!empty($match)) {
                return html_entity_decode($match[0]);
            } else {
                $i++;
            }
        }
    }

    private function getBody($str)
    {
        preg_match("/<body>(.*?)<\\/body>/si", $str, $match);
        if (!empty($match)) {
            $str = "";
            $arrStr = explode("\n", $match[1]);
            $count  = count($arrStr);
            for ($j=1; $j<$count; $j++) {
                $str .= $arrStr[$j];
            }
            $str = "<div class=WordSection1>\n" . $str . '</div>';
        } else {
            echo "---------Failed--------------<br/>";
        }
        return $str;
    }
}

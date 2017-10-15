<?php
namespace App\Http\Controllers;

use App\Document;
use App\Acronym;
use App\Category;

use App\Http\Requests;
use Illuminate\Http\Request;

use Input;

class HomeController extends Controller
{
    private $cates;
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->cates = Category::where('parent', 0)->get();
    }

    public function index()
    {
        $data = $this->setData("Sổ tay 56",null,null,0);
        return view('home', $data);
    }
    
    public function ajax($id)
    {
        $document = Document::where('id', $id)->first();
        $document = $document->content;
        $document = $this->ProcessContent($document);
        
        echo $document;
    }
    
    public function document($id)
    {
        $doc = Document::where('id', $id)->first();
        
        if(!$doc)
            $doc = Document::where('slug', $id)->first();
        if(!$doc)
            $doc = Document::where('stt', $id)->first();
        
        if($doc){
            $data = $this->setData(
                $doc->title,
                $doc->stt,
                $this->ProcessContent($doc->content),
                0
           );
        }else 
            $data = $this->setData("Không tìm thấy",null,null,null);
           
        return view('document', $data);
    }
    
    public function search()
    {
        $catID = '';
        if(Input::has('_token')){
            $catID = Input::get("cat");
        }
        $data = $this->setData("Tìm kiếm",null,null,$catID);
        
        return view('search', $data);       
    }
    
    /**
     * create table of list document by category
     * @return json 
     */
    public function ajaxTable()
    {
        $doc  = new Document();        
        $list = $doc->get_datatables();
        
        $data = array();
        $no = $_GET['start'];
        foreach ($list as $document) {
            $no++;
            $id    = $document->id ? $document->id : $document->stt;
            $row   = array();
            $row[] = $no;
            $row[] = "<a href='".url("/document/".$id)."'>".$document->title."</a>";
            $row[] = $document->updated_at ? date('d-m-Y',strtotime($document->updated_at)) : null;
 
            $data[] = $row;
        }
 
        $output = array(
            "draw"            => $_GET['draw'],
            "recordsTotal"    => $doc->count_all(),
            "recordsFiltered" => $doc->count_filtered(),
            "data"            => $data,
        );
        //output to json format
        echo json_encode($output);
    }
    
    /**
     * Set data to put into view
     * @param $title, stt, content, cates, catID
     * @return array
     */
    private function setData($title=null, $stt=null, $content=null, $catID=null)
    {
        return array(
            'title'   => $title,
            'stt'     => $stt,
            'content' => $content,
            'catID'   => $catID
        );
    }
    
    /**
     * remove token and insert link to another document
     * @param string $str content need to insert link
     * @return string
     */
    private function ProcessContent($str)
    {       //return $str;
        //store yellow/red text position
        $quaPos = null;
        $redPos = null;
        $yelPos = null;
        
        //store ID 'XD.TT-PPP3' finded in <span> tag
        $idXDTTPPP = 0;
        
        //extract all <p> tag into $pMatch
        preg_match_all("/<p(.*?)<\/p>/si", $str, $pMatch);
        $content = '';
        $pMatch  = $pMatch[0];
        $pLength = count($pMatch);
            
        for($i = 0; $i<$pLength; $i++){
            //$pMatch[$i] = str_replace("<o:p></o:p>", "", $pMatch[$i]);
            
            //find out yellow and red color in <p>
            $redPos = strpos($pMatch[$i], ":red");
            $quaPos = strpos($pMatch[$i], "aqua");
            $yelPos = strpos($pMatch[$i], "yellow");
                                        
            if($quaPos){                                     //background color = aqua
                $oQuote = strrpos($pMatch[$i], "["); 
                $cQuote = strrpos($pMatch[$i], "]");
                $long   = $cQuote - $oQuote;
                
                $id = substr($pMatch[$i], $oQuote, $long);
                $id = strip_tags($id);
                $id = str_replace(array("Đ", "à"), 'D', $id);
                $id = preg_replace('/\W/', '', $id);

                //replace old <a> by new <a> 
                $data = Document::where('id', $id)->first();
                if($data)
                    $link = '<a target="_blank" href="'.url('/document/'.$id).'">';
                else
                    $link = '<a data-fancybox data-type="ajax" data-src="'.url('/document/ajaxDieuKhoan/'.$id).'" href="javascript:;">';
                $pMatch[$i] = preg_replace("/<a(.*?)>/si",$link,$pMatch[$i]);

                //remove "-> XD.TT-PPP3" token
                $endA       = strrpos($pMatch[$i], "/a>")+3;
                $pMatch[$i] = substr($pMatch[$i], 0, $endA);
            }elseif($yelPos){                                 //background color = yellow
                //....<span style="color:#00B050"> DIEU 11 </span>..<span style="background-color: yello">[ ID ]</span>
                $numbs  = $this->CountColor($pMatch[$i],'yellow');
                $yelPos = strpos($pMatch[$i], "yellow");
                $oQuote = strpos($pMatch[$i], "[", $yelPos); 
                $cQuote = strpos($pMatch[$i], "]", $yelPos); 
                $starCo = 0;

                for($j=0; $j<1; $j++){
                    $long   = $cQuote - $oQuote;
                    $id = substr($pMatch[$i], $oQuote, $long);
                    $id = strip_tags($id);
                    $id = str_replace("Đ", 'D', $id);
                    $id = preg_replace('/\W/', '', $id);

                    //insert <a> into string by cuting string into 2 part
                    //begin first part from beginning to [ 
                    $firstP = substr($pMatch[$i], 0, $oQuote);

                    $coloPos=strpos($firstP, "00B050", $starCo);        //color: #00B050>"
                    if(!$coloPos)
                        $coloPos=strpos($firstP, "rgb", $starCo);       //color: rgb(0, 176, 80);

                    $sFontPos = strpos($firstP, "<span", $coloPos);
                    $sFontPos = strpos($firstP, ">", $sFontPos)+1;
                    $eFontPos = strpos($firstP, "</span>", $sFontPos);
                    
                    $sub1 = substr($firstP, 0, $sFontPos);
                    $sub2 = substr($firstP, $sFontPos, ($eFontPos - $sFontPos));
                    $sub3 = substr($firstP, $eFontPos);
                    
                    $sub1 = $sub1 . "<a href='".url("/document/".$id)."'/>";
                    $sub2 = $sub2 . "</a>";
                    
                    $firstP = $sub1 . $sub2 . $sub3;
                    //end first part      
                    
                    //second part from ] to the end of string
                    $secondP = substr($pMatch[$i], $cQuote+1);
                    $pMatch[$i] = $firstP.$secondP;                 //remove [ID] and insert <a> to DIEU 11                         

                    //update position to find next id in one <p>....</p>
                    $yelPos = strpos($pMatch[$i], "yellow", $yelPos+10);    //+10 to not find itself
                    $oQuote = strpos($pMatch[$i], "[", $yelPos); 
                    $cQuote = strpos($pMatch[$i], "]", $yelPos); 
                    $coloPos=strpos($firstP, "00B050", $starCo+10);         //+10 to not find itself
                    $starCo = $coloPos+20;     
                }
            } elseif ($redPos) {                       //text color = red
                $first  = "";
                $second = $pMatch[$i];
                do {
                    //get the id "59/2015/NĐ-CP" of document and insert <a href=id>
                    $second = $this->InsertAtag($second);
                    /**
                    * determine if <p> have another "59/2015/NĐ-CP"
                    * and cut the second part to insert <a>
                    */
                    $redPos = strpos($second, ":red");       //research red position after insert <a>
                    if ($first) {
                        $first  = $first .":red";
                    }
                    $first  = $first . substr($second, 0, $redPos);
                    $second = substr($second, $redPos+4, strlen($second));
                } while (strpos($second, ":red"));
                $pMatch[$i] = $first .":red" . substr($second, 0, $redPos);
            }

            if ($redPos)
                $pMatch[$i] = str_replace('replace', 'style="color:red"', $pMatch[$i]);

            $quaPos = null;
            $redPos = null;
            $yelPos = null;
            $content= $content . $pMatch[$i];

        } //end for loop $pMatch

            //if a document has <h3>Văn bản pháp lý: <span=red>*Nghị định số 59/2015/NĐ-CP*</span></h3>
        $vbpl = '';
        if (strpos($str, "<h3")) {
            preg_match_all("/<h3(.*?)<\/h3>/si", $str, $h3);
            $h3 = $h3[0][0];

            //if have * *
            /*$sPos = strpos($h3, '*');
            $ePos = strpos($h3, "*", $sPos + 2);            //+2 to avoid find itself
            
            //get the ID of document between *...*
            $id = substr($h3, $sPos+1, ($ePos-$sPos)-1);
            $id = strip_tags($id);
            $id = $this->ExtractID($id);
            
            $first = substr($h3, 0, $sPos+1);   //cut string from 0 to first *
            $third = substr($h3, $ePos);        //cut string from second * to the end
            
            $vbpl = $first . $id . $third;*/
            //

            $eAPos  = strpos($h3, '</a>') + 4;
            
            $first  = substr($h3, 0, $eAPos);
            $second = substr($h3, $eAPos);

            //$id = strip_tags($second);
            //$id = $this->ExtractID($id);
            $id = "<span class='error'><i> VBPL </i></span>";

            $vbpl = $first . $id . "</h3>";
        }
        return $vbpl . $content;
    }//end ProcessContent($str)

    /**
     * get the id "59/2015/NĐ-CP"
     * inset <a href=id> into string by red position
     * @param string $str
     * @return string
     */
    private function InsertAtag($str)
    {
        //get the id by cut string from 'red' to '</span'
        $redPos = strpos($str, "red");              //find red position
        $redPos = strpos($str, ">", $redPos)+1;     //find close tag of '<span' from red position
        $endSpan= strpos($str, "</span", $redPos);  //find '</span' from red position
        $length = $endSpan - $redPos;
        $subStr = substr($str, $redPos, $length); 
        $id     = $this->ExtractID($subStr);
        $id     = $this->vn_str_filter($id);
        //insert <a> into <p> between <span>....</span>
        $strFirst = substr($str, 0, $redPos);
        
        $data = Document::where('id', $id)->first();
        if($data)             
            $subStr   = '<a target="_blank" href="'.url('/document/'.$id).'" replace>'.$subStr.'</a>'; 
        else       
            $subStr   = '<a target="_blank" data-fancybox data-type="ajax" data-src="'.url('/document/ajaxDieuKhoan/'.$id).'" replace href="'.$id.'">'.$subStr.'</a>';        
        
        $length   = strlen($str) - $endSpan;
        $strLast  = substr($str, $endSpan, $length);  
         
        return $strFirst.$subStr.$strLast;        
    }
    
    /**
    * extract id "59/2015/NĐ-CP" of document in short string
    * @param string $str string to handle
    * @return string $str
    */  
    private function ExtractID($str)
    {
        $str = $this->RemoveNBSP($str);             //decode some vietnamese character
        
        if(strpos($str, "uật")){            
            if(strpos($str, "Đầu")){
                $sub = preg_replace('/\D/','',$str);
                $sub = substr($sub, 4);
                $add = '';
                
                if(strpos($str, "phụ"))
                    $add = 'phuluc';
                
                if(strpos($str, "công"))
                    return "LDTC".$add.$sub;
                else
                    return "LDT".$add.$sub;
            }
            
            $arrL = Acronym::where('type','like','luat')->get();
            foreach ($arrL as $law) {
                if(stripos($str, $law->search)){
                    $str = preg_replace('/\D/','',$str);
                    $str = substr($str, 4);
                    return $law->acronym.$str;
                }
            }
            
        } elseif (stripos($str, "hông")) {
            return $this->stringToID($str, "thongtu");
        } elseif(stripos($str, "ghị")) {
            return $this->stringToID($str, "nghidinh");            
        } elseif(strpos($str, "quyết")) {
            return $this->stringToID($str, "quyetdinh");
        }
    }
    
    /**
     * count all position of one color
     * @param color, string
     * @return array of position
     */
    private function CountColor($str, $color)
    {
        $pos   = null;
        $i      = 0;
        $start  = 0;
        
        do{
            $pos = stripos($str, $color, $start);
            if($pos){
                $i++;
                $start = $pos + 10;                 //+10 to not find itself
            }
        }while($pos);
            
        return $i;
    }
    
    /**
    * remove special character: &nbsp;
    * @param string $str string contain html character
    * @return string is processed
    */
    private function RemoveNBSP($str)
    {
        //$str = htmlentities($str, null, 'utf-8');
        $str = str_replace("&nbsp;", "", $str);
        $str = html_entity_decode($str);
        $str = str_replace("&acirc;", "â", $str);
        return $str;
    }  
    
    /**
    * convert vietnamese character
    * @param string $str vietnamese string
    * @return string without Diacritic marks (Diacredical Marks)
    */
    private function vn_str_filter($str)
    {
        $unicode = [
            'a'=>'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ',
            'd'=>'đ',
            'e'=>'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',
            'i'=>'í|ì|ỉ|ĩ|ị',
            'o'=>'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',
            'u'=>'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',
            'y'=>'ý|ỳ|ỷ|ỹ|ỵ',
            'A'=>'Á|À|Ả|Ã|Ạ|Ă|Ắ|Ặ|Ằ|Ẳ|Ẵ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',
            'D'=>'Đ',
            'E'=>'É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',
            'I'=>'Í|Ì|Ỉ|Ĩ|Ị',
            'O'=>'Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',
            'U'=>'Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',
            'Y'=>'Ý|Ỳ|Ỷ|Ỹ|Ỵ',
            'P'=>'p'
        ];
        
       foreach($unicode as $nonUnicode=>$uni){
            $str = preg_replace("/($uni)/i", $nonUnicode, $str);
       }
        return $str;
    }

    /**
    * Find acronym(in document's name) by acronym(defined in db)
    * @param string $str full document's name
    * @param string $type type of document
    * @return string - ID of document
    */
    private function stringToID($str, $type)
    {
        $arrL = Acronym::where('type','like',$type)->get();         //get all acronym in db
        foreach ($arrL as $law) {
            if (stripos($str, $law->search)) {                      //if isset acronym(`search` field in db) in document's name
                $sub    = '';
                $exPos  = stripos($str, 'iều');                     //finding addendum in document's name
                if ($exPos) {
                    $sub = substr($str, $exPos);
                    $sub = preg_replace('/\D/','',$sub);
                } elseif ($law->extra != null) {                    //if that acronym has addendum stored in db
                    $extra = [];
                    if (stripos($law->extra, ',')) {                //if that acronym has multi addendum
                        $extra = explode(',', $law->extra);
                    } else {
                        $extra[0] = $law->extra;
                    }
                    foreach ($extra as $ex) {
                        $exPos = stripos($str, $ex);
                        if($exPos){                                 //if isset addendum(`extra` field stored in db) in document's name
                            $sub = substr($str, $exPos);
                            $sub = preg_replace('/\s/','',$sub);
                            $sub = $this->vn_str_filter($sub);
                            break;
                        }
                    }
                }
                $str = substr($str, 0, $exPos);
                $str = preg_replace('/\D/','',$str);
                return $law->acronym.$str.$sub;
            }
        }
    }
}

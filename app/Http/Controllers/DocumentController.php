<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Document;
use App\Acronym;

use App\Http\Requests;

class DocumentController extends Controller
{
    //
    public function document($id)
    {
        
    }
    
    public function ajaxDieuKhoan($id=0)
    {
        if(!$id) return;        
       
        $id  = rawurldecode($id);
        $arr = Document::where('id', $id)->first();
        
        if($arr){
            //echo $arr->content;
        }
        else
            echo '<div class="WordSection1">Dữ liệu ' . $id . " đang được cập nhật</div>";
    }
    
    public function ajaxThutuc($id)
    {
        if(!$id) return;        
        
        $id  = rawurldecode($id);
        $arr = Document::where('id', $id)->first();
        
        if($arr){
            $content = $this->ProcessContent($arr->content);
            return view('document', ['content' => $content]);
        }
        else
            echo '<div class="WordSection1">Dữ liệu ' . $id . " đang được cập nhật</div>";
    }    
    
    /**
     * remove token and insert link to another document
     * @param string $str content need to insert link
     * @return string
     */
    private function ProcessContent($str)
    {        
        //store yellow/red text position
        $yelPos = 0;
        $redPos = 0;
        
        //store ID 'XD.TT-PPP3' finded in <span> tag
        $idXDTTPPP = 0;
        
        //extract all <p> tag into $pMatch
        preg_match_all("/<p(.*?)<\/p>/si", $str, $pMatch);
        
        $str     = '';
        $pMatch  = $pMatch[0];
        $pLength = count($pMatch);
        for($i = 0; $i<$pLength; $i++){
            $pMatch[$i] = str_replace("<o:p></o:p>", "", $pMatch[$i]);
            
            //find out yellow and red color in <p>
            $yelPos = strrpos($pMatch[$i], "yellow;");
            $redPos = strrpos($pMatch[$i], "red");
            
            //if <p> contain yellow background and red color
            if($redPos && $yelPos){                
                //cut '123' id from 'XD.TT-123'
                $xdttPos    = strrpos($pMatch[$i], "XD.TT-")+6;         //search  from end to beginning
                $endSpanPos = strpos($pMatch[$i], "</span", $xdttPos);  //search from id's position to end
                $long       = $endSpanPos - $xdttPos;
                $idXDTTPPP  = substr($pMatch[$i], $xdttPos, $long);

                //add 'XD.TT-PPP3' link to <a> tag 
                // $link       = '<a data-fancybox data-type="ajax" data-src="document/ajaxThutuc/'.$idXDTTPPP.'" href="'.$idXDTTPPP.'">';
                $link       = '<a data-fancybox data-type="ajax" data-src="document/ajaxThutuc/'.$idXDTTPPP.'" href="javascript:;">';
                $pMatch[$i] = preg_replace("/<a(.*?)>/si",$link,$pMatch[$i]);
                
                //remove "-> XD.TT-PPP3" token
                $iSpan = 0;
                //--searching from last to start
                while($seek = strrpos($pMatch[$i], "<span")){
                    if($iSpan == 5) break;
                    $pMatch[$i] = substr($pMatch[$i], 0, $seek);
                    $iSpan++;
                }
            }
            
            //get the id "59/2015/NĐ-CP" of document and insert <a href=id>
            if(!$yelPos && $redPos){
                $pMatch[$i] = $this->InsertAtag($pMatch[$i]);
                
                //determine if <p> have another "59/2015/NĐ-CP"
                //and cut the second part to insert <a>
                $redPos = strpos($pMatch[$i], "red");       //research red position after insert <a>
                $first  = substr($pMatch[$i], 0, $redPos);
                $second = substr($pMatch[$i], $redPos+3, strlen($pMatch[$i]));
                //insert <a> between first and second part if find red color
                if(strpos($second, "red")){                    
                    $second     = $this->InsertAtag($second);                    
                    $pMatch[$i] = $first."red".$second;
                }
            }
            
            //if($redPos)
                //$pMatch[$i] = str_replace('replace', 'style="color:red"', $pMatch[$i]);
            
            $yelPos = 0;
            $redPos = 0;            
            $str    = $str.$pMatch[$i];
        } //end loop $pMatch
        
        return $str;
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
        
        //insert <a> into <p> between <span>....</span>
        $strFirst = substr($str, 0, $redPos);
        
        $data = Document::where('id', $id)->first();
        if($data)             
            $subStr   = '<a target="_blank" id="'.$id.'">'.$subStr.'</a>';
        $subStr   = '<a target="_blank" id="'.$id.'" class="linktab" >'.$subStr.'</a>';
        
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
         $str = $this->RemoveNBSP($str); //decode some vietnamese character
         
        if(strpos($str, "NĐ-CP")){
            $str = str_replace(array("điều","khoản","Nghị","định","NĐ-CP"), '', $str);
            $str = preg_replace('/\W/','',$str);
            return "ND".$str;
        }
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
            
            $arrL = Acronym::all();
            foreach ($arrL as $law) {
                if(strpos($str, $law->search)){
                    $str = preg_replace('/\D/','',$str);
                    $str = substr($str, 4);
                    return $law->acronym.$str;
                }
            }
        }
        if(strpos($str, "TT-BXD")){
            $str = str_replace(array("Thông","tư","TT-BXD","điều","khoản"), '', $str);
            $str = preg_replace('/\W/','',$str);
            return "BXD".$str;
        }
        if(strpos($str, "TT-BKHĐT")){
            $str = str_replace(array("Thông","tư","TT-BKHĐT","điều","khoản"), '', $str);
            $str = preg_replace('/\W/','',$str);
            return "BKH".$str;
        }
        if(strpos($str, "TT-BLĐTBXH")){
            $str = str_replace(array("Thông","tư","TT-BLĐTBXH"), '', $str);
            $str = preg_replace('/\W/','',$str);
            return "BLDTBXH".$str;
        }
        if(strpos($str, "TT-BTC")){
            $str = str_replace(array("Thông","tư","TT-BTC","điều","khoản"), '', $str);
            if(strpos($str, "Mức")){
                $str  = str_replace(array("Bảng","Mức","thu"), '', $str);
                $str .= "BIEUPHI";
            }
            $str = preg_replace('/\W/','',$str);        
            return "BTC".$str;
        }
        if(strpos($str, "TTLT-BKHĐT")){
            if($luc = strpos($str, "lục")){
                $num  = substr($str, $luc+5);
                $str  = substr($str, 0, $luc);
                $str  = str_replace(array("Phụ","lục"), '', $str);
                $str .= "PL".$num;
            }
            $str = str_replace(array("Thông","tư","TTLT-BKHĐT-BTC"), '', $str);
            $str = preg_replace('/\W/','',$str);
            return "LBVMT".$str;
        }
        if(strpos($str, "QĐ-BXD")){
            if($pos = strpos($str, "số")){
                //Bảng số 1 or Bảng số 14
                //get number 1
                $num  = substr($str, $pos+5, 1);
                //get pos of number 4
                $p = $pos+6;
                $n  = substr($str, $p, 1);
                //check if next character equal 4 or not
                if($n==4){
                    $num .= $n;
                    $p++;
                }
                //split string from number to the end
                $str  = substr($str, $p);
                $str  .= "B".$num;
            }
            //if contain Phu luc kem theo
            if(strpos($str, "lục")){
                $str  = str_replace(array("Phụ","lục","kèm","theo"), '', $str);
                $str .= "PL";
            }
            //phần I mục 9.1
            if($posM = strpos($str, "mục")){                
                $phan = substr($str, $posM-2, 1);   //get I
                $muc  = substr($str, $posM+5, 6);   //get 9.1
                $str  = str_replace(array("Phần","mục"), '', $str);
                $str  = substr($str, $posM+8);
                $str .= "P".$phan."M".$muc;
            }
            $str = str_replace(array("Quyết","định","QĐ-BXD"), '', $str);
            $str = preg_replace('/\W/','',$str);
            return "QDBXD".$str;
        }
        return "";
    }//end ExtractID($str)   
    
    /**
    * remove special character: &nbsp;
    * @param string $str string contain html character
    * @return string is processed
    */
    private function RemoveNBSP($str)
    {
        $str = htmlentities($str, null, 'utf-8');
        $str = str_replace("&nbsp;", "", $str);
        $str = html_entity_decode($str);
        return $str;
    }     
}

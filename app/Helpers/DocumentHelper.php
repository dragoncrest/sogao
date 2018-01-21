<?php
use App\Document;
use App\Acronym;
use App\Models\UserCoin;
use App\Models\UsersDocument;

class DocumentHelper
{
    private static $coin = 0;
    private static $buyedDocuments = '';

    /**
     * processing content of document
     * @param string $str content of file
     * @param bool $hasTable check content has <table> or not
     * @return string
     */
    public static function ProcessContent($doc, $hasTable)
    {
        // get buyed document and coin of login user
        if (Auth::user()) {
            $uCoin                = UserCoin::where('user_id', Auth::user()->id)->first();
            self::$coin           = $uCoin->coin;
            self::$buyedDocuments = UsersDocument::where('user_id', Auth::user()->id)->get();
        }
        // process document's content
        $content = $doc->content;
        if (!$hasTable) {
            return self::ProcessDocumentId($content);
        } else {
            $sTablePos  = strpos($content, '<table');
            $secondPart = $content;
            $content    = $firstPart = '';
            while ($sTablePos) {
                $firstPart  = substr($secondPart, 0, $sTablePos);
                $firstPart  = self::ProcessDocumentId($firstPart);
                $eTablePos  = strpos($secondPart, '</table', $sTablePos) + 8;
                $table      = substr($secondPart, $sTablePos, ($eTablePos - $sTablePos));
                $secondPart = substr($secondPart, $eTablePos);
                $sTablePos  = strpos($secondPart, '<table');
                $content   .= $firstPart . $table;
            }
        }
        return $content . $secondPart;
    } //end ProcessContent($content, $hasTable)

    /**
     * remove token and insert link to document
     * @param string $str content need to insert link
     * @return string
     */
    public static function ProcessDocumentId($str)
    {
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

        for($i = 0; $i < $pLength; $i++){
            //$pMatch[$i] = str_replace("<o:p></o:p>", "", $pMatch[$i]);

            //find out color in <p>
            $redPos = strpos($pMatch[$i], "red");
            $quaPos = strpos($pMatch[$i], "aqua");
            $yelPos = strpos($pMatch[$i], "yellow");

            if ($quaPos) {                                                  //background color = aqua
                $pMatch[$i] = self::ProcessAquaText($pMatch[$i]);
            } elseif($yelPos) {                                             //background color = yellow
                $pMatch[$i] = self::ProcessYellowText($pMatch[$i]);
            } elseif ($redPos && empty(strpos($pMatch[$i], "*"))) {         //text color = red
                $pMatch[$i] = self::ProcessRedText($pMatch[$i]);
            }

            if ($redPos)
                $pMatch[$i] = str_replace('replace', 'style="color:red"', $pMatch[$i]);

            $quaPos  = null;
            $redPos  = null;
            $yelPos  = null;
            $content = $content . $pMatch[$i];

        } //end for loop $pMatch

        //if a document has <h3>Văn bản pháp lý: <span=red>*Nghị định số 59/2015/NĐ-CP*</span></h3>
        $vbpl = '';
        if (strpos($str, "<h3")) {
            preg_match_all("/<h3(.*?)<\/h3>/si", $str, $h3);
            $h3 = $h3[0][0];
            $vbpl = $h3;
        }
        return $vbpl . $content;
    }//end ProcessDocumentId($str)

    /**
     * get the id "59/2015/NĐ-CP"
     * inset <a href=id> into string by red position
     * @param string $str
     * @return string
     */
    public static function InsertAtag($str)
    {
        //get the id by cut string from 'red' to '</span'
        $redPos = strpos($str, "red");              //find red position
        $redPos = strpos($str, ">", $redPos)+1;     //find close tag of '<span' from red position
        $endSpan= strpos($str, "</span", $redPos);  //find '</span' from red position
        $length = $endSpan - $redPos;
        $subStr = substr($str, $redPos, $length);   //original name of document
        $id     = self::ExtractID($subStr);
        $id     = self::vn_str_filter($id);
        //insert <a> into <p> between <span>....</span>
        $strFirst = substr($str, 0, $redPos);

        $data   = Document::where('id', $id)->first();
        //check user buyed or not buy document, has coin or not
        $result = self::checkUserStatus($data, $subStr);
        if (!empty($data)) {
            $subStr =
                '<a replace onclick="checkUserStatus(\''.$result['id'].'\', \''.$result['status'].'\')">'.
                    $result['str'].
                '</a>';
        } else {
            $subStr =
                '<a target="_blank" data-fancybox data-type="ajax" data-src="'.url('/document/ajaxDieuKhoan/'.$id).'" replace href="'.$id.'">'.
                    CDPL.
                '</a>';
        }
        
        $length   = strlen($str) - $endSpan;
        $strLast  = substr($str, $endSpan, $length);
         
        return $strFirst.$subStr.$strLast;
    }//end InsertAtag($str)

    /**
    * extract id "59/2015/NĐ-CP" of document in short string
    * @param string $str string to handle
    * @return string $str
    */  
    public static function ExtractID($str)
    {
        $str = self::RemoveNBSP($str);             //decode some vietnamese character

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
            return self::stringToID($str, "thongtu");
        } elseif(stripos($str, "ghị")) {
            return self::stringToID($str, "nghidinh");
        } elseif(strpos($str, "uyết")) {
            return self::stringToID($str, "quyetdinh");
        }
    }//end ExtractID($str)

    /**
     * count all position of one color
     * @param color, string
     * @return array of position
     */
    public static function CountColor($str, $color)
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
    }//end CountColor($str)

    /**
    * remove special character: &nbsp;
    * @param string $str string contain html character
    * @return string is processed
    */
    public static function RemoveNBSP($str)
    {
        //$str = htmlentities($str, null, 'utf-8');
        $str = str_replace(["&nbsp;", "."], "", $str);
        $str = preg_replace( "/\r|\n/", " ", $str );
        $str = html_entity_decode($str);
        $str = str_replace("&acirc;", "â", $str);
        return $str;
    }//end RemoveNBSP($str)

    /**
    * convert vietnamese character
    * @param string $str vietnamese string
    * @return string without Diacritic marks (Diacredical Marks)
    */
    public static function vn_str_filter($str)
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
    }//end vn_str_filter($str)

    /**
    * Find acronym(in document's name) by acronym(defined in db)
    * @param string $str full document's name
    * @param string $type type of document
    * @return string - ID of document
    */
    public static function stringToID($str, $type)
    {
        $arrL = Acronym::where('type','like',$type)->get();         //get all acronym in db
        foreach ($arrL as $law) {
            $lawPos = stripos($str, $law->search);
            if ($lawPos) {                                          //if isset acronym(`search` field in db) in document's name
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
                        if ($exPos) {                                 //if isset addendum(`extra` field stored in db) in document's name
                            $sub = substr($str, $exPos);
                            $sub = preg_replace('/\s/','',$sub);
                            $sub = self::vn_str_filter($sub);
                            break;
                        }
                    }
                } 
                if (!$exPos) {
                    $exPos = $lawPos;
                }
                $str = substr($str, 0, $exPos);
                $str = preg_replace('/\D/','',$str);
                return $law->acronym.$str.$sub;
            }
        }
    } //end stringToID($str, $type)

    public static function ProcessRedText($str)
    {
        $first  = "";
        $second = $str;
        do {
            //get the id "59/2015/NĐ-CP" of document and insert <a href=id>
            $second = self::InsertAtag($second);
            /**
            * determine if <p> have another "59/2015/NĐ-CP"
            * and cut the second part to insert <a>
            */
            $redPos = strpos($second, "red");       //research red position after insert <a>
            if ($first) {
                $first  = $first ."red";
            }
            $first  = $first . substr($second, 0, $redPos);
            $second = substr($second, $redPos+3, strlen($second));
        } while (strpos($second, ":red"));
        return $first .":red" . substr($second, 0, $redPos);
    } // end ProcessRedText($str)

    public static function ProcessAquaText($str)
    {
        $oQuote = strrpos($str, "["); 
        $cQuote = strrpos($str, "]");
        $long   = $cQuote - $oQuote;
        
        $id = substr($str, $oQuote, $long);
        $id = strip_tags($id);
        $id = str_replace(array("Đ", "à"), 'D', $id);
        $id = preg_replace('/\W/', '', $id);

        //replace old <a> by new <a> 
        $data = Document::where('id', $id)->first();
        if($data)
            $link = '<a target="_blank" href="'.url('/document/'.$id).'">';
        else
            $link = '<a data-fancybox data-type="ajax" data-src="'.url('/document/ajaxDieuKhoan/'.$id).'" href="javascript:void(0);">';
        $str = preg_replace("/<a(.*?)>/si",$link,$str);

        //remove "-> XD.TT-PPP3" token
        $endA = strrpos($str, "/a>")+3;

        return substr($str, 0, $endA);
    } // end ProcessAquaText($str)

    public static function ProcessYellowText($str)
    {
        //....<span style="color:#00B050"> DIEU 11 </span>..<span style="background-color: yello">[ ID ]</span>
        $numbs  = self::CountColor($str,'yellow');
        $yelPos = strpos($str, "yellow");
        $oQuote = strpos($str, "[", $yelPos); 
        $cQuote = strpos($str, "]", $yelPos); 
        $starCo = 0;

        for($j=0; $j<1; $j++){
            $long   = $cQuote - $oQuote;
            $id = substr($str, $oQuote, $long);
            $id = strip_tags($id);
            $id = str_replace("Đ", 'D', $id);
            $id = preg_replace('/\W/', '', $id);

            //insert <a> into string by cuting string into 2 part
            //begin first part from beginning to [ 
            $firstP = substr($str, 0, $oQuote);

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
            $secondP = substr($str, $cQuote+1);
            $str = $firstP.$secondP;                 //remove [ID] and insert <a> to DIEU 11                         

            //update position to find next id in one <p>....</p>
            $yelPos = strpos($str, "yellow", $yelPos+10);    //+10 to not find itself
            $oQuote = strpos($str, "[", $yelPos); 
            $cQuote = strpos($str, "]", $yelPos); 
            $coloPos=strpos($firstP, "00B050", $starCo+10);         //+10 to not find itself
            $starCo = $coloPos + 20;
        }
        return $str;
    } // end ProcessYellowText($str)

    /**
    * check if user logged in or have coin or buyed documents to display link
    * @param array $doc document
    * @param string $name link's name display in content
    * @return array
    */
    public static function checkUserStatus($doc, $name)
    {
        $status = $id = '';
        $str    = CDPL;
        if (!empty($doc)) {
            if (!Auth::user()) {
                $status = LOGIN;
            } else {
                $isBuyed = false;
                foreach (self::$buyedDocuments as $key => $buyedDoc) {
                    if ($buyedDoc->document_id == $doc->stt) {
                        $isBuyed = true;
                        break;
                    }
                }
                if ($isBuyed) {
                    $id     = $doc->id;
                    $str    = $name;
                    $status = BUYED;
                } elseif (self::$coin > 0) {
                    $id     = $doc->id;
                    $status = BUY;
                } elseif (!self::$coin) {
                    $status = NOTCOIN;
                }
            }
        }
        return [
            'id'     => $id,
            'str'    => $str,
            'status' => $status
        ];
    }
}// end DocumentHelper
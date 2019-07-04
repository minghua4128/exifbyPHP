<?PHP
//原始檔案目錄
$InF = "C:\\exif\\pic\\";
//輸出檔案目錄
$OutF = "C:\\exif\\output\\";
//是否保留原始檔案 (0=不保留;1=保留)
$IsSafeMode = 1;
error_reporting(E_ALL || ~E_NOTICE);
$Counts=0;
$StartTime = microtime(true);
if (is_dir($InF)) {
  if ($dh = opendir($InF)) {
    while (($file = readdir($dh)) !== false) {
      if (filetype($InF.$file) == "file"){
        //cameraUsed($InF.$file);
        $exif_ifd0 = read_exif_data($InF.$file ,'IFD0' ,0); 
        echo " 處理中 ".$InF.$file."\r\n";      
        if ($exif_ifd0['DateTime'] != "") {
          //echo $exif_ifd0['DateTime']."\r\n";
          $time1 = explode(" ",$exif_ifd0['DateTime']);
        }else{
          $filetime = date("Y:m:d H:i:s",filemtime($InF.$file));
          $time1 = explode(" ",$filetime);
        }
        $time2 = explode(":",$time1[0]);
        $time3 = explode(":",$time1[1]);
        $yy = $time2[0];
        $mm = $time2[1];
        $dd = $time2[2];
        $hh = $time3[0];
        $ii = $time3[1];
        $ss = $time3[2];
        //開始處理
        if(!is_dir($OutF.$yy.$mm.$dd)){
          mkdir($OutF.$yy.$mm.$dd);
        }
        $SubFileName = explode(".",$file);
        if ($IsSafeMode == 1){
          copy($InF.$file,$OutF.$yy.$mm.$dd."\\".$hh.$ii.$ss.".".$SubFileName[1]);
        }elseif ($IsSafeMode == 0){
          rename($InF.$file,$OutF.$yy.$mm.$dd."\\".$hh.$ii.$ss.".".$SubFileName[1]);
        }
        echo "原始檔案:".$InF.$file."\r\n";
        echo "輸出檔案:".$OutF.$yy.$mm.$dd."\\".$hh.$ii.$ss.".".$SubFileName[1]."\r\n";
        $Counts++;
      } 
    }
    $EndTime = microtime(true);
    echo "總共處理了 ".$Counts." 個檔案 總共使用 ".($EndTime-$StartTime)." 秒\r\n";
    closedir($dh);
  }
}
?>
<?php

#--------------- GetPageURL ---------------#

if($_GET['page_url'])
{
   $Link_SPL = preg_split("/\//" , $_GET['page_url']);
   $MovieCode =  $Link_SPL[4];

   $Cookie = "----- Filimo Cookie Account -----";

   FilimoLeecehr($MovieCode , $Cookie);
}

#--------------- GetPageURL ---------------#

#--------------- FilimoLeecher ---------------#

function FilimoLeecehr($MovieCode , $Cookie)
{

# ------------------ Main Download Filimo ---------------- #

   $URL_Dwonload_Main_Filimo = "https://www.filimo.com/movie/movie/download_manager/uid/".$MovieCode."?details-v2=true";
   
   $REQ_Dwonload_Main_Filimo = curl_init($URL_Dwonload_Main_Filimo);
   curl_setopt($REQ_Dwonload_Main_Filimo, CURLOPT_URL, $URL_Dwonload_Main_Filimo);
   curl_setopt($REQ_Dwonload_Main_Filimo, CURLOPT_RETURNTRANSFER, true);
   
   $Headers_Dwonload_Main_Filimo = array(
      "cookie:".$Cookie,
   );

   curl_setopt($REQ_Dwonload_Main_Filimo, CURLOPT_HTTPHEADER, $Headers_Dwonload_Main_Filimo);
   curl_setopt($REQ_Dwonload_Main_Filimo, CURLOPT_SSL_VERIFYHOST, false);
   curl_setopt($REQ_Dwonload_Main_Filimo, CURLOPT_SSL_VERIFYPEER, false);
   
   $Respone_Dwonload_Main_Filimo = curl_exec($REQ_Dwonload_Main_Filimo);

   preg_match_all('"href=\"filimoDM://(.*?)\""', $Respone_Dwonload_Main_Filimo , $Dwonload_Main_Link);
   preg_match_all('"دانلود با کیفیت(.*?)p"', $Respone_Dwonload_Main_Filimo , $Quality_Name);

$DwonloadMainLink = 0;

foreach($Dwonload_Main_Link[1] as $item) {
   $DwonloadMainLink++;
   $DownLinkMain[$DwonloadMainLink] = "filimoDM://".$item;
}

$QualityCount = 0;

foreach($Quality_Name[1] as $item) {
   $QualityCount++;
   $QualityName[$QualityCount] = $item;
}


for($C = 1; $C < $QualityCount-1; $C++)
{
   $DownLinkMain[$C] = $DownLinkMain[$C];
   $QualityName[$C] = $QualityName[$C];
}

   #preg_match('"href=\"filimoDM://(.*?)\""' , $Respone_Dwonload_Main_Filimo , $Dwonload_Main_Link_1);
   #echo $Dwonload_Main_Link_1[1];



# ------------------ Main Download Filimso ---------------- #

# ------------------ Download Filimo ---------------- #

   #preg_match('"class=\"ds-media_image lazyload lazyloading\" data-src=\"(.*?)\""' , $Filimo_Respone , $IMG_Respone_2);
   #$Poster_M_Main = $IMG_Respone_2[1];

   $URL_Dwonload_Filimo = "https://www.filimo.com/w/".$MovieCode;
   
   $REQ_Dwonload_Filimo = curl_init($URL_Dwonload_Filimo);
   curl_setopt($REQ_Dwonload_Filimo, CURLOPT_URL, $URL_Dwonload_Filimo);
   curl_setopt($REQ_Dwonload_Filimo, CURLOPT_RETURNTRANSFER, true);
   
   $Headers_Dwonload_Filimo = array(
      "cookie:".$Cookie,
   );

   curl_setopt($REQ_Dwonload_Filimo, CURLOPT_HTTPHEADER, $Headers_Dwonload_Filimo);
   curl_setopt($REQ_Dwonload_Filimo, CURLOPT_SSL_VERIFYHOST, false);
   curl_setopt($REQ_Dwonload_Filimo, CURLOPT_SSL_VERIFYPEER, false);
   
   $Respone_Dwonload_Filimo = curl_exec($REQ_Dwonload_Filimo);
   
   preg_match('"name=\"DC.Title\" content=\"(.*?)\""' , $Respone_Dwonload_Filimo , $Name_Respone);
    $MovieName = $Name_Respone[1];

    #preg_match('"property=\"og:image\" content=\"(.*?)\""' , $Respone , $IMG_Respone_2);
    #$Poster_N_Main = $IMG_Respone_2[2];

    preg_match('"src\":\"(.*?)\""' , $Respone_Dwonload_Filimo , $DonwloadLink);
    $Download_Link_Reg = $DonwloadLink[1];
    $Download_Link = str_replace("\\","", $Download_Link_Reg);
 
    preg_match('"poster\":\"(.*?)\""' , $Respone_Dwonload_Filimo , $IMG_Respone_1);
    $Poster_BIG_Main_Reg = $IMG_Respone_1[1];
    $Poster_BIG_Main = str_replace("\\","", $Poster_BIG_Main_Reg);

# ------------------ Download Filimo ---------------- #

   echo(json_encode(array(
      'MovieName' => $MovieName ,
      'Poster_BIG_Main' => $Poster_BIG_Main ,
      #'Poster_M_Main' => $Poster_M_Main ,
      'DownloadLink' => $Download_Link , 
      'DownloadLinkMain' => $DownLinkMain ,
      'MainLinkQuality' => $QualityName , 
      'Developer' => "AGC007"
      )));
      
}


#--------------- FilimoLeecher ---------------#
?>

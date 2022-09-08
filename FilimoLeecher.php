<?php

#--------------- GetPageURL ---------------#

if($_GET['page_url'])
{
   $Link_SPL = preg_split("/\//" , $_GET['page_url']);
   $MovieCode =  $Link_SPL[4];

   $Cookie = " ---- Filimo Account Cookie -----";

   FilimoLeecehr($MovieCode , $Cookie);
}

#--------------- GetPageURL ---------------#

#--------------- FilimoLeecher ---------------#

function FilimoLeecehr($MovieCode , $Cookie)
{

   $Filimo_Respone = file_get_contents("https://www.filimo.com/m/".$MovieCode);

   #preg_match('"class=\"ds-media_image lazyload lazyloading\" data-src=\"(.*?)\""' , $Filimo_Respone , $IMG_Respone_2);
   #$Poster_M_Main = $IMG_Respone_2[1];

   $URL = "https://www.filimo.com/w/".$MovieCode;
   
   $REQ = curl_init($URL);
   curl_setopt($REQ, CURLOPT_URL, $URL);
   curl_setopt($REQ, CURLOPT_RETURNTRANSFER, true);
   
   $headers = array(
      "cookie:".$Cookie,
   );

   curl_setopt($REQ, CURLOPT_HTTPHEADER, $headers);
   curl_setopt($REQ, CURLOPT_SSL_VERIFYHOST, false);
   curl_setopt($REQ, CURLOPT_SSL_VERIFYPEER, false);
   
   $Respone = curl_exec($REQ);
   
   preg_match('"name=\"DC.Title\" content=\"(.*?)\""' , $Respone , $Name_Respone);
    $MovieName = $Name_Respone[1];

    #preg_match('"property=\"og:image\" content=\"(.*?)\""' , $Respone , $IMG_Respone_2);
    #$Poster_N_Main = $IMG_Respone_2[2];

    preg_match('"src\":\"(.*?)\""' , $Respone , $DonwloadLink);
    $Download_Link_Reg = $DonwloadLink[1];
    $Download_Link = str_replace("\\","", $Download_Link_Reg);
 
    preg_match('"poster\":\"(.*?)\""' , $Respone , $IMG_Respone_1);
    $Poster_BIG_Main_Reg = $IMG_Respone_1[1];
    $Poster_BIG_Main = str_replace("\\","", $Poster_BIG_Main_Reg);

   echo(json_encode(array(
      'MovieName' => $MovieName ,
      'Poster_BIG_Main' => $Poster_BIG_Main ,
      #'Poster_M_Main' => $Poster_M_Main ,
      '' => '' ,
      'DownloadLink' => $Download_Link , 
      'Developer' => "AGC007"
      )));
}


#--------------- FilimoLeecher ---------------#
?>
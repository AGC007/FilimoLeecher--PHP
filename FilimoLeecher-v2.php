<?php

#--------------- Get Page ID ---------------#

if(isset($_REQUEST['page_url']))
{
    $Link_SPL = preg_split("/\//" , $_GET['page_url']);
    $MovieKey =  $Link_SPL[4];

    FilimoLeecher_v2($MovieKey);
}

#--------------- Get Page ID ---------------#

#--------------- Filimo Leecher ---------------#
function FilimoLeecher_v2($MovieKey)
{

    #``````````` HEADER ```````````#

    $HEADER = array(
        'Host: www.filimo.com',
        'Useragent: {"an":"Filimo","os":"tv"}',
    );

    #``````````` HEADER ```````````#

    #--------------- Get Request ---------------#

    $REQ_MOVIE_DATA = curl_init();

    curl_setopt($REQ_MOVIE_DATA, CURLOPT_URL, 'https://www.filimo.com/api/fa/v1/partner/TV/watch/uid/'.$MovieKey);
    curl_setopt($REQ_MOVIE_DATA, CURLOPT_RETURNTRANSFER, 1);
    //curl_setopt($REQ_MOVIE_DATA, CURLOPT_POSTFIELDS, "{}");
    //curl_setopt($REQ_MOVIE_DATA, CURLOPT_HEADER, 1);
    curl_setopt($REQ_MOVIE_DATA, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
    curl_setopt($REQ_MOVIE_DATA, CURLOPT_HTTPHEADER, $HEADER);
    // Timeout in seconds
    curl_setopt($REQ_MOVIE_DATA, CURLOPT_TIMEOUT, 30);

    $RES_MOVIE_DATA_RES = curl_exec($REQ_MOVIE_DATA);
    $RES_MOVIE_DATA_JS = json_decode($RES_MOVIE_DATA_RES , true);

    #--------------- Get Request ---------------#

     if($RES_MOVIE_DATA_JS['data']['type'] == 'Watch')
     {
         $IR_DOWN = false;
         #--------------- Get Movie Data ---------------#
         $MOVIE_TITLE = $RES_MOVIE_DATA_JS['data']['attributes']['movie_title'];
         $MOVIE_TITLE_EN = $RES_MOVIE_DATA_JS['data']['attributes']['movie_title_en'];
         $MOVIE_IS_SERIAL = $RES_MOVIE_DATA_JS['data']['attributes']['serial']['enable'];
         $MOVIE_SRC = $RES_MOVIE_DATA_JS['data']['attributes']['movie_src'][0][0]['src'];
         #~~~~~~~ Movie Ir Download Check ~~~~~~~#
         $MOVIE_PSEUDO = $RES_MOVIE_DATA_JS['data']['attributes']['moviePseudo'];

         if(strpos(json_encode($MOVIE_PSEUDO) , "type\":\"error"))
         {
             $IR_DOWN = false;
         }
         else
         {
             $IR_DOWN = true;
             $MOVIE_PSEUDO_COUNT = count($RES_MOVIE_DATA_JS['data']['attributes']['moviePseudo']['stream']);

             for($i=0; $i <= $MOVIE_PSEUDO_COUNT - 1; $i++)//Get Download List Link
             {
                 $dl_Movie_Quality[$i] =  $RES_MOVIE_DATA_JS['data']['attributes']['moviePseudo']['stream'][$i]['src'];
                 $dl_Movie_Link[$i] =  $RES_MOVIE_DATA_JS['data']['attributes']['moviePseudo']['stream'][$i]['src'];
             }
         }
         #~~~~~~~ Movie Ir Download Check ~~~~~~~#
         $MOVIE_PICTURE = $RES_MOVIE_DATA_JS['data']['attributes']['pic'];
         #--------------- Get Movie Data ---------------#

         #~~~~ Movie Json ~~~~#

         if($IR_DOWN == false)// Movie Ir Download Check
         {
             echo(json_encode(array(
                 'code' => http_response_code(),
                 'message' => 'success' ,
                 'IR-NET' => $IR_DOWN,
                 'developer' => 'AGC007',
                 'data' =>   array(
                     'MovieName' => $MOVIE_TITLE ,
                     'MovieNameEN' => $MOVIE_TITLE_EN ,
                     'isSeries' => $MOVIE_IS_SERIAL ,
                     'MoviePoster' => $MOVIE_PICTURE ,
                     'dl_m3u8' => array(
                         'm3u8_link' => $MOVIE_SRC ,
                         'Developer' => "AGC007"
                     )))));
         }
         else{
             echo(json_encode(array(
                 'code' => http_response_code(),
                 'message' => 'success' ,
                 'IR-NET' => $IR_DOWN,
                 'developer' => 'AGC007',
                 'data' =>   array(
                     'MovieName' => $MOVIE_TITLE ,
                     'MovieNameEN' => $MOVIE_TITLE_EN ,
                     'isSeries' => $MOVIE_IS_SERIAL ,
                     'MoviePoster' => $MOVIE_PICTURE ,
                     'dl_m3u8' => array(
                         'm3u8_link' => $MOVIE_SRC ,
                     ),
                         'dl_direct' => array(
                             'dl_direct_Quality' => $dl_Movie_Quality ,
                             'dl_direct_Link' => $dl_Movie_Link ,
                         ),
                         'Developer' => "AGC007"
                     ))));
         }

         #~~~~ Movie Json ~~~~#
         //$MOVIE_TIME = $RES_MOVIE_DATA_JS['data']['type']['attributes'];
         //$MOVIE_IMDB = $RES_MOVIE_DATA_JS['data']['type']['attributes'];
         //$MOVIE_POSTER = $RES_MOVIE_DATA_JS['data']['type']['attributes'];
         //$MOVIE_DIRECTOR = $RES_MOVIE_DATA_JS['data']['type']['attributes'];



     }
}
#--------------- FilimoLeecher ---------------#
?>
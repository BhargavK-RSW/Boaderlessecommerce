<?php

/* Template Name: Search By Image */

get_header();

define('CFG_SERVICE_INSTANCEKEY', 'opendemo');
define('CFG_REQUEST_LANGUAGE', 'en');
?>




  <form action="" method="POST" enctype="multipart/form-data">
  Select image to upload:
  <input type="file" name="fileToUpload" id="fileToUpload">
  <input type="submit" value="Upload Image" name="submit">
  </form>
  

<?php


if (isset($_FILES['fileToUpload']['tmp_name'])) 
{

    $filetmpname=$_FILES['fileToUpload']['tmp_name'];
    $filetype=$_FILES['fileToUpload']['type'];
    $filename=$_FILES['fileToUpload']['name'];
    $fileid=GetFileUploadUrl($filename);
    $uploadurl="http://files.otapi.net/upload?fileId=".$fileid;
    echo $uploadurl;
    uploadimage($filetmpname,$filetype,$filename,$uploadurl);
    $finalimageurl=GetFileInfo($fileid);
    echo $finalimageurl;

}

function GetFileUploadUrl($filename)
{
 
    $link = 'http://otapi.net/service/GetFileUploadUrl?instanceKey=' . CFG_SERVICE_INSTANCEKEY
        . '&language=' . CFG_REQUEST_LANGUAGE
        . '&fileName=' . $filename
        . '&fileType=image';
        
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $link);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_HEADER, 0);
 
    $result = curl_exec($curl);
    if ($result === FALSE) {
    echo "cURL Error: " . curl_error($curl); die();
                       }
    $xmlObject = simplexml_load_string($result);

    $json = json_encode($xmlObject);
    $data = json_decode($json,true);
 
    curl_close($curl);
 
    if ((string)$xmlObject->ErrorCode !== 'Ok') 
    {
   echo "Error: " . $xmlObject->ErrorDescription; die();
     }

    $FileId=$data['Result']['Id'];

    return $FileId;

}

function uploadimage($filetmpname,$filetype,$filename,$uploadurl)
  {
    $ch= curl_init();
    //$cfile=new CURLFile($_FILES['fileToUpload']['tmp_name'],$_FILES['fileToUpload']['type'],$_FILES['fileToUpload']['name']);
    $cfile=new CURLFile($filetmpname,$filetype,$filename);
    $data = array("myimage" => $cfile);
    curl_setopt($ch,CURLOPT_URL, $uploadurl);
    curl_setopt($ch,CURLOPT_POST, true);
    curl_setopt($ch,CURLOPT_POSTFIELDS, $data);

    $response = curl_exec($ch);
    if($response == true){
    echo "file posted";
    }
    else
    {
    echo "Error: ". curl_error($ch);
    }
    curl_close($ch);

}

function GetFileInfo($fileid)
{
   //http://otapi.net/OtapiWebService2.asmx/GetFileInfo?language=ru&fileId=e6a05dcd-d345-eb11-80c4-f409ed584015&instanceKey=opendemo
    $link = 'http://otapi.net/OtapiWebService2.asmx/GetFileInfo?instanceKey=' . CFG_SERVICE_INSTANCEKEY
        . '&language=' . CFG_REQUEST_LANGUAGE
        . '&fileId=' . $fileid;
        
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $link);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_HEADER, 0);
 
    $result = curl_exec($curl);
    if ($result === FALSE) {
    echo "cURL Error: " . curl_error($curl); die();
                       }
    $xmlObject = simplexml_load_string($result);

    $json = json_encode($xmlObject);
    $data = json_decode($json,true);
 
    curl_close($curl);
 
    if ((string)$xmlObject->ErrorCode !== 'Ok') 
    {
   echo "Error: " . $xmlObject->ErrorDescription; die();
     }

    $FinalImageUrl=$data['Result']['Url'];

    return $FinalImageUrl;

}

get_footer();

?>
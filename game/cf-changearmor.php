<?php
/*
 * DragonFable Private Server Files
 * Created By MentalBlank (mentalblank@live.com)
 * Layout by: HellFireAE
 *
 * -- IMPORTANT INFORMATIONS --
 * AT THE CURRENT TIME THESE FILES ARE IN DEVELOPMENT
 * AND ARE NOT CURRENTLY COMPLETE OR 100% SECURE
 *
 * THESE FILES WERE CREATED FOR EDUCATIONAL PURPOSES ONLY
 * I DO NOT SUPPORT ANY SERVERS CREATED FROM THESE FILES
*/

include ("../includes/config.php");
header("content-type: text/xml; charset=UTF-8",true);

if (!isset($HTTP_RAW_POST_DATA)) {
    $HTTP_RAW_POST_DATA = file_get_contents("php://input");

    $doc = new DOMDocument();
    $doc->loadXML($HTTP_RAW_POST_DATA);

    $intCharID = $doc->getElementsByTagName('intCharID');
    $char_id = $intCharID->item(0)->nodeValue;

    $strToken = $doc->getElementsByTagName('strToken');
    $UserToken = $strToken->item(0)->nodeValue;

    $intColorBase = $doc->getElementsByTagName('intColorBase');
    $ColorBase = $intColorBase->item(0)->nodeValue;

    $intColorTrim = $doc->getElementsByTagName('intColorTrim');
    $ColorTrim = $intColorTrim->item(0)->nodeValue;

    $char_result = mysql_query("SELECT * FROM df_characters WHERE id = '".$char_id."' LIMIT 1") or $error = 1;
    $char = mysql_fetch_assoc($char_result);
    $user_result = mysql_query("SELECT * FROM df_users WHERE id = '".$char['userid']."' AND pass = '".$UserToken."' LIMIT 1") or $error = 1;
    $user = mysql_num_rows($user_result);

    if($user != 1 || $error == 1){
        $reason = "Error!";
        $message = "There was an issue with your account... Please Login and try again";

        $dom = new DOMDocument("1.0", "UTF-8");
        $XML = $dom->appendChild($dom->createElement('error'));
        $XML->setAttribute("xmlns:sql", "urn:schemas-microsoft-com:xml-sql");
        $info = $XML->appendChild($dom->createElement('info'));
        $info->setAttribute("code", "526.14");
        $info->setAttribute("reason", $reason);
        $info->setAttribute("message", $message);
        $info->setAttribute("action", "None");
    } else {
        $cost = 100;
        $gold_left = $char['gold'] - $cost;

        $ChangeColors = mysql_query("UPDATE df_characters SET colorbase = '".$ColorBase."', colortrim = '".$ColorTrim."' WHERE id = '".$char_id."' LIMIT 1") or $error = 1;
        $ChangeGold = mysql_query("UPDATE df_characters SET gold='" . $gold_left . "' WHERE ID='" . $char_id . "' LIMIT 1") or $error = 1;

        if ($error == 1)
        {
            $reason = "Error!";
            $message = "There was an error saving your new armor colors.";

            $dom = new DOMDocument("1.0", "UTF-8");
            $XML = $dom->appendChild($dom->createElement('error'));
            $XML->setAttribute("xmlns:sql", "urn:schemas-microsoft-com:xml-sql");
            $info = $XML->appendChild($dom->createElement('info'));
            $info->setAttribute("code", "526.14");
            $info->setAttribute("reason", $reason);
            $info->setAttribute("message", $message);
            $info->setAttribute("action", "None");
        }
        if ($error != 1)
        {
            $dom = new DOMDocument("1.0", "UTF-8");
            $XML = $dom->appendChild($dom->createElement('character'));
            $XML->setAttribute("xmlns:sql", "urn:schemas-microsoft-com:xml-sql");
            $character = $XML->appendChild($dom->createElement('character'));
            $character->setAttribute("intColorBase", $char['colorbase']);
            $character->setAttribute("intColorTrim", $char['colortrim']);

            $XML2 = $dom->appendChild($dom->createElement('success'));
            $XML2->setAttribute("xmlns:sql", "urn:schemas-microsoft-com:xml-sql");
            $XML2->setAttribute("status", "SUCCESS");
        }
    }
    $dom->formatOutput = true;
    echo $dom->saveXML();
}
?>
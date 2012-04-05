<!DOCTYPE html> 
<html>
<head>

</head>
<body>
<?php
function convert_steamid_community($steamid)
{
	$id = (substr(str_ireplace("STEAM_0:","",$steamid),2)*2)+substr(str_ireplace("STEAM_0:","",$steamid),0,1)+("76561197960265728");
	return($id);
}
function get_steamid_community($communityid)
{
$id=($communityid-(76561197960265728))/2;
if (substr($id,-2) == ".5") { $steamid = "STEAM_0:1:" . str_replace(".5","",$id);
}else{
$steamid = "STEAM_0:0:" . $id;
}
return($steamid);
} 
if(isset($_REQUEST['steam'])){$steamid=htmlentities($_REQUEST['steam']);}else{$steamid="";}
if($steamid=="")
{}else
{
	if(stristr($steamid,"STEAM_0:")){
		$steam=convert_steamid_community($steamid);
		$xmlf = "http://steamcommunity.com/profiles/$steam?xml=1";
	}elseif($steamid>76561197960265728){
		$xmlf = "http://steamcommunity.com/profiles/$steamid?xml=1";
	}elseif(stristr($steamid,"http://steamcommunity.com/id/")){
		$steam=str_ireplace("http://steamcommunity.com/id/","",$steamid);
		$xmlf = "http://steamcommunity.com/id/$steam?xml=1";
	}elseif(stristr($steamid,"http://steamcommunity.com/profiles/")){
		$steam=str_ireplace("http://steamcommunity.com/profiles/","",$steamid);
		$xmlf = "http://steamcommunity.com/profiles/$steam?xml=1";
	}else
	{
		$steam=$steamid;
		$xmlf = "http://steamcommunity.com/id/$steam?xml=1";
	}
	libxml_use_internal_errors(TRUE);
	$xml = simplexml_load_file($xmlf);
	if(implode("",libxml_get_errors())!=""){
		echo '<h3>An Error has occured. Please try again later.</h3>';
	}else
	{
		if($xml->error=="The specified profile could not be found.")
		{
			echo '<font color="red" style="width:100%">You searched an Invalid Player</font><br>';
		}else
		{	
			echo "You searched: $steamid<br /><br />";
			if($xml->privacyMessage)
			{
				echo 'This user has not yet set up their Steam Community profile.<br />
				<a href=http://steamcommunity.com/profiles/'.$xml->steamID64.' target="_blank">Goto This Players Profile</a>';}else
			{
				echo '<table width="600"><colgroup><col width="200" /><col width="200" /><col width="200"></colgroup>
				<tr>
				<th>Object</th>
				<th>Info</th>
				<th rowspan="12"><img src="'.$xml->avatarFull.'" title="'.htmlentities($xml->headline).'" /></th>
				</tr>';
				
				$status['offline']="Offline";
				$status['online']="Online";
				$status['in-game']=$xml->stateMessage;
				
				$privacy['public']="Public";
				$privacy['usersonly']="Steam Users Only";
				$privacy['friendsfriendsonly']="Friends Of Friends";
				$privacy['friendsonly']="Friends Only";
				$privacy['private']="Private";	
				
				$vac['0']="No";
				$vac['1']="Yes";
				
				echo '<tr>';
				echo '<td>Steam Name</td>';
				echo '<td>'.htmlentities($xml->steamID).'</td>';
				echo '</tr>';
				echo '<tr>';
				echo '<td>Profile</td>';
				echo '<td><a href=http://steamcommunity.com/profiles/'.$xml->steamID64.' title="Click here to go to '.$xml->steamID.'s Steam Page" target="_blank">Click Here</a></td>';
				echo '</tr>';
				echo '<tr>';
				echo '<td>Status</td>';
				echo '<td>'.$status["$xml->onlineState"].'</td>';
				echo '</tr>';
				if($xml->onlineState == "offline" & $xml->privacyState=="public")
				{
					echo '<tr>';
					echo '<td>Last Online</td>';
					echo '<td>'.str_ireplace("Last Online:","",$xml->stateMessage).'</td>';
					echo '</tr>';
				}else{}
				echo '<tr>';
				echo '<td>Vac Banned?</td>';
				echo '<td>'.$vac["$xml->vacBanned"].'</td>';
				echo '</tr>';
				if($xml->privacyState!="public"){}else
				{
					echo '<tr>';
					echo '<td>Steam Rating</td>';
					echo '<td>'.$xml->steamRating.'</td>';
					echo '</tr>';
					echo '<tr>';
				}
				echo '<td>Steam ID</td>';
				echo '<td>'.get_steamid_community($xml->steamID64).'</td>';
				echo '</tr>';
				echo '<tr>';
				echo '<td>Community ID</td>';
				echo '<td>'.$xml->steamID64.'</td>';
				echo '</tr>';
				echo '<tr>';
				echo '<td>Privacy Level</td>';
				echo '<td>'.$privacy["$xml->privacyState"].'</td>';
				echo '</tr>';
				echo '<tr>';
				echo '<td>Add To Friends</td>';
				echo '<td><a href="steam://friends/add/'.$xml->steamID64.'" title="Click to add '.$xml->steamID.' to your friends list">Click Here</a></td>';
				echo '</tr>';
				echo '<tr>';
				echo '<td>Info Source</td>';
				echo '<td><a href="'.$xmlf.'" title="XML File" target="_blank">Click Here</a></td>';
				echo '</tr>';
				echo '</table><br />';			  
				if($xml->privacyState!="public"){echo "This Player's Profile is not Public, Therefor Groups and Games cannot be retrieved<br /><br />"; }else
				{
					if(!isset($xml->mostPlayedGames->mostPlayedGame[0]->gameName)){echo $xml->steamID.' has not played any games recently!<br /><br />';}else
					{
						echo '<p class="toggleContent"><button type="button">Recently Played Games</button></p>
						<div class="tcontent">
						<table border="1">
						<tr>
						<th>Game</th>
						<th>Last 2 weeks</th>
						<th>Total</th>
						</tr>';
						foreach($xml->mostPlayedGames->mostPlayedGame as $games){
							echo '<tr>
							<td><a href='.$games->gameLink.' title="Click to go to the '.htmlentities($games->gameName).' Store Page" target="_blank">'.htmlentities($games->gameName).'</a></td>
							<td>'.$games->hoursPlayed.' hrs</td>
							<td>'.$games->hoursOnRecord.' hrs</td>
							</tr>';
						}
						echo '</table></div>';
					}
					if(!isset($xml->groups->group[0]->groupID64)){echo $xml->steamID.' dose not belong to any groups!<br /><br />';}else
					{
						echo '<p class="toggleContent"><button type="button">Groups ('.count($xml->groups->group).')</button></p>
						<div class="tcontent">
						<table border="1">
						<tr>
						<th>Name</th>
						<th>Headline</th>
						<th>Total Members</th>
						</tr>';
						foreach($xml->groups->group as $groups){
							echo '<tr>
							<td><a href="http://steamcommunity.com/gid/'.$groups->groupID64.'" title="Click to go to the '.htmlentities($groups->groupName).' Group Page" target="_blank">'.htmlentities($groups->groupName).'</a></td>
							<td>'.$groups->headline.'</td>
							<td>'.$groups->memberCount.'</td>
							</tr>';
						}
						echo '</table></div>';
					}
				}
			}		
		}
	}
}
?>
</body>
</html>
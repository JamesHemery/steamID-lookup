<?php
//error_reporting(0);
require("functions.php");
$startTime = startTimer();
bcscale(0);
if(isset($_GET['s'])){$steamid=htmlentities($_GET['s']);}else{$steamid="";} ?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>SteamID Search</title>
		<meta name="keywords" content="Steam, Tool, Search, DJ Wolf" />
		<meta name="description" content="Searches the Steam database for a player then returns the player information. (If Any)" />
		<link href="style.css" rel="stylesheet" type="text/css" />
		<link rel="icon" type="image/png" href="images/favicon.png" />		
	</head>
	<body>
		<div id="container">
			<div id="content">
				<div id="searchBox" class="steamBox">
					<form id="searchForm">
						<input type="text" id="searchBar" name="s" size="52" placeholder="Search by Steamid, Community ID, or Custom URL" />
						<input type="submit" id="searchButton" class="button1" value=""/>
					</form>
					<?php if($steamid!="") echo '<div class="center" style="margin-top:5px;">Search: '.htmlentities($steamid).'</div>'; ?>
				</div>
				<div id="infoBox" class="steamBox">
				<?php if($steamid==""){
					echo '<div style="margin: 5px 0;">Please search for a user.</div>';
				}else{
					$xmlf = buildSteamURL($steamid);
					libxml_use_internal_errors(TRUE);
					$xml = simplexml_load_file($xmlf);
					if(!isset($steam64)){$steam64 = $xml->steamID64;}
					if(!isset($steam32)){$steam32 = get_steamid_community($steam64);}
					
					$status['offline']="Offline";
					$status['online']="Online";
					$status['in-game']=str_ireplace('<span class="friend_join_game_dash">-</span> <a class="friend_join_game_link"',"- <a",str_ireplace("<br />",": ",$xml->stateMessage));
					
					$privacy['public']="Public";
					$privacy['usersonly']="Steam Users Only";
					$privacy['friendsfriendsonly']="Friends Of Friends";
					$privacy['friendsonly']="Friends Only";
					$privacy['private']="Private";	
					
					$vac['0']="No";
					$vac['1']="Has Bans";
					if(libxml_get_errors()!=NULL){
						echo '<div class="error">Oops. Steam is overloaded. Try again Later, or <a href="?'.htmlentities($_SERVER['QUERY_STRING']).'">Re-Search</a> now.</div>';
					}elseif(error_get_last()!=NULL){
						echo '<div class="error">An Error has occured. Try again later, or <a href="?'.htmlentities($_SERVER['QUERY_STRING']).'">Re-Search</a> now.</div>';
					}elseif($xml->error=="The specified profile could not be found." || $xml->error=="115"){
						echo '<div class="warning">You searched for a Player that does not exist. (Or do they :O)</div>';
					}elseif($xml->privacyMessage){ ?>
						<div class="warning">This user is being Anti-Social and didnt set up their Page. But Heres some Info Anyway.</div>
						<div id="steamData">
							<div>SteamID32: <?php echo htmlentities($steam32); ?></div>
							<div>SteamID64: <?php echo htmlentities($steam64); ?></div>
							<div>Vac Banned?: <?php echo htmlentities($vac["$xml->vacBanned"]); ?></div>
							<div>Trade Bans?: <?php echo htmlentities($xml->tradeBanState); ?></div>
							<div>Profile: <a href="http://steamcommunity.com/profiles/<?php echo htmlentities($xml->steamID64); ?>" title="Click here to go to <?php echo htmlentities($xml->steamID); ?>'s Steam Page" target="_blank">Click Here</a></div>
							<div>Add to Friends: <a href="steam://friends/add/<?php echo htmlentities($xml->steamID64); ?>" title="Click to add <?php echo htmlentities($xml->steamID); ?> to your friends list">Click Here</a></div>
							<div>Info Source: <a href="<?php echo htmlentities($xmlf); ?>" title="XML File" target="_blank">Click Here</a></div>
						</div>
						<div style="display: block; clear: both;"></div>
					<?php }else{
						$username = $xml->steamID;
						if($xml->privacyState!="public"){$steamRating= "Profile Private";}else{$steamRating = $xml->steamRating;}
						if($xml->privacyState!="public"||!isset($xml->hoursPlayed2Wk)){$playTime="Unavailable";}else{$playTime = $xml->hoursPlayed2Wk;} ?>
						<div id="steamData">
							<div>Username: <?php echo htmlentities($username); ?></div>
							<div>Status: <?php echo $status["$xml->onlineState"]; ?></div>
							<?php if($xml->onlineState == "offline" & $xml->privacyState=="public"){ echo '<div>'.htmlentities($xml->stateMessage).'</div>'; } ?>
							<div>Profile State: <?php echo htmlentities($privacy["$xml->privacyState"]); ?></div>
							<div>Steam Rating: <?php echo htmlentities($steamRating); ?></div>
							<div>Playtime (Hrs, 2 Weeks): <?php echo htmlentities($playTime); ?></div>
							<div>SteamID32: <?php echo htmlentities($steam32); ?></div>
							<div>SteamID64: <?php echo htmlentities($steam64); ?></div>
							<div>Vac Banned?: <?php echo htmlentities($vac["$xml->vacBanned"]); ?></div>
							<div>Trade Bans?: <?php echo htmlentities($xml->tradeBanState); ?></div>
							<div>Profile: <a href="http://steamcommunity.com/profiles/<?php echo htmlentities($xml->steamID64); ?>" title="Click here to go to <?php echo htmlentities($xml->steamID); ?>'s Steam Page" target="_blank">Click Here</a></div>
							<div>Add to Friends: <a href="steam://friends/add/<?php echo htmlentities($xml->steamID64); ?>" title="Click to add <?php echo htmlentities($xml->steamID); ?> to your friends list">Click Here</a></div>
							<div>Info Source: <a href="<?php echo htmlentities($xmlf); ?>" title="XML File" target="_blank">Click Here</a></div>
						</div>
						<div id="steamPicture">
							<img src="<?php echo htmlentities($xml->avatarFull); ?>" alt="<?php echo htmlentities($xml->headline); ?>"/>
						</div>
						<div style="display: block; clear: both;"></div>
					<?php }
					} ?>
				</div>
			</div>
			<div id="footer">
				<div>Steam Search V2.9 by amreuland: <a href="http://steamcommunity.com/profiles/76561198027173954" target="_blank">Steam(DJ Wolf)</a>|<a href="https://github.com/amreuland/" target="_blank" title="amreuland">GitHub</a> - <a href="https://github.com/amreuland/steamID-lookup">GitHub/SteamID Lookup</a></div>
				<div>All trademarks are the property of their respective owners. <a href="http://steampowered.com" target="_blank">Powered by Steam</a></div>
				<div>Page compiled in <?php echo stopAndCountTimer($startTime, 4); ?> Seconds.</div>
			</div>
		</div>
	</body>
</html>
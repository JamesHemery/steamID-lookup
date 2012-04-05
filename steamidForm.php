<script src="steamid.js" type="text/javascript"></script>
<script type="text/javascript" src="http://code.jquery.com/jquery-latest.js"></script>
<form onsubmit="MakeSteamidRequest(this.steam.value);" action="javascript:void(0);">
<input type="search" style="width:50%" id="steam" name="steam" size="52" placeholder="Type a Steam ID or a Community ID" />
<input type="submit" value="Search" />
</form><br>
<div id="ResponseDiv" class="ResponseDiv" height="1100px">
</div>
<p>Version 1.9 - <a href="https://github.com/DJWolf/steamID-lookup" target="_blank">Public Release on GitHub</a>
<br>Steam Player/ID Lookup. By <a href="http://steamcommunity.com/profiles/76561198027173954" title="DJ Wolf">DJ Wolf</a> - 
<!--DO NOT REMOVE THE LINE UNDER THIS!!! - STEAM REQUIRES IT IF YOU ARE GOING TO USE THE STEAM API OR COMMUNITY DATA-->
<a href="http://steampowered.com" target="_blank">Powered By Steam</a></p> 
</CENTER>
<?php if(isset($_GET['steam'])){echo '<script type="text/javascript">MakeSteamidRequest("'.$_GET['steam'].'");</script>';}?>


Solomd PHP PVP api integration
==============================

This is a simple php api integration script to help you get stared.
<strong>it does not include rate limits</strong>

should run ok on most php 5.2+


<code>
$api = new pvp_api('API-KEY');
$me = $api->summoner_by_name('na','akuseru');
var_dump($me);
</code>

test script just does a quick dump. if you have any questions just open a ticket


Authors
================
[Adam Smith (akuseru)](http://github.com/akuseru
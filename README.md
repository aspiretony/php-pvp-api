Solomd PHP PVP api integration
==============================

This is a simple php api integration script to help you get stared.<Br>
<strong>It does not include a rate limit manager. If you use this on a live site with public traffic you're not going to have a good time!</strong>

should run ok on most php 5.2+


```php
$api = new pvp_api('API-KEY');
$me = $api->summoner_by_name('na','akuseru');
var_dump($me);
```

test script just does a quick dump. of all available endpoints. if you have questions open a ticket!


Authors
================
[Adam Smith (akuseru)](http://github.com/akuseru)

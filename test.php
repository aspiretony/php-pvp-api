<?php
/**
 * Created by Adam Smith (akuseru) <adam@solomid.net>
 * The MIT License (MIT)
 *
 * Copyright (c) 2013 Solomid Network
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */


include 'pvp_api.php';
if(!isset($argv[1]))
    die('must set arg to api key');

$api = new pvp_api($argv[1]);
$args = array('{region}'=>'na',
               '{summonerId}'=> 20802335,
                '{name}'=> 'box box',
                '{summonerIds}' => '20802335,17772');
$_urls = array(
    'champion'      => array('url'          => '/api/lol/{region}/v1.1/champion',
        'params'        => array(
            0 => '{region}'
        )),
    'match_history' => array('url'          => '/api/lol/{region}/v1.1/game/by-summoner/{summonerId}/recent',
        'params'       => array(
            0 => '{region}',
            1 => '{summonerId}',
        )),
    'league'        => array('url'          => '/api/{region}/v2.1/league/by-summoner/{summonerId}',
        'params'       => array(
            0 => '{region}',
            1 => '{summonerId}',
        )),
    'stats_summary' => array('url'          => '/api/lol/{region}/v1.1/stats/by-summoner/{summonerId}/summary',
        'params'       => array(
            0 => '{region}',
            1 => '{summonerId}',
        )),
    'stats_ranked'  => array('url'          => '/api/lol/{region}/v1.1/stats/by-summoner/{summonerId}/ranked',
        'params'       => array(
            0 => '{region}',
            1 => '{summonerId}',
        )),
    'summoner_masteries' => array('url'     => '/api/lol/{region}/v1.1/summoner/{summonerId}/masteries',
        'params'       => array(
            0 => '{region}',
            1 => '{summonerId}',
        )),
    'summoner_runes'     => array('url'     => '/api/lol/{region}/v1.1/summoner/{summonerId}/runes',
        'params'       => array(
            0 => '{region}',
            1 => '{summonerId}',
        )),
    'summoner_by_name'   => array('url'     => '/api/lol/{region}/v1.1/summoner/by-name/{name}',
        'params'       => array(
            0 => '{region}',
            1 => '{name}',
        )),
    'summoner_by_id'   => array('url'       => '/api/lol/{region}/v1.1/summoner/{summonerId}',
        'params'       => array(
            0 => '{region}',
            1 => '{summonerId}',
        )),
    'summoner_ids'   => array('url'         => '/api/lol/{region}/v1.1/summoner/{summonerIds}/name',
        'params'       => array(
            0 => '{region}',
            1 => '{summonerIds}',
        )),
    'teams'         => array('url'          => '/api/{region}/v2.1/team/by-summoner/{summonerId}',
        'params'       => array(
            0 => '{region}',
            1 => '{summonerId}',
        )),

);

foreach($_urls as $f => $x) {
    $params = array();
    foreach($x['params'] as $p) {
        $params[] = $args[$p];
    }

    $data = call_user_func_array(array($api, $f), $params);
echo "\t\t{$f}\n=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-\n";
    var_dump($data);
echo "=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-\n";
}
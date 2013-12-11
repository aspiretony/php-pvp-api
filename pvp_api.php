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

require_once 'vendor/autoload.php';

/**
 * a generic pvp_api wrapper.
 * <strong>WARNING</strong> This wrapper does not monitor your rate limit.
 * If you let users call this endpoint directly you are going to have a bad time!
 *
 * simple usage:
 * <code>
 * $api = new pvp_api('API-KEY');
 * $me = $api->summoner_by_name('na','akuseru');
 * var_dump($me);
 * </code>
 *
 * Class pvp_api
 */
class pvp_api {
    /**
     * this is static so we don't show it during a var_dump / print_r on our pvp_api object.
     * @var string holds your api key
     */
    private static $_key;

    /**
     * this is our default url for making requests (in case it changes)
     * @var string
     */
    private $_endpoint = "http://prod.api.pvp.net";
    /**
     * These are our default curl options
     * CURL is used by default with file_get_contents as a fallback.
     * @var array
     */
    private $_options = array (
        'headers' => array(
        ),
        'options' => array(),
        'as_array' => false,
    );

    /**
     * These are all available urls and how to handle them
     * this uses php magic __call, params are expected to be in their numbered order.
     * any optional parameters should be passed as an array(key=>value)
     * no checking is done on params.
     * @var array
     */
    //for this section it would probably be a good idea to
    private $_urls = array(
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
    /**
     * @param string $api_key
     * @param array $options
     */
    public function  __construct ($api_key, $options=array()) {
        static::$_key = $api_key;
        $this->_options = array_merge($this->_options, $options);
    }

    /**
     * This is where we handle fetching and decoding our url.
     * @param string $url
     * @throws Exception
     * @return Object|null
     */
    private function _get ($url) {
        if(strstr($url, '?'))
        {
            $url .= '&api_key='.static::$_key;
        }
        else
        {
            $url .= '?api_key='.static::$_key;
        }
        $data = Requests::get($url, $this->_options['headers'], $this->_options['options']);
        if($data->status_code !== 200){
            throw new Exception($data->body, $data->status_code, new Exception('Riot returned non 200 status code'));
        }
        $data = $data->body;
        if ($data === null) {
            return null;
        } else {
            $json = json_decode($data, $this->_options['as_array']);

            //check to see if we had a json error
            if (json_last_error() != JSON_ERROR_NONE) {
                if (!function_exists('json_last_error_msg')) {
                    switch (json_last_error()) {
                        default:
                            $error = 'Unknown JSON error code: '.json_last_error();
                            break;
                        case JSON_ERROR_DEPTH:
                            $error = 'Maximum stack depth exceeded';
                            break;
                        case JSON_ERROR_STATE_MISMATCH:
                            $error = 'Underflow or the modes mismatch';
                            break;
                        case JSON_ERROR_CTRL_CHAR:
                            $error = 'Unexpected control character found';
                            break;
                        case JSON_ERROR_SYNTAX:
                            $error = 'Syntax error, malformed JSON';
                            break;
                        case JSON_ERROR_UTF8:
                            $error = 'Malformed UTF-8 characters, possibly incorrectly encoded';
                            break;
                    }
                } else {
                    $error = json_last_error_msg();
                }
                    throw new Exception($error);
            }

            return $json;
        }
    }

    public function __call ($name, $arguments) {
        if (!array_key_exists($name, $this->_urls)) {
            throw new Exception('No call matching '.$name);
        }
        $url = $this->_endpoint.$this->_urls[$name]['url'];
        foreach($arguments as $id => $x)
        {
            if (isset($this->_urls[$name]['params'][$id])) {
                if ($this->_urls[$name]['params'][$id] === '{name}') {
                    $x = $this->_parse_name($x);
                }
                $url = str_replace($this->_urls[$name]['params'][$id], $x,$url);
            } else if (is_array($x)) {
                $params = $x;
            } else {
                throw new Exception('I don\'t know how to handle paramater number '.$x);
            }
        }

        if(isset($params))
            $url = $url.'?'.http_build_query($params);
        return $this->_get($url);
    }

    /**
     * returns a cleaned name for {name} params
     * @param $name
     * @return string
     */
    protected function _parse_name ($name) {
        return strtolower(str_replace(' ', '',$name));
    }
}
<?php

/**
 * @return the value at $index in $array or $default if $index is not set.
 */
function idx(array $array, $key, $default = null) {
  return array_key_exists($key, $array) ? $array[$key] : $default;
}

function he($str) {
  return htmlentities($str, ENT_QUOTES, "UTF-8");
}

function fbGetUser($id = 'me') {
	return $facebook->api("/$id");
}

function fbGetUserLikes($id = 'me') {
	return idx($facebook->api("/$id/likes"), 'data', array());
}

function fbGetUserPhoto($id = 'me') {
	return "https://graph.facebook.com/" . he($id) . "/picture?type=square";
}

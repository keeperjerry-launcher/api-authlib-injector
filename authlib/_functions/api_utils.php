<?php
	function getRandomMD5()
	{
		return md5(openssl_random_pseudo_bytes(20));
	}

	function checkPass($getPass, $getHashPass)
	{
		return strlen($getHashPass) == 32 && ctype_xdigit($getHashPass)
		? $getHashPass == md5(md5($getPass))
		: password_verify($getPass, $getHashPass);
    }

	function shortUuid($uuid)
	{
		return str_replace("-", "", $uuid);
    }
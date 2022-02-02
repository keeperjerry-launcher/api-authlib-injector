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

	/* 
    =================================================
        Функция генерации uuid minecraft offline   
    =================================================
    */

    function generation_uuid($nickname)
    {
        // Здесь происходит какая-то хуйня
        $val = md5($nickname, true);
        $byte = array_values(unpack('C16', $val));
    
        $tLo = ($byte[0] << 24) | ($byte[1] << 16) | ($byte[2] << 8) | $byte[3];
        $tMi = ($byte[4] << 8) | $byte[5];
        $tHi = ($byte[6] << 8) | $byte[7];
        $csLo = $byte[9];
        $csHi = $byte[8] & 0x3f | (1 << 7);
    
        if (pack('L', 0x6162797A) == pack('N', 0x6162797A)) {
            $tLo = (($tLo & 0x000000ff) << 24) | (($tLo & 0x0000ff00) << 8) | (($tLo & 0x00ff0000) >> 8) | (($tLo & 0xff000000) >> 24);
            $tMi = (($tMi & 0x00ff) << 8) | (($tMi & 0xff00) >> 8);
            $tHi = (($tHi & 0x00ff) << 8) | (($tHi & 0xff00) >> 8);
        }
    
        $tHi &= 0x0fff;
        $tHi |= (3 << 12);
    
        $uuid = sprintf(
            '%08x-%04x-%04x-%02x%02x-%02x%02x%02x%02x%02x%02x',
            $tLo, $tMi, $tHi, $csHi, $csLo,
            $byte[10], $byte[11], $byte[12], $byte[13], $byte[14], $byte[15]
        );
        return $uuid;
    }

    function uuidConvert($string)
    {
        $string = generation_uuid("OfflinePlayer:".$string);
        return $string;
    }

	function uuidConvertShort($string)
    {
        $string = generation_uuid("OfflinePlayer:".$string);
        return str_replace("-", "", $string);
    }

	function shortUuid($uuid)
	{
		return str_replace("-", "", $uuid);
    }
<?php

require_once('common.php');
if (empty($myUser)) exit();

require_once('phpqrcode.php');

$methode = array_keys($_REQUEST)[0];
switch($methode) {
    case 'qr': # qrcode.php?qr&label=A&user=B&key=C
        $variableList = array('label', 'user', 'key', 'issuer', 'algorithm', 'digits', 'period');
        Functions::chargeVarRequest($variableList);
        foreach ($variableList as $variable) ${$variable} = urlencode(${$variable});

        if (empty($key)) {
            $key = "**********";
        }
        $qrCode = "otpauth://totp/{$label}%3A{$user}?secret={$key}"; // %3A==urlencode(':')
        foreach (array('issuer', 'algorithm', 'digits', 'period') as $champ)
            if (!empty(${$champ}))
                $qrCode.="&{$champ}={${$champ}}";
        break;
    case 'txt': # qrcode.php?txt&TEXTE
        $qrCode = substr($_SERVER['QUERY_STRING'], 1+strlen($methode));
        break;
    default:
        $qrCode = '';
}

Functions::chargeVarRequest('_qrSize', '_qrMargin');
if (empty($_qrSize))   $_qrSize   = 3;
if (empty($_qrMargin)) $_qrMargin = 4;

QRcode::png($qrCode, false, 'QR_LEVEL_H', $_qrSize, $_qrMargin);

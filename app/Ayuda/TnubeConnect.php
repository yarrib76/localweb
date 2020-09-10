<?php
/**
 * Created by PhpStorm.
 * User: viamore
 * Date: 09/08/2020
 * Time: 04:14 PM
 */

namespace Donatella\Ayuda;


class TnubeConnect
{
    public function getConnectionTN($store_id)
    {
        //$store_id = 0;
        /*Verifica con que tienda tiene que sincronizar:
        Demo Nacha = 972788
        Samira SRL = 938857
        Donatella = 963000
        Viamore = 1043936
        */
        $connetion=[];
        if ($store_id == '972788'){
            $access_token = 'efa99efebc3e97935b84675e875a0f4d3566524c';
            //   $store_id = '972788';
            $appsName = 'SincroDemo (yarrib76@gmail.com)';
        }
        if ($store_id == '938857'){
            $access_token = '101d4ea2e9fe7648ad05112274a5922acf115d37';
            //    $store_id = '938857';
            $appsName = 'SincroApps (yarrib76@gmail.com)';
        }
        if ($store_id == '963000'){
            $access_token = '00b27bb0c34a6cab2c1d4edc0792051b50b91f9e';
            //    $store_id = '963000';
            $appsName = 'SincoAppsDonatella (yarrib76@gmail.com)';
        }
        if ($store_id == '1043936'){
            $access_token = '483b0e8c4eb5d65211002a5d1770281b7ea5e437';
            //    $store_id = '1043936';
            $appsName = 'SincoAppsViamore (yarrib76@gmail.com)';
        }
        $connetion[0]= ['access_token' => $access_token,'appsName' => $appsName];
        return $connetion;
    }
}
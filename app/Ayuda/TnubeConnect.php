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
        //Para instalar la aplicaci�n en mi tienda, ingresar a la parte administrador de la tienda,
        // 1. Abrir una nueva pestania y poner le url "https://www.tiendanube.com/apps/(app_id)/authorize",
        //2. Reemplazar (app_id) por el id de la aplicacion que se quiere instalar.
        //3. Luego tomar el Code y pegarlo en el codigo de abajo:
        //4. En la creaci�n del objeto ingresar el id de la aplicaci�n y el Clien Secret (esta en https://partners.tiendanube.com/apps/?ref=menu)


        /*
        $code = 'c20dd9b6d9a87d1ec7dca0e5e3278625e4abfd9b';
        // En Auth(Cliente_id,Client Secret)
        $auth = new Auth(1358, 'WcuW5hyGiiPPqpnC5OEVOmg0r7oDjUcvlIXLEphoAanRFVd5');
        $store_info = $auth->request_access_token($code);
        dd($store_info);

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
            $tienda = 'Nacha';
        }
        if ($store_id == '938857'){
            $access_token = '9d4e7d6c96a5256904d289d6425b969c043bd1cf';
            //    $store_id = '938857';
            $appsName = 'SincroApps (yarrib76@gmail.com)';
            $tienda = 'Samira';
        }
        if ($store_id == '963000'){
            $access_token = '81cdc25af292e5cd931891e2b3ef6683b895ec71';
            //    $store_id = '963000';
            $appsName = 'SincoAppsDonatella (yarrib76@gmail.com)';
            $tienda = 'Donatella';
        }
        if ($store_id == '1043936'){
            $access_token = '986093261acb4e02ad78a42b891f9ca52592a8cf';
            //    $store_id = '1043936';
            $appsName = 'SincoAppsViamore (yarrib76@gmail.com)';
            $tienda = 'Viamore';
        }

        if ($store_id == '1379491'){
            $access_token = 'addbd218f3cd7b03cd46b8cb318b472ddbd34c84';
            //    $store_id = '1043936';
            $appsName = 'SincroDemo (yarrib76@gmail.com)';
            $tienda = 'LabLocales';
        }

        if ($store_id == '4999055'){
            $access_token = '9965c891d2b6d8481ad01fd5f6203aa7aece9b6d';
            //    $store_id = '1043936';
            $appsName = 'SincroDemo (yarrib76@gmail.com)';
            $tienda = 'MegaNay';
        }

        $connetion[0]= ['access_token' => $access_token,'appsName' => $appsName,'tienda' => $tienda];
        return $connetion;
    }
}
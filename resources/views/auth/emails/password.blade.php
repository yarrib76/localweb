<!--Verifico en que local estoy para enviar el Codido correcto de Tienda Nube-->
@if (substr(Request::url('http://donatella.dyndns.org'),0,27) == 'http://donatella.dyndns.org')
    Ingresar al Link para cambiar la contraseña: {{ url('http://donatella.dyndns.org:8081/password/reset/'.$token) }}
@elseif (substr(Request::url('http://samirasrl.dyndns.org'),0,27) == 'http://samirasrl.dyndns.org')
    Ingresar al Link para cambiar la contraseña: {{ url('http://samirasrl.dyndns.org:8081/password/reset/'.$token) }}
@elseif (substr(Request::url('http://viamore.dyndns.org'),0,25) == 'http://viamore.dyndns.org')
    Ingresar al Link para cambiar la contraseña: {{ url('http://viamore.dyndns.org:8081/password/reset/'.$token) }}
@elseif (substr(Request::url('http://dona.com'),0,15) == 'http://dona.com')
    Ingresar al Link para cambiar la contraseña: {{ url('http://dona.com:8081/password/reset/'.$token) }}
@elseif (substr(Request::url('http://donalab.dyndns.org'),0,25) == 'http://donalab.dyndns.org')
    Ingresar al Link para cambiar la contraseña: {{ url('http://donalab.dyndns.org:8083/password/reset/'.$token) }}
@endif

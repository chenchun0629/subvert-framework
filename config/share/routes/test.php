<?php

return [

    ['api' => 'test.network.ping.pong', 'version' => '*', 'action' => 'Bll.Test.Network.Ping.pong', 'status' => 'enable'],
    ['api' => 'test.network.ping.sql', 'action' => 'Bll.Test.Network.Ping.sql'],
    ['api' => 'test.network.ping.redis', 'action' => 'Bll.Test.Network.Ping.redis'],
    ['api' => 'test.network.ping.entity', 'action' => 'Bll.Test.Network.Ping.entity', 'entity' => 'Com.Test.Network.Ping.Entity'],
    ['api' => 'test.network.ping.sess', 'action' => 'Bll.Test.Network.Ping.sess', 'entity' => 'Com.Test.Network.Ping.Sess'],

];

<?php

return [
    'skynetauth' => [
        'name'          => '天网平台',                   // 中文菜单名称
        'icon'          => 'fas fa-shield-alt',         // FontAwesome图标类
        'route_segment' => 'api-admin',                 // URL路径片段
        'route'         => 'seatcore::api-admin.list'   // 路由别名（Laravel路由命名规范）
    ]
];

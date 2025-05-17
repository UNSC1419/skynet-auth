![SeAT](http://i.imgur.com/aPPOxSK.png)

[![Latest Stable Version](https://poser.pugx.org/unsc1419/skynet-auth/v/stable)](https://packagist.org/packages/unsc1419/skynet-auth)
[![Total Downloads](https://poser.pugx.org/unsc1419/skynet-auth/downloads)](https://packagist.org/packages/unsc1419/skynet-auth)
[![Latest Unstable Version](https://poser.pugx.org/unsc1419/skynet-auth/v/unstable)](https://packagist.org/packages/unsc1419/skynet-auth)
[![License](https://poser.pugx.org/unsc1419/skynet-auth/license)](https://packagist.org/packages/unsc1419/skynet-auth)

# skynet-auth
用于链接Skynetauth和SeAT的插件

# 容器SeAT安装方法
* 修改`/opt/seat-docker/.env`文件并保存
  ```php
  # SeAT Plugins
  # This is a list of the all of the third party plugins that you
  # would like to install as part of SeAT. Package names should be
  # comma seperated if multiple packages should be installed.

  SEAT_PLUGINS=UNSC1419/skynet-auth
  ....
  ....
  # ---------------------------
  # 添加SkynetAuth配置段
  # ---------------------------
  SKYNETAUTH_SSOBACK_URL=https://auth.domain.tld/auth/seat/callback # auth登录回调url
  ```


* Docker（SeAT 5.x - 使用反向代理）的情况下执行 `docker compose -f docker-compose.yml -f docker-compose.mariadb.yml -f docker-compose.proxy.yml up -d `
* Docker（SeAT 5.x - 使用 Traefik）的情况下执行 `docker compose -f docker-compose.yml -f docker-compose.mariadb.yml -f docker-compose.traefik.yml up -d `

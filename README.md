Yii 2 Advanced Project Template
===============================
Advanced Project Template for demo, include frontend and backend(with RBAC authorization),
just practice dont use in production.

[![Latest Stable Version](https://poser.pugx.org/rockielin/yii2-app-advanced-template/v/stable.png)](https://packagist.org/packages/rockielin/yii2-app-advanced-template)
[![Total Downloads](https://poser.pugx.org/rockielin/yii2-app-advanced-template/downloads.png)](https://packagist.org/packages/rockielin/yii2-app-advanced-template)

安裝
------------
若尚未安裝composer, 先執行composer安裝
~~~
curl -sS https://getcomposer.org/installer | php
~~~

透過composer直接建立新專案:
~~~
php composer.phar create-project --prefer-dist --stability=dev rockielin/yii2-app-advanced-template project-name
~~~

或

clone此專案
~~~
php composer.phar install --no-interaction
composer run-script post-install-cmd 
~~~

更新
~~~
php composer.phar update --no-interaction
~~~

拷貝設定檔
設定domain, db相關資訊 (因部署環境會變動的設定)
~~~
cp common/congif/main.example.php common/congif/main.php
~~~

本機開發啟動(port自訂)
------------
後台 http://localhost:8080  測試帳密(example@example.com / 123456)
~~~
php yii-admin serve --port=8080
~~~

前台 http://localhost:8081 測試帳密(example@example.com / 123456)
~~~
php yii-front serve --port=8081
~~~

API http://localhost:8082
~~~
php yii-api serve --port=8082
~~~

common(圖片共用程式) http://localhost:8083
~~~
php yii-common serve --port=8083
~~~


本機開發工具
------------
Debug Tool (不同站台debug路徑不同)
~~~
http://localhost:808x/debug
~~~

產生器
~~~
http://localhost:808x/gii
~~~

清cache(範例用FileCache, 不同站台cache路徑不同)
~~~
http://localhost:808x/job/clearcache
~~~


Docker執行:
-------------
部署環境若使用Docker, 須先設定nginx/conf.d

進入 ./docker後執行
~~~
docker-compose up -d
~~~

Docker其他命令:
-------------
docker setting
~~~
docker-compose.yml
~~~

building
~~~
docker-compose build
~~~

start continer
~~~
docker-compose up -d
~~~

stop
~~~
docker-compose down -v
~~~

show log
~~~
docker-compose logs -f
~~~

access continer
~~~
docker-compose exec php bash
~~~


Docker中Supervisor命令:
-------------
啟動
~~~
supervisord -c /etc/supervisor/supervisord.conf
~~~

查看supervisor是否啟動
~~~
ps aux | grep supervisor
~~~

關閉supervisor主進程
~~~
supervisorctl shutdown
pkill -f supervisord  # kill it
~~~

查看supervisor執行的子進程
~~~
supervisorctl status
~~~


其他:
-------------
後台Theme有些混亂 待處理
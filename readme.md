# tracking

- v1.0 林益远 2020 03 19 创建

## 1 前言
### 1.1 项目说明
该项目使用laravel5.6 + Vue2.0 进行开发，构建一个后台应用

### 1.2 注意事项
主要的功能有以下:

- 用户管理
- 权限管理
- 角色管理
- 操作日志
- .....


## 2 如何部署
### 2.1 开发说明
- 开发框架：`Laravel 5.6` 
- PHP版本：`PHP 7.3` `NPM 3.5.2`
- 扩展：`Redis`

### 2.2 安装
#### 2.2.1 基本要求
- 服务器要求：
	- PHP >= 7.1.3
	  NPM >= 3.5.0
	- OpenSSL PHP扩展
	- PDO PHP扩展，注意需要php_mysql
	- Mbstring PHP扩展
	- Tokenizer PHP扩展
	- XML PHP扩展
	- Swoole PHP扩展
	- Redis PHP扩展



#### 2.2.2 安装步骤
以下为本项目svn仓库地址

		https://115.159.35.222/svn/tracking
	

**第1步：从svn上拉取代码**


**第2步：确保计算机安装了composer包管理器，若未安装composer，请在电脑安装composer，并将composer镜像切换至阿里云镜像。 在项目目录下执行**
	
	composer install

	如果出现 `The requested PHP extension ext-curl ^7.3 has the wrong version (7.2.14) installed` 请运行

	composer install --ignore-platform-reqs
	


**第3步：配置文件**

1、在项目中找到`.env.example`文件，该文件作为项目的全局配置文件，在部署时需要复制成`.env`，执行以下命令

	cp -f .env.example ./.env
2、根据.env文件说明修改各配置项，然后运行命令生成KEY：

	  php artisan key:generate

3、配置stroage bootstrap 可写

	 chmod -R 777 stroage bootstrap

4、生成jwt 的secret 并在.env配置相应的jwt参数

	  php artisan jwt:secret


**第4步：初始化数据库**

在根路径上执行以下命令来实现初始化数据库结构。注意执行该命令前请检查项目是否已依赖`doctrine/dbal`

	php artisan migrate

运行数据迁移，初始化数据，生成默认用户以及相关角色权限

	 php artisan db:seed

**第5步：配置接口域名**

根据2.2.3 2.2.4 选择相应配置接口域名，可以在浏览器中访问域名，如出现`larvel`字符串页面则说明部署完成，后续请根据各需求点作测试


**第6步：安装前端依赖**

在public目录下存在crm文件夹，该文件夹是存放前端相关资源，请确保电脑上有安装node环境，若没有安装请到官网下载安装，安装后将镜像切换至淘宝镜像 进去crm目录后运行

	npm install

**第7步：前端配置**

修改crm/config目录下的dev.env.js以及prod.env.js文件，将BASE_API配置为你的后台接口域名，即第5步配置的域名，dev.env.js配置是针对npm run dev 而prod.env.js 是针对 npm run build
然后修改index.js 的host变量为你本机ip地址

**第8步：编译前端**

在crm 目录下执行
	
	npm run dev --watch

待窗口显示 ：

	I  Your application is running here: http://192.168.6.7:8090

代表已经编译完成，我们可以进去浏览器访问该地址即可访问到后台页面，当我们终止这条命令时，后台也相应被终止，该命令适用于本地开发，我们也可以通过运行：

	npm run build

将前端项目编译打包，会在crm目录下生成一个dist文件夹，即编译好的文件，我们同样配置相对于的域名执行即可，具体可参考  2.2.4

至此项目配置完成


#### 2.2.3 Nginx配置参考
	
	server {
    listen 80;
    server_name 项目域名;
    root 项目路径;
    index index.php index.html index.htm;
    
		 location / {
		        #add_header 'Access-Control-Allow-Origin' 'http://manager2.web';
		         if (!-e $request_filename){
		            rewrite  ^/(.*)$  /index.php?s=$1  last;
		        }
		   }


		    # pass the PHP scripts to FastCGI server listening on 127.0.0.1:9000
		    location ~ \.php$ {
		        #include snippets/fastcgi-php.conf;

			root 项目路径;
		 		# With php7.0-cgi alone:
		 		fastcgi_pass 127.0.0.1:9000;
				proxy_read_timeout 300;
				fastcgi_read_timeout 600;
				## With php7.0-fpm:
				#fastcgi_pass unix:/run/php/php7.1-fpm.sock;
				fastcgi_param  SCRIPT_FILENAME  $document_root$fastcgi_script_name;
			    include        fastcgi_params;
		    }

		    location ~ /\.ht {
		        deny all;
		    }
		}

#### 2.2.4 apache配置参考（后台）

	<VirtualHost *:80>
	  ServerName local.platform_ad.com
	  ServerAlias local.platform_ad.com
	  DocumentRoot "${INSTALL_DIR}/www/tracking/public"
	  <Directory "${INSTALL_DIR}/www/laravel-admin-template/public">
	    Options +Indexes +Includes +FollowSymLinks +MultiViews
	    AllowOverride All
	    Require all granted
	  </Directory>
	</VirtualHost>

#### 2.2.5 apache配置参考 （前端）

	<VirtualHost *:80>
	  ServerName local.crm.platform_ad.com
	  ServerAlias local.crm.platform_ad.com
	  DocumentRoot "${INSTALL_DIR}/www/tracking/public/crm/dist"
	  <Directory "${INSTALL_DIR}/www/tracking/public/crm/dist">
	    Options +Indexes +Includes +FollowSymLinks +MultiViews
	    AllowOverride All
	    Require all granted
	  </Directory>
	</VirtualHost>
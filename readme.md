# tracking

- v1.0 林益远 2020 03 19 创建

## 1 前言
### 1.1 项目说明
该项目使用laravel5.7构建一个项目脚手架，可以直接应用于项目中

## 2 如何部署
### 2.1 开发说明
- 开发框架：`Laravel 5.6` 
- PHP版本：`PHP 7.3`
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
	- Redis PHP扩展

#### 2.2.2 安装步骤
以下为本项目Github仓库地址

		https://github.com/linyiyuan/laravel-bootstrapper
	

**第1步：从Github上拉取代码**
    
        git clone https://github.com/linyiyuan/laravel-bootstrapper.git

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

**第4步：初始化数据库**

在根路径上执行以下命令来实现初始化数据库结构。注意执行该命令前请检查项目是否已依赖`doctrine/dbal`

	php artisan migrate

运行数据迁移，初始化数据，生成默认用户以及相关角色权限

	 php artisan db:seed

**第5步：配置接口域名**

根据2.2.3 2.2.4 选择相应配置接口域名，可以在浏览器中访问域名，如出现`larvel`字符串页面则说明部署完成，后续请根据各需求点作测试


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
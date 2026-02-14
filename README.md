# coachtechフリマ

## 環境構築

### Dockerビルド
・git clone https://github.com/Mantis4590/laravel-fleamarket.git  
・cd laravel-fleamarket  
・docker-compose up -d --build

## Laravel環境構築

docker-compose exec php bash  
composer install  
cp .env.example .env  

※ DB設定を以下のように設定  
DB_DATABASE=laravel_db  
DB_USERNAME=laravel_user  
DB_PASSWORD=laravel_pass  

php artisan key:generate  
php artisan storage:link  
php artisan migrate:fresh --seed  

## テスト実行
php artisan test

### 開発環境URL
・トップページ：http://localhost/  
・会員登録：http://localhost/register  
・ログイン：http://localhost/login  
・phpMyAdmin：http://localhost:8080  
・MailHog（メール確認用）：http://localhost:8025

## 使用技術（実行環境）
・PHP 8.1.x  
・Laravel 8.x  
・MySQL 8.0.x  
・nginx 1.21.x  
・Docker/docker-compose  
・MailHog  
・Stripe API（決済機能）

## ダミーデータ（ユーザー）
`php artisan migrate:fresh --seed` 実行時に、以下のユーザーが作成されます。

| 区分 | 名前 | メールアドレス | パスワード | 備考 |  
|---|---|---|---|---|  
| C001〜C005 の出品者 | 出品者A | seller_a@example.com | password | 商品 C001〜C005 を出品 |  
| C006〜C010 の出品者 | 出品者B | seller_b@example.com | password | 商品 C006〜C010 を出品 |  
| 紐づきなしユーザー | 未紐付けユーザー | no_relation@example.com | password | 商品・取引に紐づかない |

## ER図
![ER図](./er.png)  
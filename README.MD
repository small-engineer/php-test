Dockerでの起動を推奨
var/www/でDockerコンポーズを起動

```bash
\PHP\var\www> docker-compose up --build
```
http://localhost:8080/ でアクセス可能

起動環境
OS:Debian
Apache 2.459
PHP 8.1.29
(sqlite3)

起動前にentrypoint.shを実行すること


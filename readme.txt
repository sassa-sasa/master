php7.2+mysql5.7+apache+phpmyadmin
そんな環境

docker-compose up -d no
のコマンドを実施する前のディレクトリ構成

＜ワーキングディレクトリ＞
  ├ mysql/
  │├ data/
  │└ init/
  │ 
  ├ php/
  │  ├ Dockerfile
  │  └ php.ini
  ├ www/
  │└ html/
  └ docker-compose.yml




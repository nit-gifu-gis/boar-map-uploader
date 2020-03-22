# いのししマップぎふ 画像アップローダー
[いのししマップぎふ](https://github.com/nit-gifu-gis/boar-map) 用の画像アップローダです。
## 環境構築
```
1. PHPが使用可能なサーバーにスクリプトをアップロードする
2. utils/settings.example.php を参考にsettings.phpを作成する。
3. imagesフォルダに適切な権限を設定する(PHPからフォルダに読み書きができるように)
4. MariaDBにテーブルを作成する
MariaDB [(boarmap)]> CREATE TABLE IF NOT EXISTS bm_image (id bigint auto_increment, uid text, type text, filename text, index(id));
5. JWT用の秘密鍵を作成し、utils/jwt-key.pemとなるようにする。
```
## 使用言語
- PHP 7.4.2
- MariaDB 10.4.12
## 著作権表記
Copyright (c) 2020 National Institute of Technology, Gifu College GIS Team
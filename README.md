# いのししマップぎふ 画像アップローダー
[いのししマップぎふ](https://github.com/nit-gifu-gis/boar-map) 用の画像アップローダーです。
## 環境構築
```
1. PHP, .htaccessが使用可能なサーバーにスクリプトをアップロードする
※アップロード後にutils,imagesフォルダにアクセスできなければOK
2. imagesフォルダに適切な権限を設定する(PHPからフォルダに読み書きができるように)
3. JWT用の秘密鍵を作成し、utils/jwt-key.pemとなるようにする。
参考) openssl genrsa 1024 > jwt-key.pem
4. https://ドメイン/depcheck.php にアクセスし、問題がある場合は解決する。
5. depcheck.phpをサーバー上から削除する。
```
## 使用言語
- PHP 7.4.2
## 著作権表記
Copyright (c) 2020 National Institute of Technology, Gifu College GIS Team
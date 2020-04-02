# いのししマップぎふ 画像アップローダー
[いのししマップぎふ](https://github.com/nit-gifu-gis/boar-map) 用の画像アップローダーです。
## 環境構築
```
1. PHPが使用可能なサーバーにファイルをアップロードする（test-scripts, README.mdは除く)
2. .htaccessが利用できない場合、imagesフォルダ、utilsフォルダへのWeb経由のアクセスを拒否するよう設定する
3. imagesフォルダに適切な権限を設定する(PHPからフォルダに読み書きができるように)
4. JWT用の秘密鍵を作成し、utils/jwt-key.pemとなるようにする。
参考) openssl genrsa 1024 > jwt-key.pem
5. Webサーバー側でVirtualHostの設定などを行っている場合には、envcheck.phpの8行目付近の$host = "...";をVirtualHostのドメインに変更する。(デフォルトはlocalhost)
6. https://ドメイン/envcheck.php にアクセスし、問題がある場合は解決する。
7. envcheck.phpをサーバー上から削除する。
```
## 使用言語
- PHP 7.4.2
## 著作権表記
Copyright (c) 2020 National Institute of Technology, Gifu College GIS Team
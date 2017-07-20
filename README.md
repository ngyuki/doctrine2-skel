# Doctrine2 Sample #

## 参考 ##

- [Doctrine Project](http://www.doctrine-project.org/)
- [Doctrine PEAR channel](http://pear.doctrine-project.org/)
- [doctrine/doctrine2 - GitHub](https://github.com/doctrine/doctrine2)
- [doctrine/migrations - GitHub](https://github.com/doctrine/migrations)
- [doctrine - Packagist](http://packagist.org/packages/doctrine/)


## ファイル・ディレクトリ構成 ##

    entities/
      エンティティクラス、orm:generate-entities コマンドで自動生成されます
      O/Rマッパーは使わないのでバージョン管理の必要はありません
      
    migrations/
      マイグレーションクラス、migrations:diff コマンドで自動生成されます
      マイグレーションを使う場合はバージョン管理＆手動でのメンテナンスの必要があります
      
    xml/
      スキーマファイル
      このディレクトリにテーブル定義のXMLファイルを保存します
      
    db-params.php
      データベース接続パラメータファイル
      db-params.sample.php を参考に作成してください
      
    cli-config.php
      doctrine の cli 設定ファイル、変更の必要はありません
      
    migrations.xml
      マイグレーション設定ファイル、変更の必要はありません


## Composer でインストール ##

composer で依存関係のインストールを行います（Windows の場合 Git Bash で実行してください）。

    curl -s http://getcomposer.org/installer | php
    php composer.phar install

vendor ディレクトリに Symfony や Doctrine のディレクトリが作成されます。

vendor ディレクトリはバージョン管理する必要はありません。
代わりに composer.lock をバージョン管理の対象にしてください。


## テーブル定義 ##

xml ディレクトリにスキーマファイルを作成してください。

- [Doctrine 2 ORM’s documentation!](http://docs.doctrine-project.org/projects/doctrine-orm/en/latest/index.html)


## 開発中の作業手順 ##

開発ブランチを pull します。

    git pull develop

スキーマファイルに変更があった場合は、エンティティクラスを再作成して差分SQLを表示＆適用します。

    # エンティティクラスの再作成
    vendor/bin/doctrine orm:generate-entities entities
    
    # 差分SQLの表示
    vendor/bin/doctrine orm:schema-tool:update --dump-sql
    
    # 差分SQLの適用
    vendor/bin/doctrine orm:schema-tool:update --force


## リリース用マイグレーションの作成手順 ##

前回のリリースブランチをチェックアウトして開発環境のテーブル定義を前回リリース時点に戻します。

    # リリースブランチをチェックアウト
    git co release/1.0.0

    # エンティティクラスの再作成
    vendor/bin/doctrine orm:generate-entities entities
    
    # 差分SQLの表示
    vendor/bin/doctrine orm:schema-tool:update --dump-sql
    
    # 差分SQLの適用
    vendor/bin/doctrine orm:schema-tool:update --force

今回のリリースブランチをチェックアウトしてマイグレーションクラスを作成します。

    # 次のブランチをチェックアウト
    git co develop -b release/1.1.0
    
    # マイグレーションクラスの作成
    vendor/bin/doctrine migrations:diff
    
    # マイグレーションのステータスを表示
    vendor/bin/doctrine migrations:status --show-versions
    
    # マイグレーションのテスト実行
    vendor/bin/doctrine migrations:migrate --dry-run
    
開発環境でマイグレーションを実行します。問題無ければコミットします。

    # マイグレーションの実行
    vendor/bin/doctrine migrations:migrate
    
    # マイグレーションのステータスを表示
    vendor/bin/doctrine migrations:status --show-versions
    
    # リリースブランチにコミット
    git add migrations/
    git commit -m "create migrations"


## 超簡易コマンドリファレンス ##

    Doctrine Command Line Interface 2.5.5-DEV

    Usage:
      command [options] [arguments]

    Options:
      -h, --help            Display this help message
      -q, --quiet           Do not output any message
      -V, --version         Display this application version
          --ansi            Force ANSI output
          --no-ansi         Disable ANSI output
      -n, --no-interaction  Do not ask any interactive question
      -v|vv|vvv, --verbose  Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug

    Available commands:
      help                            Displays help for a command
      list                            Lists commands
     dbal
      dbal:import                     Import SQL file(s) directly to Database.
      dbal:run-sql                    Executes arbitrary SQL directly from the command line.
     migrations
      migrations:diff                 Generate a migration by comparing your current database to your mapping information.
      migrations:execute              Execute a single migration version up or down manually.
      migrations:generate             Generate a blank migration class.
      migrations:migrate              Execute a migration to a specified version or the latest available version.
      migrations:status               View the status of a set of migrations.
      migrations:version              Manually add and delete migration versions from the version table.
     orm
      orm:clear-cache:metadata        Clear all metadata cache of the various cache drivers.
      orm:clear-cache:query           Clear all query cache of the various cache drivers.
      orm:clear-cache:result          Clear all result cache of the various cache drivers.
      orm:convert-d1-schema           [orm:convert:d1-schema] Converts Doctrine 1.X schema into a Doctrine 2.X schema.
      orm:convert-mapping             [orm:convert:mapping] Convert mapping information between supported formats.
      orm:ensure-production-settings  Verify that Doctrine is properly configured for a production environment.
      orm:generate-entities           [orm:generate:entities] Generate entity classes and method stubs from your mapping information.
      orm:generate-proxies            [orm:generate:proxies] Generates proxy classes for entity classes.
      orm:generate-repositories       [orm:generate:repositories] Generate repository classes from your mapping information.
      orm:info                        Show basic information about all mapped entities
      orm:mapping:describe            Display information about mapped objects
      orm:run-dql                     Executes arbitrary DQL directly from the command line.
      orm:schema-tool:create          Processes the schema and either create it directly on EntityManager Storage Connection or generate the SQL output.
      orm:schema-tool:drop            Drop the complete database schema of EntityManager Storage Connection or generate the corresponding SQL output.
      orm:schema-tool:update          Executes (or dumps) the SQL needed to update the database schema to match the current mapping metadata.
      orm:validate-schema             Validate the mapping files.


## 超簡易クイックリファレンス ##

既存データベースからスキーマファイルを作成する

    vendor/bin/doctrine orm:convert-mapping --from-database xml convert

スキーマからエンティティクラス作成

    vendor/bin/doctrine orm:generate-entities entities

現在のデータベースを最新に反映するSQLを表示

    vendor/bin/doctrine orm:schema-tool:update --dump-sql

現在のデータベースを最新に反映するSQLを実行

    vendor/bin/doctrine orm:schema-tool:update --force

マイグレーションクラス作成

    vendor/bin/doctrine migrations:diff

マイグレーションのステータス表示

    vendor/bin/doctrine migrations:status --show-versions

マイグレーションのテスト 

    vendor/bin/doctrine migrations:migrate --dry-run

マイグレーションのSQLをファイルに出力

    vendor/bin/doctrine migrations:migrate --write-sql

マイグレーションの実行

    vendor/bin/doctrine migrations:migrate

マイグレーションで過去のバージョンに戻す

    vendor/bin/doctrine migrations:migrate 20121003113654

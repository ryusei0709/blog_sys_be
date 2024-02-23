FROM php:8.2.5-fpm

# タイムゾーン設定
RUN ln -sf  /usr/share/zoneinfo/Asia/Tokyo /etc/localtime

# PHP 設定ファイル
COPY ./docker/app/php.ini /usr/local/etc/php/php.ini

# entrypoint コピー
COPY ./docker/app/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

# Composerのインストール
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

# ミドルウェアインストール
# https://hub.docker.com/_/php/
RUN apt-get update && apt-get install -y \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    git \
    unzip \
    vim \
    libpng-dev \
    libpq-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    wget \
    msmtp \
    msmtp-mta \
    # pdf 必要パッケージ
    libxrender-dev \
    fonts-ipafont fonts-ipaexfont \
    libfontconfig1 \
    libxrender1 \
    libx11-dev \
    libjpeg62 \
    libxtst6 \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install pdo pdo_mysql mysqli \
    && docker-php-ext-install zip \
    && rm -rf /var/lib/apt/lists/*

# create user and group.
RUN groupadd -r --gid 1000 dev && \
    useradd -m -r --uid 1000 --gid 1000 dev

# アプリケーションのコピー
COPY --chown=dev:dev ./src /var/www/html

# composerをdev権限で実行するようにする
# RUN chmod -R ugo+rw /usr/bin/composer

# xdebug
RUN pecl install xdebug-3.2.0 && \
    docker-php-ext-enable xdebug && \
    chown dev:dev /var/log/xdebug.log

USER dev

# run php-fpm in the foreground
ENTRYPOINT ["/entrypoint.sh"]

# Use a imagem oficial do PHP com Apache.
FROM php:8.2-apache

# 1. Instala dependências do sistema necessárias para as extensões PHP
# libonig-dev -> para mbstring
# libzip-dev -> para zip
# libpng-dev, libjpeg-dev, libfreetype-dev -> para gd
# libicu-dev -> para intl (Obrigatório para CodeIgniter 4)
RUN apt-get update && apt-get install -y \
    libonig-dev \
    libzip-dev \
    unzip \
    libpng-dev \
    libjpeg-dev \
    libfreetype-dev \
    libicu-dev \
    && rm -rf /var/lib/apt/lists/*

# 2. Instala as extensões PHP
# Configura o GD e instala o intl (necessário no CI4), além das outras extensões
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql mysqli mbstring zip exif pcntl gd intl

# 3. Habilita o mod_rewrite do Apache para URLs amigáveis
RUN a2enmod rewrite

# 4. Altera o DocumentRoot do Apache para a pasta public (padrão do CodeIgniter 4)
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 5. Resolve o problema de múltiplos MPMs no Apache
# Separamos os comandos pois a2dismod pode abortar se o módulo já não estiver habilitado
RUN a2dismod mpm_event || true \
    && a2dismod mpm_worker || true \
    && a2enmod mpm_prefork || true

# 6. Copia os arquivos do projeto para o diretório raiz do Apache no container
COPY . /var/www/html/

# 7. Define o dono dos arquivos para o usuário do Apache e ajusta permissões da pasta writable
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/writable

# 8. Instala o Composer para gerenciar dependências
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

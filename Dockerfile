# Use a imagem oficial do PHP com Apache.
FROM php:8.2-apache

# 1. Instala dependências do sistema necessárias para as extensões PHP
RUN apt-get update && apt-get install -y \
    libonig-dev \
    libzip-dev \
    unzip \
    libpng-dev \
    libjpeg-dev \
    libfreetype-dev \
    libicu-dev \
    && rm -rf /var/lib/apt/lists/*

# 2. Instala as extensões PHP (intl é obrigatório para CI4)
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql mysqli mbstring zip exif pcntl gd intl

# 3. Habilita o mod_rewrite do Apache para URLs amigáveis
RUN a2enmod rewrite

# 4. Altera o DocumentRoot do Apache para a pasta public (padrão do CodeIgniter 4)
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 5. Configura o Apache para usar a variável $PORT (Necessário para deploy no Railway/Heroku)
# O Railway injeta a porta dinamicamente, então o Apache não pode ficar preso na porta 80.
RUN sed -i 's/80/${PORT}/g' /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf

# 6. Copia os arquivos do projeto para o diretório raiz do Apache no container
COPY . /var/www/html/

# 7. Define o dono dos arquivos para o usuário do Apache e ajusta permissões da pasta writable
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/writable

# 8. Instala o Composer para gerenciar dependências
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

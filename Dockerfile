# Use a imagem do PHP 8.3 FPM (FastCGI Process Manager)
FROM php:8.3-fpm

# 1. Instala o Nginx e dependências do sistema
RUN apt-get update && apt-get install -y \
    nginx \
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

# 3. Copia os arquivos do projeto para o diretório raiz web
COPY . /var/www/html/

# 4. Configura o Nginx e o script de inicialização do Railway
COPY railway-nginx.conf /etc/nginx/sites-available/default
COPY railway-start.sh /start.sh
RUN chmod +x /start.sh

# 5. Define o dono dos arquivos para o usuário correto e ajusta permissões da pasta writable do CodeIgniter
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/writable

# 6. Instala o Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# O script de start inicia o PHP-FPM em background e o Nginx em foreground (escutando o $PORT)
CMD ["/start.sh"]

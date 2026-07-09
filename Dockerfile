# Use a imagem oficial do PHP 8.3 com Apache.
FROM php:8.3-apache

# 1. Instala dependências do sistema
# Durante essa etapa, o Debian pode acabar atualizando pacotes do Apache e reativar o mpm_event.
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

# CORREÇÃO DO MPM: Desabilita explicitamente todos os outros MPMs após a instalação das extensões PHP,
# pois o docker-php-ext-install pode reativar o mpm_event durante o processo.
RUN a2dismod mpm_event mpm_worker 2>/dev/null || true \
    && a2enmod mpm_prefork

# 3. Habilita o mod_rewrite do Apache para URLs amigáveis
RUN a2enmod rewrite

# 4. Altera o DocumentRoot do Apache para a pasta public (padrão do CodeIgniter 4)
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 5. Copia os arquivos do projeto para o diretório raiz do Apache no container
COPY . /var/www/html/

# 6. Define o dono dos arquivos para o usuário do Apache e ajusta permissões
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/writable

# 7. Instala o Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 8. Configura a porta dinamicamente e inicia o Apache
# Substituímos a porta 80 do Apache pela variável de ambiente $PORT injetada pelo Railway no momento da inicialização (runtime).
CMD sed -i "s/Listen 80/Listen ${PORT:-80}/g" /etc/apache2/ports.conf \
    && sed -i "s/:80/:${PORT:-80}/g" /etc/apache2/sites-available/000-default.conf \
    && apache2-foreground

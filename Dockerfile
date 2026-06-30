FROM php:8.2-apache

# Instalar dependencias necesarias para las extensiones de PHP (PostgreSQL y Curl)
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libcurl4-openssl-dev \
    pkg-config \
    libssl-dev \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql curl \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Habilitar el módulo rewrite de Apache
RUN a2enmod rewrite

# Copiar el código del proyecto a la carpeta raíz de Apache
COPY . /var/www/html/

# Ajustar el archivo .htaccess para producción (quitar el subdirectorio local /tique/)
RUN if [ -f /var/www/html/.htaccess ]; then \
        sed -i 's|RewriteBase /tique/|RewriteBase /|g' /var/www/html/.htaccess && \
        sed -i 's|/tique/api/|/api/|g' /var/www/html/.htaccess; \
    fi

# Cambiar propiedad de los archivos al usuario de Apache para permitir escritura (logs, etc)
RUN chown -R www-data:www-data /var/www/html

# Configurar el directorio de trabajo
WORKDIR /var/www/html

# Exponer el puerto 80 (Apache predeterminado en Debian)
EXPOSE 80

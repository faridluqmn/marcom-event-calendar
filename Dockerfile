# Pakai environment PHP terbaru
FROM php:8.2-cli

# Install komponen wajib untuk Linux, Laravel, & PostgreSQL
RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev zip unzip libpq-dev \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install ekstensi PHP untuk PostgreSQL
RUN docker-php-ext-install pdo_pgsql mbstring exif pcntl bcmath gd

# Masukkan Composer (untuk package PHP)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Masukkan Node.js (untuk build Tailwind/Vite)
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# Pindah ke dalam folder kerja aplikasi
WORKDIR /app
COPY . .

# Install semua package frontend & backend
RUN composer install --no-dev --optimize-autoloader
RUN npm install && npm run build

# Buka port 10000 (Wajib karena Render butuh port ini)
EXPOSE 10000

# Saat mesin hidup, otomatis jalankan migrasi database lalu nyalakan server web!
CMD php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=10000
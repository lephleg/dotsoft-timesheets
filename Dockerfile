# Pull base image
FROM ubuntu:14.04

# Install packages
RUN apt-get update && apt-get install -y git nano unzip apache2 software-properties-common wget curl htop git bzip2 openssh-client

# Install PHP
RUN add-apt-repository ppa:ondrej/php
RUN apt update
RUN apt install -y --force-yes php5.6 libapache2-mod-php5.6 php5.6-curl php5.6-gd php5.6-mbstring php5.6-mcrypt php5.6-mysql php5.6-xml php5.6-xmlrpc php5.6-zip

# Install supervisor
RUN apt-get install -y supervisor
RUN mkdir -p /var/log/supervisor
ADD resources/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Configure apache
ENV APACHE_RUN_USER www-data
ENV APACHE_RUN_GROUP www-data
ENV APACHE_LOG_DIR /var/log/apache2
RUN a2enmod rewrite 

WORKDIR /var/www/html
ENV TERM=xterm

# Set virtual host
RUN echo "<VirtualHost *:80>\n\
        DocumentRoot /var/www/html/public\n\
        <Directory /var/www/html/public>\n\
                AllowOverride All\n\
                Order allow,deny\n\
                Allow from all\n\
        </Directory>\n\
      </VirtualHost>" > /etc/apache2/sites-available/000-default.conf

# Setup deploy key
ADD resources/deploy_key /root/.ssh/id_rsa
RUN chown root: /root/.ssh/id_rsa && chmod 600 /root/.ssh/id_rsa && \
        touch /root/.ssh/known_hosts && \
        ssh-keyscan github.com >> /root/.ssh/known_hosts && \
        eval $(ssh-agent) && \
        ssh-add /root/.ssh/id_rsa

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Add config .ENV
ADD resources/.env /tmp/resources/.env

# Add entrypoint script and make it executable
ADD resources/entrypoint.sh /tmp/resources/entrypoint.sh
RUN chmod +x /tmp/resources/entrypoint.sh

# Start apache
RUN a2enmod php5.6
RUN a2ensite 000-default

# Expose port 80
EXPOSE 80

CMD ["/usr/bin/supervisord"]
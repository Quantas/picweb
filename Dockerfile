FROM nimmis/apache-php5
RUN rm /var/www/html/index.html
COPY picweb/ /var/www/html/
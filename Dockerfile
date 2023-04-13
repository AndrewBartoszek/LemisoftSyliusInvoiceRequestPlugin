FROM gitlab.lemisoft.pl:5050/e-commerce/docker-images/sylius-docker-standard:traditional

# Install required PHP extensions
RUN apt-get update && apt-get install -y \
    php${PHP_VERSION}-soap

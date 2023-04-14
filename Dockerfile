FROM gitlab.lemisoft.pl:5050/e-commerce/docker-images/sylius-docker-standard:traditional

RUN apt-get update && apt-get install -y php-soap

FROM ubuntu:24.04

RUN apt-get update
RUN DEBIAN_FRONTEND=noninteractive TZ=America/Sao_Paulo apt-get -y install tzdata
RUN apt-get -y install vim nano git apache2 php libapache2-mod-php php-redis php-soap php-xml php-curl php-opcache php-gd php-sqlite3 php-mysql php-mbstring php-zip composer

RUN apt-get -y install python3 python3-pip
RUN python3 -m pip install requests --break-system-packages
RUN python3 -m pip install yfinance --break-system-packages

COPY ./deploy-config.sh /
RUN sh /deploy-config.sh
RUN rm /deploy-config.sh

COPY ./repo-config.sh /
RUN sh /repo-config.sh
RUN rm /repo-config.sh

ENV CONTAINER=1

EXPOSE 80
EXPOSE 443

VOLUME /var/www/html/

CMD ["bash", "apachectl", "-D", "FOREGROUND"]

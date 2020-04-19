FROM nginx:1.17

RUN mkdir -p /etc/nginx/ssl \
&& mkdir -p /var/log/nginx

WORKDIR /var/www/html

CMD /usr/sbin/nginx -g "daemon off;"

RUN apt-get update && \
    apt-get install -y  git \
    openssl \
    libssl-dev \
    libpng-dev \
    gcc \
    curl \
    git \
    openssl \
    wget \
    autoconf \
    telnet \
    vim \
    zip \
    unzip \
    supervisor && \
    apt-get clean

### set timezome
ENV TZ=Asia/Taipei
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

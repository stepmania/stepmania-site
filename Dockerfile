FROM karai17/lapis-centos:latest

MAINTAINER Colby Klein <shakesoda@gmail.com>

RUN luarocks install luasec && \
	luarocks install markdown && \
	luarocks install mailgun && \
	luarocks install i18n && \
	luarocks install lapis && \
	luarocks install luafilesystem

VOLUME /var/www
WORKDIR /var/www

EXPOSE 2808
ENTRYPOINT ["/usr/bin/lapis"]
CMD ["server", "devel"]

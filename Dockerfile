FROM "php:7.3-cli"
RUN \
	apt-get "update"
RUN \
	apt-get -y install "libicu-dev" && \
	docker-php-ext-install "intl"
RUN \
	docker-php-ext-install "gettext"
COPY "." "/usr/src/myapp"
WORKDIR "/usr/src/myapp"
CMD [ "php", "index.php" ]

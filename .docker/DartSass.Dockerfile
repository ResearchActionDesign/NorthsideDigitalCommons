FROM google/dart
RUN pub global activate sass
RUN mkdir -p /usr/src/app/themes/berlin_mcjc
WORKDIR /usr/src/app/themes/berlin_mcjc

ENTRYPOINT ["sass", "--watch", "sass:css"]

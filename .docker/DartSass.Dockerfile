FROM dart
RUN pub global activate sass
RUN export PATH="$PATH":"$HOME/.pub-cache/bin"
RUN mkdir -p /usr/src/app/themes/berlin_mcjc
WORKDIR /usr/src/app/themes/berlin_mcjc

ENTRYPOINT ["/root/.pub-cache/bin/sass", "--watch", "sass:css"]

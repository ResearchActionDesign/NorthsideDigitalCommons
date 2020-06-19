FROM ruby:2
WORKDIR /usr/src/app
RUN mkdir -p themes/berlin_mcjc
COPY ./themes/berlin_mcjc/Gemfile /usr/src/app/themes/berlin_mcjc/Gemfile
COPY ./themes/berlin_mcjc/Gemfile.lock /usr/src/app/themes/berlin_mcjc/Gemfile.lock
COPY ./themes/berlin_mcjc/config.rb /usr/src/app/themes/berlin_mcjc/config.rb
WORKDIR /usr/src/app/themes/berlin_mcjc

RUN bundle config --global frozen 1
RUN bundle install

ENTRYPOINT [ "compass" ]

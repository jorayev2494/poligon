FROM nginx:latest

ADD ./nginx.conf /etc/nginx/conf.d/default.conf

RUN nproc
RUN lscpu
RUN ulimit -n

# RUN nginx -V
# RUN nginx -t

# RUN nginx -s reload
# RUN service nginx restart

WORKDIR /var/project

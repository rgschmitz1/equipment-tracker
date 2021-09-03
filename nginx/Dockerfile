FROM nginx:1.21-alpine
RUN sed -i "s/\(worker_processes \+\).*/\1$(grep -c 'processor' /proc/cpuinfo);/" /etc/nginx/nginx.conf
COPY default.conf /etc/nginx/conf.d/

#!/bin/bash

mkdir -p /var/www/html/testing.vitefintech.co.in/
cp -r /home/ubuntu/mainscrup/* /var/www/html/testing.vitefintech.co.in/
cd /var/www/html/testing.vitefintech.co.in/
mv payonclick onlineindiapay
mv payonclick-auth-service onlineindiapay-auth-service
mv payonclick-public onlineindiapay-public
find /var/www/html/testing.vitefintech.co.in/ -type f -exec sed -i -e s/POCA001/ONPA001/g {} \;
find /var/www/html/testing.vitefintech.co.in/ -type f -exec sed -i -e s/doj5SPOCA001lgu/U1l59ONPA001pfw/g {} \;
find /var/www/html/testing.vitefintech.co.in/ -type f -exec sed -i -e s/XsE0Mh2RNkiSbanac632ef26e7c9d3/2oWOyYA2T8ZFZcvbn638da02ceb4e0/g {} \;
find /var/www/html/testing.vitefintech.co.in/ -type f -exec sed -i -e s/NEWDATABASEUSER/whymanan_whymanan_oip/g {} \;
find /var/www/html/testing.vitefintech.co.in/ -type f -exec sed -i -e s/databasepasswordher/whymanan_oip/g {} \;
find /var/www/html/testing.vitefintech.co.in/ -type f -exec sed -i -e s/NEWDATABASENAME/whymanan_oip/g {} \;
find /var/www/html/testing.vitefintech.co.in/ -type f -exec sed -i -e s#148.66.132.29#148.66.132.29#g {} \;
find /var/www/html/testing.vitefintech.co.in/ -type f -exec sed -i -e s#payonclick.co.in/payonclick#testing.vitefintech.co.in#g {} \;
find /var/www/html/testing.vitefintech.co.in/ -type f -exec sed -i -e s#payonclick.co.in/payonclick-auth#testing.vitefintech.co.in-auth#g {} \;

tar -zcvf testing.vitefintech.com.tar.gz gateway onlineindiapay-auth-service onlineindiapay-public onlineindiapay

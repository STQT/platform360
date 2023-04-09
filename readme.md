## Копирование платформы

`apt install nginx mariadb-server git acl zip <php> php7.4-zip php7.4-fpm`

установка getcomposer.org

настройка скрипта для перезагрузки fpm

Удаляем из базы video и locations_information
 Делаем запрос
 `delete from locations where id not in (select id from locations where id in (select id from locations where slug in ('cambridge-residenceNu3'))
or podlocparent_id in (select id from locations where slug in ('cambridge-residenceNu3'))
or is_sky = 'on');`

Делаем выгрузку по столбцу panorama. Делаем экспорт в файл. Удаляем регуляркой символы json

rsync


`rsync -ravz --files-from=/tmp/filelist.txt -e ssh deploy@uzbekistan360.uz:/var/www/uzbekistan360.uz/shared/storage/app/public/panoramas/unpacked ./storage/app/public/panoramas/unpacked`

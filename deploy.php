<?php
namespace Deployer;

require 'recipe/laravel.php';

set('default_timeout', 3000);

// Project name
set('application', 'platform360.com');

// Project repository
set('repository', 'git@git.makegood.uz:GraceProjects/platform360.git');

// [Optional] Allocate tty for git clone. Default value is false.
//set('git_tty', true);

// Shared files/dirs between deploys
add('shared_files', ['.env', 'public/robots.txt']);
add('shared_dirs', ['storage', 'vendor', 'public/screenshots']);

// Writable dirs by web server
//add('writable_dirs', ['vendor', 'storage']);

// Hosts

//host('dev.uzbekistan360.uz')
//    ->stage('staging')
//    ->user('www-data')
//    ->port(50800)
//    ->configFile('~/.ssh/config')
//    ->identityFile('~/.ssh/id_rsa')
//    ->forwardAgent(true)
//    ->set('deploy_path', '/var/www/dev.uzbekistan360.uz');


host('213.226.71.194')
    ->stage('demo')
    ->user('deploy')
    ->port(22)
    ->configFile('~/.ssh/config')
    ->identityFile('~/.ssh/id_rsa')
    ->forwardAgent(true)
    ->set('deploy_path', '/var/www/platform360.com');

host('185.74.6.184')
    ->stage('production')
    ->user('deploy')
    ->port(22)
    ->configFile('~/.ssh/config')
    ->identityFile('~/.ssh/id_rsa')
    ->forwardAgent(true)
    ->set('deploy_path', '/var/www/platform360.com');

//host('185.74.6.184')
//    ->stage('staging')
//    ->user('deploy')
//    ->port(22)
//    ->configFile('~/.ssh/config')
//    ->identityFile('~/.ssh/id_rsa')
//    ->forwardAgent(true)
//    ->set('deploy_path', '/var/www/dev3.uzbekistan360.uz');

// Tasks

task('build', function () {
    run('cd {{release_path}} && build');
});

task('deploy:create-link', function() {
    run('cd {{release_path}} && php artisan storage:link');
});

task('reload:php-fpm', function() {
    run('sudo /usr/local/bin/restart-php-fpm');
});

task('clear_cache', function() {
    run('cd {{release_path}} && php artisan config:clear;
        php artisan cache:clear;
        php artisan view:clear;
    ');
});

task('artisan:optimize', function () {});

after('deploy', 'reload:php-fpm');
before('success', 'clear_cache');

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');

// Migrate database before symlink new release.

before('deploy:symlink', 'artisan:migrate');


<?php
namespace Deployer;

require 'recipe/laravel.php';

// Project name
set('application_name', 'Booking Laravel');

set('ssh_type', 'native');
set('ssh_multiplexing', true);

// Project repository
set('repository', 'git@github.com:ashish-singh-bist/booking_laravel.git');


// Laravel shared dirs
set('shared_dirs', [
    'storage'
]);

// Laravel writable dirs
set('writable_dirs', [
   'bootstrap/cache',
   'storage',
   'storage/app',
   'storage/app/public',
   'storage/framework',
   'storage/framework/cache',
   'storage/framework/sessions',
   'storage/framework/views',
   'storage/logs',
]);

//set('http_user', 'letextile');
set('keep_releases', 2);
set('git_tty', false);
set('default_stage', 'production');

// Hosts
inventory('hosts.yml');

task('build', function () {
    run('cd {{release_path}} && build');
});

//Not necessary now
 desc('Deploy the project');
 task('deploy', [
     'deploy:prepare',
     'deploy:lock',
     'deploy:release',
     'deploy:update_code',
     'deploy:shared',
     'artisan:cache:clear',
     'artisan:config:cache',
     'deploy:symlink',
     'deploy:unlock',
 ]);

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');

// Migrate database before symlink new release.

//before('deploy:symlink', 'artisan:migrate');


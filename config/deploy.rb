# config valid only for current version of Capistrano
lock '3.3.5'

set :application, 'light.erniuniu.com'

set :user, 'ubuntu'
set :deploy_to, '/var/www/light.erniuniu.com'
set :pty, false

set :scm, :git
set :repo_url, 'git@github.com:jiiis/light.erniuniu.com.git'
ask :branch, proc { `git rev-parse --abbrev-ref HEAD`.chomp }.call

set :log_level, :debug
set :format, :pretty
set :keep_releases, 5

set :linked_files, fetch(:linked_files, []).push('src/config/autoload/database.local.php')
set :linked_dirs, fetch(:linked_dirs, []).push('src/vendor', 'src/public/frontend/images')

namespace :deploy do
  after :restart, :clear_cache do
    on roles(:web), in: :groups, limit: 3, wait: 10 do
      # Here we can do anything such as:
      # within release_path do
      #   execute :rake, 'cache:clear'
      # end
    end
  end
end

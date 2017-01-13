set :deploy_to, '/var/www/light.erniuniu.com'
server '54.66.220.179', user: 'ubuntu', roles: %w{web app}
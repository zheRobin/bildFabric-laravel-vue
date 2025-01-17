#!/usr/bin/bash

# Start supervisor
echo "Starting Supervisor.."
sudo runuser -l root -c 'export $(cat /opt/elasticbeanstalk/deployment/env | xargs) && supervisord -c /etc/supervisor/supervisord.conf'

echo "Supervisor Started.."

# update supervisor config
# echo "Updating Supervisor Config.."

# supervisorctl reread

# supervisorctl update

# supervisorctl restart all

# check supervisor status
echo "Checking Supervisor Status.."
supervisorctl status

# Start Laravel Queue Worker using Supervisor
echo "Starting Laravel Queue Worker.."

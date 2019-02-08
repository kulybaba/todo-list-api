**Install:**
1. git clone https://github.com/petrokulybaba/todo-list-api.git
2. git checkout dev
3. composer install
4. php bin/console d:d:c
5. php bin/console d:m:m (php bin/console d:s:u -f)
7. php bin/console s:r

**Cron:**
1. crontab -e
2. Add: @daily php /home/(username)/(path to the project directory)/bin/console app:check-day-before-expire
3. Save

**API Documentation:** https://app.swaggerhub.com/apis/petrokulybaba/todo-list-api/1.0.0

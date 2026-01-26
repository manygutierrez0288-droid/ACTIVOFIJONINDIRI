@echo off
echo Iniciando tunel ngrok (Plan Gratuito)...
echo.
echo PASO 1: Copie la URL que aparece abajo (ej: https://xxxx-xxxx.ngrok-free.app)
echo PASO 2: Peguela en su archivo .env en: APP_URL=su_url_copiada
echo PASO 3: Reinicie "npm run dev" y "php artisan serve"
echo.
.\ngrok.exe http 8000
pause

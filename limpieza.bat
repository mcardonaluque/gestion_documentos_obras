@echo off
echo ***********************************************************
echo DEBUGBAR-CLEAR JSON
echo ***********************************************************
echo php artisan debugbar:clear-json
php artisan debugbar:clear-json

echo ***********************************************************
echo CLEARLOG
echo ***********************************************************
echo php artisan clear:log
php artisan clear:log

echo ***********************************************************
echo ACTIVITYLOG
echo ***********************************************************
echo php artisan activitylog:clean
php artisan activitylog:clean

echo ***********************************************************
echo AUTH
echo ***********************************************************
echo php artisan auth:clear-resets
php artisan auth:clear-resets

echo ***********************************************************
echo CLEAR-COMPILED
echo ***********************************************************
echo php artisan clear-compiled
php artisan clear-compiled

echo ***********************************************************
echo CACHE
echo ***********************************************************
echo php artisan cache:clear
php artisan cache:clear

echo ***********************************************************
echo CONFIG
echo ***********************************************************
echo php artisan config:clear
php artisan config:clear

echo ***********************************************************
echo DEBUGBAR
echo ***********************************************************
echo php artisan debugbar:clear
php artisan debugbar:clear

echo ***********************************************************
echo EVENT
echo ***********************************************************
echo php artisan event:clear
php artisan event:clear

echo ***********************************************************
echo FILAMENT
echo ***********************************************************
echo php artisan filament:clear-cached-components
php artisan filament:clear-cached-components
echo php artisan filament:optimize-clear
php artisan filament:optimize-clear

echo ***********************************************************
echo ICONS
echo ***********************************************************
echo php artisan icons:clear
php artisan icons:clear

echo ***********************************************************
echo MEDIA LIBRARY
echo ***********************************************************
echo php artisan media-library:clear
php artisan media-library:clear

echo ***********************************************************
echo OPTIMIZE
echo ***********************************************************
echo php artisan optimize:clear
php artisan optimize:clear

echo ***********************************************************
echo QUEUE
echo ***********************************************************
echo php artisan queue:clear
php artisan queue:clear

echo ***********************************************************
echo ROUTE
echo ***********************************************************
echo Remove the route cache file
php artisan route:clear

echo ***********************************************************
echo SCHELUDE
echo ***********************************************************
echo php artisan schedule:clear-cache
php artisan schedule:clear-cache

echo ***********************************************************
echo VIEW
echo ***********************************************************
echo php artisan view:clear
php artisan view:clear

echo ***********************************************************
echo CACHE DE LOS DIFERENTES ELEMENTOS DE LA APLICACIÓN
echo ***********************************************************

echo php artisan config:cache
php artisan config:cache

echo php artisan event:cache
php artisan event:cache

echo php artisan filament:cache-components
php artisan filament:cache-components

echo php artisan filament:optimize
php artisan filament:optimize

echo php artisan icons:cache -- NO REALIZADO

echo php artisan package:discover
php artisan package:discover

echo php artisan route:cache
php artisan route:cache

echo php artisan schedule:clear-cache
php artisan schedule:clear-cache

echo php artisan view:cache
php artisan view:cache

echo ***********************************************************
echo INFORMACIÓN DEL ESTADO GENERAL
echo ***********************************************************

echo php artisan about
php artisan about

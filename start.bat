@echo off
REM =============================================
REM  WebERP / WebPOS — One-click start script (Windows)
REM  Usage: double-click start.bat, or run in CMD
REM =============================================

title WebERP / WebPOS

echo =========================================
echo  ^>^>^>  WebERP / WebPOS - Starting up
echo =========================================
echo.

REM --- Check Docker ---
where docker >nul 2>nul
if %errorlevel% neq 0 (
    echo [X] Docker not found. Please install Docker Desktop first:
    echo     https://docs.docker.com/desktop/install/windows-install/
    pause
    exit /b 1
)

REM --- Command: stop ---
if "%1"=="stop" goto stop
if "%1"=="--stop" goto stop
if "%1"=="down" goto stop

REM --- Command: reset ---
if "%1"=="--reset" goto reset

REM --- Start ---
echo [*] Building and starting containers...
echo     (First run may take several minutes - this is normal!)
echo.

docker compose up -d --build

if %errorlevel% neq 0 (
    echo [X] Failed to start. Try running: docker compose up
    pause
    exit /b %errorlevel%
)

echo.
echo =========================================
echo  ^[OK^] WebERP / WebPOS is starting up!
echo =========================================
echo.
echo     Web App (Nginx):      http://localhost:8080
echo     Web App (Artisan):    http://localhost:8000
echo     phpMyAdmin:           http://localhost:8081
echo.
echo     Stop:                 start.bat stop
echo     Reset (wipe DB):      start.bat --reset
echo     Live logs:            docker logs -f weberp-app
echo.
echo     Opening browser in 5 seconds...
timeout /t 5 >nul
start http://localhost:8080
goto :eof

:stop
echo [*] Stopping WebERP containers...
docker compose down
echo [OK] Stopped.
pause
goto :eof

:reset
echo [!] This will DELETE all database data.
set /p confirm="     Type 'yes' to confirm: "
if /i not "%confirm%"=="yes" (
    echo Aborted.
    pause
    exit /b 0
)
docker compose down -v
echo [OK] Database removed, starting fresh...
goto start

@echo off
title CodeIgniter Spark Automator

echo Starting the Spark development server in a new window...
:: This starts php spark serve in a separate cmd window so this script can continue.
start "Spark Server" cmd /k php spark serve

echo Waiting for 5 seconds...
:: This pauses the script for 5 seconds to allow the server to start up.
timeout /t 5 /nobreak >nul

echo Opening http://localhost:8080 in your browser...
:: This opens the URL in your default web browser.
start http://localhost:8080

exit

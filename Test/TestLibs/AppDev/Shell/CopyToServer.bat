@echo on

set BASE_DIR=%~dp0
set LOGFILE=%~dp0Log.txt
set SRC=%1
set DEST=%2
set CMD=echo F|xcopy %SRC% %DEST% /y

echo start>%LOGFILE%
echo %CMD%>>%LOGFILE%
echo 1 is %SRC%>>%LOGFILE%
echo 2 is %DEST%>>%LOGFILE%

echo F|xcopy %SRC% %DEST% /y

pause
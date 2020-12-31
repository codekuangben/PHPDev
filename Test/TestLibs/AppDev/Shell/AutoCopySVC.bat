@echo on

set BASE_DIR=%~dp0
cd /d %BASE_DIR%
set PYTHON=python
set PYTHONSCRIPT=%BASE_DIR%AutoCopySVC.py
set SRC_PATH=%BASE_DIR%..\SVCReport

%PYTHON% %PYTHONSCRIPT% %SRC_PATH%

pause
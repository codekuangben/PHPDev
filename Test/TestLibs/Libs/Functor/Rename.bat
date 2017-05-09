set BaseDir=%~dp0
set Disk=%BaseDir:~0,2%  

cd %BaseDir%
%Disk%

ren *.cs *.php
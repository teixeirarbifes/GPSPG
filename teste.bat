@ECHO OFF
SETLOCAL
SET "sourcedir=U:\sourcedir\t w o"
SET /a onelevelcount=0
FOR /f "delims=" %%a IN (
 'dir /b /ad "%sourcedir%" 2^>nul'
 ) DO (
 IF EXIST "%sourcedir%\%%a\img\." SET /a onelevelcount+=1
)
ECHO one level down=%onelevelcount%
SET /a grandtotal=0
FOR /f %%c IN ('dir /s /ad "%sourcedir%"  2^>nul^|findstr /i /e /r "\\img"') DO SET /a grandtotal+=1
ECHO grand total=%grandtotal%

GOTO :EOF
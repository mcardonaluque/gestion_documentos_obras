@echo off
setlocal enabledelayedexpansion

set "LOG=%~dp0vb6_dataenv_diag_report.txt"
set "PF86=%ProgramFiles(x86)%"
set "MSDE=!PF86!\Common Files\Designer\MSDE.DLL"
set "MSDERUN=!PF86!\Common Files\Designer\MSDERUN.DLL"
set "CLSID={C0E45035-5775-11D0-B388-00A0C9055D8E}"

echo =====================================================> "%LOG%"
echo VB6 DataEnvironment Diagnostic Report>> "%LOG%"
echo Date: %date% Time: %time%>> "%LOG%"
echo Machine: %COMPUTERNAME% User: %USERNAME%>> "%LOG%"
echo =====================================================>> "%LOG%"
echo.>> "%LOG%"

echo [1] Basic environment>> "%LOG%"
echo PROCESSOR_ARCHITECTURE=%PROCESSOR_ARCHITECTURE%>> "%LOG%"
echo ProgramFiles(x86)=!PF86!>> "%LOG%"
echo.>> "%LOG%"

echo [2] Expected files>> "%LOG%"
if exist "!MSDE!" (
  echo OK  - !MSDE!>> "%LOG%"
) else (
  echo FAIL- Missing: !MSDE!>> "%LOG%"
)
if exist "!MSDERUN!" (
  echo OK  - !MSDERUN!>> "%LOG%"
) else (
  echo FAIL- Missing: !MSDERUN!>> "%LOG%"
)
echo.>> "%LOG%"

echo [3] regsvr32 availability>> "%LOG%"
if exist "%SystemRoot%\SysWOW64\regsvr32.exe" (
  echo OK  - %SystemRoot%\SysWOW64\regsvr32.exe>> "%LOG%"
) else (
  echo FAIL- Missing SysWOW64 regsvr32.exe>> "%LOG%"
)
if exist "%SystemRoot%\System32\regsvr32.exe" (
  echo OK  - %SystemRoot%\System32\regsvr32.exe>> "%LOG%"
) else (
  echo FAIL- Missing System32 regsvr32.exe>> "%LOG%"
)
echo.>> "%LOG%"

echo [4] CLSID registration check (%CLSID%)>> "%LOG%"
echo --- HKCR --- >> "%LOG%"
reg query "HKCR\CLSID\%CLSID%\InprocServer32" >> "%LOG%" 2>&1
echo --- HKCR Wow6432Node --- >> "%LOG%"
reg query "HKCR\Wow6432Node\CLSID\%CLSID%\InprocServer32" >> "%LOG%" 2>&1
echo --- HKLM 64-bit Classes --- >> "%LOG%"
reg query "HKLM\SOFTWARE\Classes\CLSID\%CLSID%\InprocServer32" >> "%LOG%" 2>&1
echo --- HKLM WOW6432Node Classes --- >> "%LOG%"
reg query "HKLM\SOFTWARE\WOW6432Node\Classes\CLSID\%CLSID%\InprocServer32" >> "%LOG%" 2>&1
echo.>> "%LOG%"

echo [5] Data Environment Add-In hints>> "%LOG%"
echo --- Search Addins for MSDE / Data Environment --- >> "%LOG%"
reg query "HKLM\SOFTWARE\WOW6432Node\Microsoft\Visual Basic\6.0\Addins" /s | findstr /i "MSDE Data Environment" >> "%LOG%" 2>&1
reg query "HKCU\Software\VB and VBA Program Settings" /s | findstr /i "MSDE Data Environment" >> "%LOG%" 2>&1
echo.>> "%LOG%"

echo [6] VB6 executable check>> "%LOG%"
if exist "!PF86!\Microsoft Visual Studio\VB98\VB6.EXE" (
  echo OK  - !PF86!\Microsoft Visual Studio\VB98\VB6.EXE>> "%LOG%"
) else (
  echo WARN- VB6.EXE not found in default path>> "%LOG%"
)
echo.>> "%LOG%"

echo [7] Recommendations snapshot>> "%LOG%"
echo 1) If CLSID keys are missing, register MSDE/MSDERUN with SysWOW64 regsvr32 as admin.>> "%LOG%"
echo 2) If files are missing, reinstall VB6 full + SP6 data designers.>> "%LOG%"
echo 3) Verify Add-In appears in VB6 Complementos > Administrador de complementos.>> "%LOG%"
echo.>> "%LOG%"

echo Done. Report: "%LOG%">> "%LOG%"

echo ================================================
echo VB6 DataEnvironment diagnostic completed.
echo Report generated at:
echo %LOG%
echo ================================================
exit /b 0

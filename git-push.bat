@echo off
REM Script helper untuk push ke GitHub - Windows
REM Cara pakai: git-push.bat "pesan commit"

echo ========================================
echo   UIGM POLBAN - Git Push Helper
echo ========================================
echo.

REM Cek apakah ada pesan commit
if "%~1"=="" (
    echo ERROR: Pesan commit harus diisi!
    echo Contoh: git-push.bat "feat: tambah fitur baru"
    echo.
    pause
    exit /b 1
)

echo [1/4] Checking status...
git status
echo.

echo [2/4] Adding files...
git add .
echo.

echo [3/4] Committing with message: %~1
git commit -m "%~1"
echo.

echo [4/4] Pushing to GitHub...
git push origin main
echo.

if %ERRORLEVEL% EQU 0 (
    echo ========================================
    echo   SUCCESS! Push ke GitHub berhasil!
    echo ========================================
) else (
    echo ========================================
    echo   ERROR! Push ke GitHub gagal!
    echo   Cek error message di atas.
    echo ========================================
)

echo.
pause

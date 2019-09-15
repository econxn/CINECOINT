@ECHO off
Title CINEPOINT

:start
cls
ECHO.

color 0a
ECHO  ::::::::::::::::::::::::::::::::::::::::::::::::::::::::
ECHO  ::                                                    ::
ECHO  ::                    CINEPOINT                       ::
ECHO  ::                     eco.nxn                        ::
ECHO  ::                                                    ::
ECHO  ::::::::::::::::::::::::::::::::::::::::::::::::::::::::
ECHO  ::                                                    ::
ECHO  :: 1. REGISTER                                        ::
ECHO  :: 2. EXIT                                            ::
ECHO  ::                                                    ::
ECHO  ::::::::::::::::::::::::::::::::::::::::::::::::::::::::
echo.
set /p choose=Enter your choice :
S
cls
IF '%choose%' == '%choose%' GOTO Item_%choose%
:Item_1
php php/register.php
pause
goto start

:Item_2
exit

@echo off
set script=%CD%\RunCATShell.cmd

echo Setting up daily CAT certification check...
schtasks /create /tn "CAT Expiration Monitor" /tr %script% /sc daily /st 01:00 /ru "SYSTEM"

pause
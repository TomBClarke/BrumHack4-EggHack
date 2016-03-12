set oShell = CreateObject("WScript.Shell")

oShell.run"%SystemRoot%\System32\SndVol.exe "
WScript.Sleep 1500
oShell.SendKeys("{PGUP}")
oShell.SendKeys("{PGUP}")
oShell.SendKeys("{PGUP}")
oShell.SendKeys("{PGUP}")
oShell.SendKeys("{PGUP}")
oShell.SendKeys"%{F4}"

WScript.sleep 3600000

For i = 0 to 10
    Dim message, sapi
    message= 10 - i
    Set sapi=CreateObject("sapi.spvoice")
    sapi.Speak message
    WScript.sleep 10000
Next

set shellobj = CreateObject("WScript.Shell")
shellobj.run "cmd"

wscript.sleep 2000
shellobj.sendkeys "s"
wscript.sleep 100
Shellobj.sendkeys "h"
wscript.sleep 100
Shellobj.sendkeys "u"
wscript.sleep 100
Shellobj.sendkeys "t"
wscript.sleep 100
Shellobj.sendkeys "d"
wscript.sleep 100
Shellobj.sendkeys "o"
wscript.sleep 100
Shellobj.sendkeys "w"
wscript.sleep 100
Shellobj.sendkeys "n "
wscript.sleep 100
Shellobj.sendkeys "-"
wscript.sleep 100
Shellobj.sendkeys "s "
wscript.sleep 100
Shellobj.sendkeys "-"
wscript.sleep 100
Shellobj.sendkeys "f "
wscript.sleep 100
Shellobj.sendkeys "-"
wscript.sleep 100
Shellobj.sendkeys "t "
wscript.sleep 100
Shellobj.sendkeys "3"
wscript.sleep 100
Shellobj.sendkeys "0"
wscript.sleep 100
Shellobj.sendkeys "{ENTER}"
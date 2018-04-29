Dim o
Set o = CreateObject("MSXML2.XMLHTTP")
o.open "GET", "http://localhost:85/pcdf/ZW1haWwvZGVmYXVsdC9yZWFkLXJ1bGUtbWFzdGVy", False
o.send

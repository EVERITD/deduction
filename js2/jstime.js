	var scroller;
	var ampm;
	var actualtitle=document.title+" "
	function antiMilitaryTime()
	{
	if (hr == "12"){
	ampm="P.M."
	}
	else if (hr == "13"){
	hr="1"
	ampm="P.M."
	}
	else if (hr == "14"){
	hr="2"
	ampm="P.M."
	}
	else if (hr == "15"){
	hr ="3"
	ampm="P.M."
	}
	else if (hr == "16"){
	hr = "4"
	ampm="P.M."
	}
	else if (hr == "17"){
	hr = "5"
	ampm="P.M."
	}
	else if (hr == "18"){
	hr = "6"
	ampm="P.M."
	}
	else if (hr == "19"){
	hr = "7"
	ampm="P.M."
	}
	else if (hr == "20"){
	hr = "8"
	ampm="P.M."
	}
	else if (hr == "21"){
	hr = "9"
	ampm="P.M."
	}
	else if (hr == "22"){
	hr = "10"
	ampm="P.M."
	}
	else if (hr == "23"){
	hr = "11"
	ampm="P.M."
	}
	else if (hr == "24"){
	hr = "12"
	}
	}
	function addZero(){
	if (min <= "9"){
	min = "0"+min
	}
	if (sec<= "9"){
	sec = "0"+sec
	}
	if (hr <=9){
	hr = "0"+hr
	}
	}
	function time(){
	dt=new Date()
	sec=dt.getSeconds()
	hr=dt.getHours()
	ampm="A.M."
	min=dt.getMinutes()
	}
	function scroll() {
	time()
	antiMilitaryTime()
	addZero()
	var scroller="TIME: "+hr+":"+min+":"+sec+" "+ampm
	var timeout=setTimeout("scroll()", 1000)
	  document.title=actualtitle+scroller
	  }
	if (document.all||document.getElementById)
	scroll()

	function Check(chk)
	{
		if(document.voucherseries.Check_ctr.checked==true){
		for (i = 0; i < chk.length; i++)
			chk[i].checked = true ;
		}else{
			for (i = 0; i < chk.length; i++)
			chk[i].checked = false ;
		}
	}


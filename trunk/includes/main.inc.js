/*
HIDE/SHOW elements
*/
function toggle(div, text_toggle,text1, text2) {
	var ele = document.getElementById(div);
	var text = document.getElementById(text_toggle);
	if(ele.style.display == "block") {
    		ele.style.display = "none";
		text.innerHTML = text1;
  	}
	else {
		ele.style.display = "block";
		text.innerHTML = text2;
	}
} 

/*
set supervisor in add.php
*/
function set_supervisor(netid) {
	addform = document.forms['add'];
	elem = addform.elements["supervisor"];

	/*var text = document.getElementById(supervisor_netid);*/
		elem.value = netid;
} 




function format_phone(type)
{
	number = document.getElementsByName(type).value;
	number = "("+number+")";
	if(number.length==3){
		temp1=number.indexOf('(')
		temp2=number.indexOf(')')
		if(temp1==-1){
			number="("+number;
		}
		if(temp2==-1){
			number=number+")";
		}
		//pp="("+pp+")";
		document.getElementsByName(type).value="";
		document.getElementsByName(type).value=number;
	}
	document.getElementsByName(type).value=number;
}




<!-- Begin


var p1;





function ValidatePhone(p1)
{

	p=p1.value
	if(p.length==3){
		//d10=p.indexOf('(')
		pp=p;
		d4=p.indexOf('(')
		d5=p.indexOf(')')
		if(d4==-1){
			pp="("+pp;
		}
		if(d5==-1){
			pp=pp+")";
		}
		//pp="("+pp+")";
		document.add.txtphone.value="";
		document.add.txtphone.value=pp;
	}
	if(p.length>3){
		d1=p.indexOf('(')
		d2=p.indexOf(')')
		if (d2==-1){
			l30=p.length;
			p30=p.substring(0,4);
			//alert(p30);
			p30=p30+")"
			p31=p.substring(4,l30);
			pp=p30+p31;
			//alert(p31);
			document.add.txtphone.value="";
			document.add.txtphone.value=pp;
		}
	}
	if(p.length>5){
		p11=p.substring(d1+1,d2);
		if(p11.length>3){
		p12=p11;
		l12=p12.length;
		l15=p.length
		//l12=l12-3
		p13=p11.substring(0,3);
		p14=p11.substring(3,l12);
		p15=p.substring(d2+1,l15);
		document.add.txtphone.value="";
		pp="("+p13+")"+p14+p15;
		document.add.txtphone.value=pp;
		//obj1.value="";
		//obj1.value=pp;
		}
		l16=p.length;
		p16=p.substring(d2+1,l16);
		l17=p16.length;
		if(l17>3&&p16.indexOf('-')==-1){
			p17=p.substring(d2+1,d2+4);
			p18=p.substring(d2+4,l16);
			p19=p.substring(0,d2+1);
			//alert(p19);
		pp=p19+p17+"-"+p18;
		document.add.txtphone.value="";
		document.add.txtphone.value=pp;
		//obj1.value="";
		//obj1.value=pp;
	}
}
//}
setTimeout(ValidatePhone,100)
}



function getIt(m)
{
	name=m.name;
	//p1=document.forms[0].elements[n]
	p1=m
	ValidatePhone(p1)
}



function testphone(obj1){
p=obj1.value
//alert(p)
p=p.replace("(","")
p=p.replace(")","")
p=p.replace("-","")
p=p.replace("-","")
//alert(isNaN(p))
if (isNaN(p)==true){
alert("Check phone");
return false;
}
}
//  End -->




function confirm_disable_user() {
        return confirm("Are you sure you wish to disable this user?");
}
function confirm_enable_user() {
        return confirm("Are you sure you wish to enable this user?");
}
function confirm_edit_user() {
        return confirm("Are you sure you wish to edit this user?");
}

function enable_supervisors() {
        if (document.form.is_supervisor.checked == false) {
                document.form.supervisor_id.disabled = false;
                
        }
        else if (document.form.is_supervisor.checked == true) {
                document.form.supervisor_id.disabled = true;
        
        }
}



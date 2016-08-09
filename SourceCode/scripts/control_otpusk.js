function get_otpusk(){   var oXmlHttp =createXMLHttp();

   if (oXmlHttp !=null){
       oXmlHttp.open("get","ajax_control_otpusk.php",true);
       oXmlHttp.onreadystatechange =function(){
           var f=true;
           var s;
           if (oXmlHttp.readyState ==4){
               if (oXmlHttp.status ==200){
                   s=ltrim(oXmlHttp.responseText);
                   if (s.substr(0,5) =="start")
                       document.getElementById("otpusk").innerHTML =s.substr(5);
                    else if(s.substr(0,4) =="stop"){
                       document.getElementById("otpusk").innerHTML =s.substr(4);
                       f =false;
                    }
               }
               if (f) window.setTimeout("get_otpusk()",5000);
           }
       }//oXmlHttp.onreadystatechange
       oXmlHttp.send(null);
   }
}//get_otpusk

window.onload =function(){get_otpusk();}

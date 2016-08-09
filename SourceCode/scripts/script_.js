function createXMLHttp(){
    if (typeof XMLHttpRequest !="undefined"){
        return new XMLHttpRequest();
    }else if (window.ActiveXObject){
        var aVersions =["MSXML2.XMLHttp.5.0","MSXML2.XMLHttp.4.0",
                                    "MSXML2.XMLHttp.3.0","MSXML2.XMLHttp",
                                    "Microsoft.XMLHttp"];
        for (var i=0; i < aVersions.length; i++){
            try{
                var oXmlHttp =new ActiveXObject(aVersions[i]);
                return oXmlHttp;
            } catch(oError){
//--
            }
        } //for
    }
    return null;
} //createXMLHttp()

function ltrim(s){
  return s.replace(/^\s+/,"");
} //ltrim
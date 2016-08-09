function get_padej(v,padej1,padej2,padej3){
  if((v >=10) && (v <=20)) return padej3;
   else
     switch (v % 10){
       case 1:  return padej1;
       case 2:
       case 3:
       case 4:  return padej2;
       default: return padej3;    
     }//switch
}//get_padej


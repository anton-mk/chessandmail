/* Автор: Колосовский Антон Михайлович */

/* Соглашения:
     cl -впереди функции означает, что функция является конструктором класса
*/

function cl_modal_(){
   this.hide_parent =null; //объект скрывающий родительский элемент
   this.modal_ =null;
   this.layer_modal_1 =null;
   this.content_ ="";
   this.use_top =true;
   this.use_transporant =true;

   this.show_ =function(){
        var width_modal =0;
            height_modal =0;
            style_layer_1 ="";
            style_modal ="";
            doc_ =null;
            s ='';
        if (this.use_top) doc_ =top.document; else doc_ =document;

//создаю объект скрывающий родительский элемент
        this.hide_parent=doc_.createElement("div");
        s ="display: block; " +
           "position: absolute; " +
           "margin: auto; " +
           "top: 0; left: 0; " +
           "width: 100%; height: 100%; " +
           "z-index: 9999; " +
           "border: 0; " +
           "background-color: #000; ";
        if (this.use_transporant)
          s +="opacity: 0.2; " + //прозрачность для всех кроме IE
              "filter:alpha(opacity=20); "; //прозрачность для IE
          else
          s +="opacity: 0; " + //прозрачность для всех кроме IE
              "filter:alpha(opacity=0); "; //прозрачность для IE
        this.hide_parent.style.cssText=s;
        doc_.body.appendChild(this.hide_parent);
//создаю модальное окно
        for (var i=1; i<3; i++){
          this.modal_ =doc_.createElement("div");
          style_modal ="display: block; " +
                       "-moz-box-sizing: border-box; " +
                       "box-sizing: border-box; " +
                       "border: 1px solid black; " +
                       "background-color: white; " +
                       "color: black; " +
                       "padding: 10px; " +
//закругление снизу
                       "border-bottom-left-radius: 4px; " +
                       "border-bottom-right-radius: 4px; " +
                       "-moz-border-radius-bottomleft: 4px; " +
                       "-moz-border-radius-bottomright: 4px; " +
                       "-webkit-border-bottom-left-radius: 4px; " +
                       "-webkit-border-bottom-right-radius: 4px; " +
//тень
                       "box-shadow: 10px 10px 64px #000; " +
                       "-webkit-box-shadow: 10px 10px 64px #000; " +
                       "-moz-box-shadow: #000 10px 10px 64px;  " +
//позиция на экране
                       "position:relative; ";
          if (i ==2){
            style_modal +="left: -50%; " +
                          "top: -50%;";
          }
          this.modal_.style.cssText =style_modal;
          this.modal_.innerHTML =this.content_;
//выввожу окно
          this.layer_modal_1 =doc_.createElement("div");
          style_layer_1 ="display: block; " +
                         "position: fixed; " +
                         "z-index: 10000; " +
                         "left: 50%; " +
                         "top: 50%; ";
          if (i==1){
            style_layer_1 +="visibility:hidden;";
          }else{
            style_layer_1 +="width: " +width_modal + "px; " +
                            "height: " + height_modal + "px; ";
          }
          this.layer_modal_1.style.cssText =style_layer_1;
          doc_.body.appendChild(this.layer_modal_1);
          this.layer_modal_1.appendChild(this.modal_);
          if (i==1){
            width_modal  =this.modal_.offsetWidth;
            height_modal =this.modal_.offsetHeight;
            doc_.body.removeChild(this.layer_modal_1);
            this.modal_ =null;
            this.layer_modal_1 =null;
          }
        }//for
   }//show

   this.hide_ =function(){
        var doc_ =null;
        if (this.use_top) doc_ =top.document; else doc_ =document;

       doc_.body.removeChild(this.layer_modal_1);
       this.modal_ =null;
       this.layer_modal_1 =null;

       doc_.body.removeChild(this.hide_parent);
       this.hide_parent =null;
   }//hide_

}//cl_modal_


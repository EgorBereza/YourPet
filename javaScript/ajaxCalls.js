 //otan anoigei gia proti fora i selida sthn arxikh (otan den uparxei GET parameter p)
 //emfanizei sthn arxiki n=limit karteles pou exoun dimiourgithi pio prosfata
$(document).ready(function() {
  if(getParameterFromUrl('p')== null){
      var limit=18;  //poses karteles na emfanizontai sthn arxiki
      doHttpRequest("POST",null,'php/searchRecentCards.php',searchCards,"limit="+limit);
      var link=window.location.href.split('?')[0];
      var stateObj = {          //state object sto opio apothikeuontai kataliles plirofories gia na dimiourgite swsth selida apo to istoriko
          pageContent: "",
          page: "",
          currentPage:"",
          jasonObject: ""
          };
          stateObj.page="home";
          stateObj.pageContent=document.querySelector("#mymain").innerHTML;   
      window.history.pushState(stateObj, 'home', link+'?p=home');
  }
});


  //methodos gia diaxirisi error 404 pou dimiourgeite sthn emfanisi kartelwn an kapoios xristis diagrapsei thn kartela
  //oso allos xristis vlepei ta apotelesmata anazitisis pou egine prin thn diagrafi authns ths kartelas
  window.addEventListener('error', function(e) {
    if(e.target.id && e.target.id.substr(0,13) == 'resultCardImg'){
      clearNode(e.target.parentNode.parentNode);
      console.clear();
    }
     },true);
  


//methodos gia ajax calls (type=GET/POST)
function doHttpRequest(type,e,link,callbackFunction,sendParams){
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.open(type,link,true);
    xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xmlhttp.responseType = 'text';
    
    xmlhttp.onreadystatechange = function() { 
      if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
        callbackFunction(e,this);
      }
    }
    if(sendParams!=null){
      xmlhttp.send(sendParams);
     }
     else{
      xmlhttp.send();
     }
    
}

//JS Object gia pagitation sthn emfanisi apotelesmatwn ths anazitisis
var paginator = {
  jasonObject: null,
  totalCards:0,
  currentPage:1,                   //i epilegmenh selida (auth pou emfanizetai sthn othonh)
  pageSize:6,                     //NA ALLAXW SE 9   //megethos kathe selidas(poses karteles emfanizontai)
  pagesToShow:5,                  //poses selides tha fenonte panw sto pagitation UI
  totalPages:0,                   //sunolo twn selidwn pou vgainoun apo to jasonObject me vash pageSize kathe selidas
  startPage:1,                    //proti selida pou tha emfanizetai sto pagitation UI (oxi proti genika )
  endPage:0,                      //teleutaia selida pou tha emfanizetai sto pagitation UI  (oxi teleutaia genika )
  startCardIndex:0,                //card id ths protis kartelas sthn currentPage
  endCardIndex :0,                 //card id ths teleutaias kartelas sthn currentPage
  beforeCurrent:0,                  //selides prin thn epilegmenh(current)
  afterCurrent:0,                   //selides meta thn epilegmenh(current)

  setUp : function(){
    this.totalCards=this.jasonObject.length;
    this.totalPages = Math.ceil(this.totalCards/this.pageSize);

  },
  //methodos upologizei poies selida tha emfanizontai sto pagitation UI kai poies karteles tha emfanizontai sthn epilegmenh selida
  paginate : function() {
   if (this.currentPage < 1) {
      this.currentPage = 1;
   } 
   else if (this.currentPage > this.totalPages) {
    this.currentPage = this.totalPages;
   }
    this.endPage = this.totalPages;
   if(this.totalPages>this.pagesToShow){
      this.beforeCurrent = Math.floor(this.pagesToShow / 2);   
      this.afterCurrent = Math.ceil(this.pagesToShow / 2) - 1; 
      //an i epilegmenh einai sthn arxh 
      if (this.currentPage <=  this.beforeCurrent) {
        this.startPage = 1;
        this.endPage=this.pagesToShow;
      }
      //an i epilegmenh einai sto telos
      else if(this.currentPage+this.afterCurrent>=this.totalPages){
        this.startPage=this.totalPages-this.pagesToShow+1;
        this.endPage = this.totalPages;
      }
      //an i epilegmenh einai kapou sthn mesh 
      else{
        this.startPage=this.currentPage-this.beforeCurrent;
        this.endPage=this.currentPage+this.afterCurrent;
      }
   }
    this.startCardIndex = (this.currentPage - 1) * this.pageSize;
    this.endCardIndex = Math.min(this.startCardIndex + this.pageSize - 1, this.totalCards - 1);
  }
};



//
//diaforoi event listeners me ajax calls
//


//register
document.querySelector("#mymain").addEventListener('click',function(e){
  if(e.target!=null &&  e.target.id =='register-submit' && e.target.classList.contains("not-Pressed")){
   e.target.classList.remove("not-Pressed");                    // prostasia apo diplo click sto koumbi (apenergopoish tou)
   e.preventDefault();
   var name=document.querySelector("#inputName").value;
   var surname=document.querySelector("#inputSurname").value;
   var password=document.querySelector("#inputPassword").value;
   var email=document.querySelector("#inputEmail").value;
   var cPassword=document.querySelector("#confirmPassword").value;
   var params = [password,name,surname,email,cPassword];
    if(checkForm('#alert-placeholderRegister',params) && confirmPassword(password,cPassword,'#alert-placeholderRegister')){
      doHttpRequest("POST",e,'php/registerUser.php',registerNewUser,"name="+name+"&surname="+surname+"&password="+password+"&email="+email+"&cPassword="+cPassword);
      document.querySelector("#spinner-alert").classList.remove("notDisplayed");
      window.scrollTo(0,0);
    }
    else{
       e.target.classList.add("not-Pressed");
    }
   }
 });


 //create
document.querySelector("#mymain").addEventListener('click',function(e){
if(e.target!=null && e.target.id =='btn-create' && e.target.classList.contains("not-Pressed")){
    e.target.classList.remove("not-Pressed"); // prostasia apo diplo click sto koumbi (apenergopoish tou)
    e.preventDefault();

    var img1=document.querySelector("#img1").src;
    var img2=document.querySelector("#img2").src;
    var img3=document.querySelector("#img3").src;
    
 if(!img1.includes("photos/Spinner.gif") && !img2.includes("photos/Spinner.gif") && !img3.includes("photos/Spinner.gif")){
  if(checkIfImages(img1,img2,img3)){
    var name=document.querySelector("#name").value;
    var surname=document.querySelector("#surname").value;
    var phone=document.querySelector("#phone").value;
    var email=document.querySelector("#email").value;

    var city=document.querySelector("#city").value;
    var race=document.querySelector("#race").value;

    var params = [city,race,name,surname,phone,email];
    if(checkIfNotEmpty(params)){
      var adopt=+document.querySelector("#adopt").checked;
      var found=+document.querySelector("#found").checked;
      var searched=+document.querySelector("#searched").checked; 
      if((adopt+found+searched)==1){
        var type=document.querySelector("#type").value;
        var size=document.querySelector("#size").value;
        var age=document.querySelector("#age").value;
        var gender=document.querySelector("#gender").value;
        var comment=document.querySelector("#comment").value;


          var card_id="";
          //gia epexergasia palias otan uparxei card_id stelnete kai auto sto createCard.php
          if(history.state && history.state.paramCardId){
            card_id="&card_id="+history.state.paramCardId.substr(3)
          }
        //  else if(getParameterFromUrl('c')) card_id="&card_id="+getParameterFromUrl('c');         

            doHttpRequest("POST",e,'php/createCard.php',createCard,"name="+name+"&surname="+surname+"&phone="+phone+"&email="+email+"&city="+city+"&race="+race+"&adopt="
            +adopt+"&found="+found+"&searched="+searched+"&type="+type+"&size="+size+"&age="+age+"&gender="+gender+"&comment="+comment+"&img1="
            +img1+"&img2="+img2+"&img3="+img3+card_id);
            document.querySelector("#spinner-alert").classList.remove("notDisplayed");
            window.scrollTo(0,0);

      }
      else{
        showAlert("#alert-placeholderCreate","alert-danger","Παρακαλώ επιλέξτε ένα από:Υιοθεσία,Βρέθηκε,Αναζητείται");
        window.scrollTo(0,0);
        e.target.classList.add("not-Pressed");
      } 
   }
   else{
    showAlert("#alert-placeholderCreate","alert-danger","Παρακαλώ συμπληρώστε όλα τα απαραίτητα πεδία");
    window.scrollTo(0,0);
    e.target.classList.add("not-Pressed");
   }
 }
 else{
   showAlert("#alert-placeholderCreate","alert-danger","Παρακαλώ επιλέξτε 3 φωτογραφίες τύπου jpg/jpeg ή png");
   window.scrollTo(0,0);
   e.target.classList.add("not-Pressed");
  }
 }
else{
    showAlert("#alert-placeholderCreate","alert-danger","Παρακαλώ επιλέξτε 3 φωτογραφίες");
    window.scrollTo(0,0);
    e.target.classList.add("not-Pressed");
}
    
  }

});


 //login
 document.querySelector("#mymain").addEventListener('click',function(e){
   if(e.target!=null && e.target.id == 'buttonLogin'){
     e.preventDefault();
     var email=document.querySelector("#inputEmail").value;
     var password=document.querySelector("#inputPassword").value;
     var params = [password,email];
     if(checkForm('#alert-placeholderLogin',params) ){
      doHttpRequest("POST",e,'php/doLogin.php',loginUser,"email="+email+"&password="+password);
     }
  }

});

//validate email
 document.querySelector("#mymain").addEventListener('click',function(e){
    if(e.target!=null && e.target.id =='alert-confirm'){
      doHttpRequest("POST",e,'php/validateEmail.php',validateUser,"t="+getParameterFromUrl('t')+"&e="+getParameterFromUrl('e'));
    }
   });

//reset
 document.querySelector("#mymain").addEventListener('click',function(e){
    if(e.target!=null && e.target.id =='btn-reset'){
      e.preventDefault();
      var email=document.querySelector("#inputEmail").value;
      var newPassword=document.querySelector("#inputNewpassword").value;
      var params = [newPassword,email];
      if(checkForm('#alert-placeholderReset',params) ){
        doHttpRequest("POST",e,'php/resetUser.php',resetUser,"email="+email+"&newPassword="+newPassword);
      }
     
    }
  });

  //search
 document.querySelector("#mymain").addEventListener('click',function(e){
    if(e.target!=null && e.target.id == 'buttonSearch'){
      clearNode(document.querySelector("#alert-placeholderHome"));  
      e.preventDefault();
      var city=document.querySelector("#city").value;
      var race=document.querySelector("#race").value;

      var adopt=+document.querySelector("#adopt").checked;
      var found=+document.querySelector("#found").checked;
      var searched=+document.querySelector("#searched").checked;
      if((adopt+found+searched)!=0){
        var type=document.querySelector("#type").value;
        var size=document.querySelector("#size").value;
        var age=document.querySelector("#age").value;
        var gender=document.querySelector("#gender").value;
        
        doHttpRequest("POST",e,'php/searchCards.php',searchCards,"city="+city+"&race="+race+"&adopt="+adopt+"&found="
                      +found+"&searched="+searched+"&type="+type+"&size="+size+"&age="+age+"&gender="+gender);
      }
      else{
         showAlert("#alert-placeholderHome","alert-danger","Παρακαλώ επιλέξτε τουλάχιστον ένα από:Υιοθεσία,Βρέθηκε,Αναζητείται");
         clearNode(document.querySelector("#result-page"));
         clearNode(document.querySelector("#cardsPaginationUl"));
      }
 
   }
 
 });

 //delete button in card (shows alert for confirmation)
 document.querySelector("#mymain").addEventListener('click',function(e){
  if(e.target!=null && e.target.id == 'btn-delete-card'){
     showAlert('#alert-placeholderCard',"alert-info","Σίγουρα θέλετε να διαγράψετε αυτήν την καρτέλα;Για επιβεβαίωση πατήστε:");
     document.querySelector(".alert").insertAdjacentHTML('beforeend','<a class="text-danger" id="alert-delete-card" href="#" >εδώ</a>');
  }
});

//delete card (from alert)
document.querySelector("#mymain").addEventListener('click',function(e){
  if(e.target!=null && e.target.id == 'alert-delete-card'){
    e.preventDefault();
    var card_id;
    if(history.state!=null && history.state.card!=null) card_id=history.state.card;  //an uparxei sto istoriko pernei apo ekei to card_id
    else card_id = getParameterFromUrl('c');                                          //alliws to vriskei apo to URL
    if(card_id){
        doHttpRequest("POST",e,'php/deleteCard.php',deleteCard,"card_id="+card_id);
    }
  }

});

//pagitation
//event listener pou kanei pagitation
//sthn ousia allazei to paginator.currentPage anloga me ti koumbi patisei o xristis kai kallei thn methodo paginator.paginate() pou upologizei ola ta alla
//meta kalei methdo showPage pou emfanizei to apotelesma tou upologismou ths paginator.paginate() 
document.querySelector("#mymain").addEventListener('click',function(e){
  if(e.target!=null && e.target.classList.contains("page-btn")){
      e.preventDefault();
      var top=false;
       if(e.target.classList.contains("btn-top")){
        scroll(0,0);
        top=true;
       }
       else if(e.target.classList.contains("btn-next")){
        paginator.currentPage++;
        paginator.paginate();
       }
       else if(e.target.classList.contains("btn-prev")){
        paginator.currentPage--;
        paginator.paginate();
       }
       else if(e.target.classList.contains("btn-first")){
        paginator.currentPage=1;
        paginator.paginate();

       }
       else if(e.target.classList.contains("btn-last")){
        paginator.currentPage=paginator.totalPages;
        paginator.paginate();
       }
       else{
        paginator.currentPage=parseInt(e.target.text);
        paginator.paginate();
       } 
       showPagination();
       showPage(paginator,document.querySelector("#result-page"));
       if(!top)addHistoryEntry();
  }
});


 //view card
 document.querySelector("#mymain").addEventListener('click',function(e){
  if(e.target!=null &&  e.target.classList.contains("btn-view")){
     var cardId=(e.target.parentNode.parentNode.parentNode.parentNode.id.substr(4));
     doHttpRequest("GET",e,'components/card.php?p=card&c='+cardId,showCardPage,null);
  }
});

//logout
 document.querySelector("#navbarHeader").addEventListener('click',function(e){
  if(e.target!=null && e.target.id == 'nav-logout'){
    doHttpRequest("POST",e,'php/logout.php',logoutUser,null);
  }
   
});


//apostoli neou token gia epivevewsh email
document.querySelector("#mymain").addEventListener('click',function(e){
  if(e.target!=null &&  e.target.id=='NewconfirmEmail'){
    e.preventDefault();
    var email=document.querySelector("#inputEmail").value;
    if(email!=""){
        doHttpRequest("POST",e,'php/sendNewToken.php',sendNewToken,"email="+email);
        document.querySelector("#spinner-alert").classList.remove("notDisplayed");
        window.scrollTo(0,0);
    }
    else{
      //elegxos an klithike apo login i register selida(gia na emfanisei to alert se swsto div)
      if(document.querySelector("#alert-placeholderLogin")!=null) var alertId="alert-placeholderLogin";
      else var alertId="alert-placeholderRegister2";
      showAlert("#"+alertId+"","alert-danger","Για την αποστολή νέου email επιβεβαίωσης συμπληρώστε στην κάτω φόρμα το email σας και πατήστε:");
      document.querySelector("#"+alertId+"").firstChild.insertAdjacentHTML('beforeend','<a class="text-danger" id="NewconfirmEmail" href="#" >εδώ</a>');
    }
  }
});

//event listener gia koumbi andigrafi sundesmou
document.querySelector("#mymain").addEventListener('click',function(e){
  if(e.target!=null &&  e.target.id=='btn-copy-card'){
    e.preventDefault();
    var temp = document.createElement('input'),
    text = window.location.href;
    document.body.appendChild(temp);
    temp.value = text;
    temp.select();
    document.execCommand('copy');
    document.body.removeChild(temp);
    showAlert('#alert-placeholderCard',"alert-success","Ο σύνδεσμος της καρτέλας αντιγράφτηκε");
  }
});



//
//Katw call back functions gia ta panw ajax calls
//

//kallounte afou epistrepsei apotelesma to php arxeio pou klithike sto ajax call
//sthn ousia emfanizoun apotelesma/allazoun to UI analoga thn apandish tou backend se ajax call
//kapoies tropopoioun kai to istoriko gia na douleuoun back/forward/reload sthn selida



//delete card (from alert) call back function 
function deleteCard(e,xmlhttp){
  if(xmlhttp.responseText.trim() =="success"){
   var link=window.location.href.split('?')[0];

   var stateObj = {  
     pageContent: "",
     page: "home"
     };

    window.history.replaceState(stateObj,"home",link+'?p=home'); 
    document.querySelector('#nav-home').click();
  }
  else{
    showAlert('#alert-placeholderCard',"alert-danger",xmlhttp.responseText.trim());
  }
}

//search call back function
function searchCards(e,xmlhttp){
    var jasonObject=JSON.parse(xmlhttp.responseText);    //apotelesma ths anazitisis sthn vash mesa se ena JASON
    if(jasonObject.error){ 
      showAlert("#alert-placeholderHome","alert-danger",jasonObject.error);
    }
    else{
      if(jasonObject.length>0){    //an vrethikan karteles   emfanisi selidas kai pagitation
        paginator.jasonObject = jasonObject;
        paginator.setUp();
        paginator.currentPage=1;
        paginator.paginate();
        showPage(paginator,document.querySelector("#result-page"));
        showPagination();
      }
     else{                        //an den vrethikan karteles  emfanisi alert
       clearNode(document.querySelector("#result-page"));
       clearNode(document.querySelector("#cardsPaginationUl"));
       showAlert("#alert-placeholderHome","alert-danger","Δεν βρέθηκε κανένα αποτέλεσμα");
     }
     addHistoryEntry();  //prosthiki sto istoriko to apotelesma ths anazitisis
  }
}


//view card call back function 
function showCardPage(e,xmlhttp){
    clearNode(document.querySelector("#mymain"));
    document.querySelector("#mymain").insertAdjacentHTML('beforeend',xmlhttp.responseText); 
    //dimiourgia tou state object gia card gia apothikeush sto istoriko (exei kapoies diafores gia auto den kalw thn addHistoryEntry())
    var link=window.location.href.split('?')[0];
    var stateObj = {  
      pageContent: "",
      page: "",
      card:""
      };
    stateObj.page="card";
    stateObj.card=e.target.parentNode.parentNode.parentNode.parentNode.id.substr(4);   //card id ths kartelas
    stateObj.pageContent=document.querySelector("#mymain").innerHTML;  
    window.history.pushState(stateObj, ''+"card", link+'?p=card&c='+stateObj.card); 
    scroll(0,0);
}


//register call back function
  function registerNewUser(e,xmlhttp){
    document.querySelector("#spinner-alert").classList.add("notDisplayed");
    if(xmlhttp.responseText.trim() == "success"){
       var type ="alert-success";
       var msg ="Επιτυχής εγγραφή,παρακαλώ επιβεβαιώστε την διεύθυνση email σας για να μπορείτε να συνδεθείτε.Αν δεν βρίσκετε το email επιβεβαίωσης ελέγξτε τα ανεπιθύμητα μηνύματα στο email σας αλλιώς επικοινωνήστε μαζί μας";
    }
    else{
       var type="alert-danger";
       var msg=xmlhttp.responseText.trim();
       e.target.classList.add("not-Pressed");                    //ama uparxei kapoio lathos enaevergopoish tou koumbiou gia nea prospathia eggrafis
    }
    showAlert('#alert-placeholderRegister',type,msg);
    if(type == "alert-success"){
      showAlert('#alert-placeholderRegister2',"alert-info","Για την αποστολή νέου email επιβεβαίωσης συμπληρώστε στην κάτω φόρμα το email σας και πατήστε:");
      document.querySelector('#alert-placeholderRegister2').firstChild.insertAdjacentHTML('beforeend','<a class="text-danger" id="NewconfirmEmail" href="#" >εδώ</a>');
    }
   }

  // reset password call back function
   function resetUser(e,xmlhttp){
    if(xmlhttp.responseText.trim() == "success"){
      var type ="alert-success";
      var msg ="Παρακαλώ επιβεβαιώστε την διεύθυνση email σας για να αλλάξει ο κωδικός";
   }
   else{
      var type="alert-danger";
      var msg=xmlhttp.responseText.trim();
   }
   showAlert('#alert-placeholderReset',type,msg);
   }

  // validate email call back function
   function  validateUser(e,xmlhttp){
     if(xmlhttp.responseText.trim() == "success"){
       var type ="alert-success";
       var msg ="Η διεύθυνση email σας έχει επιβεβαιωθεί μπορείτε να συνδεθείτε";
     }
    else{
       var type="alert-danger";
       var msg=xmlhttp.responseText.trim();
     }
      window.history.replaceState(null, null, window.location.href.split('?')[0]+'?p=login');   //vgazw tous get parametrous afou den xriazontai pleon
     
      showAlert('#alert-placeholderLogin',type,msg);
   }
   
   // login call back function
   function loginUser(e,xmlhttp){
    var response = xmlhttp.responseText.trim();
    //allages sto nav otan sundethei o xristis 
    if(response.substring(0,7) == "success"){
      document.querySelector('#nav-login').classList.add('notDisplayed');
      document.querySelector('#nav-register').classList.add('notDisplayed');
      document.querySelector('#nav-logout').classList.remove('notDisplayed');
      document.querySelector('#nav-myCards').classList.remove('notDisplayed');
      document.querySelector('#nav-home').click();
      document.querySelector('#nav-msg').textContent=response.substring(8);
    }
    else if(response=='confirm'){
      showAlert('#alert-placeholderLogin',"alert-info","Επιβεβαιώστε την διεύθυνση email σας για να μπορείτε να συνδεθείτε.Για την αποστολή νέου email επιβεβαίωσης συμπληρώστε στην κάτω φόρμα το email σας και πατήστε:");
      document.querySelector(".alert").insertAdjacentHTML('beforeend','<a class="text-danger" id="NewconfirmEmail" href="#" >εδώ</a>');
    }
    else{
      var type="alert-danger";
      var msg=response;
      showAlert('#alert-placeholderLogin',type,msg);
      }
   }
   
   // logout call back function
   function logoutUser(e,xmlhttp){
     //allages sto nav otan aposundethei o xristis 
    if(xmlhttp.responseText.trim() == "success"){
      document.querySelector('#nav-login').classList.remove('notDisplayed');
      document.querySelector('#nav-register').classList.remove('notDisplayed');
      document.querySelector('#nav-logout').classList.add('notDisplayed');
      document.querySelector('#nav-myCards').classList.add('notDisplayed');
      document.querySelector("#nav-msg").innerText="";
    //  console.log('succ');
      document.querySelector('#nav-home').click();
    }
    else{
   //   console.log("fail");
      document.querySelector('#nav-home').click();
      var type="alert-danger";
      var msg=xmlhttp.responseText.trim();
      showAlert('#alert-placeholderHome',type,msg);

    }
    
   }



//apostoli neou token gia epivevewsh email call back function
function sendNewToken(e,xmlhttp){
  document.querySelector("#spinner-alert").classList.add("notDisplayed");
  //elegxos an klithike apo login i register selida(gia na emfanisei to alert se swsto div)
  if(document.querySelector("#alert-placeholderLogin")!=null) var alertId="alert-placeholderLogin";
  else var alertId="alert-placeholderRegister";

  if(xmlhttp.responseText.trim() == "success"){
    showAlert("#"+alertId+"","alert-success","Νέο email επιβεβαίωσης στάλθηκε.Αν δεν το βρίσκετε ελέγξτε τα ανεπιθύμητα μηνύματα στο email σας αλλιώς επικοινωνήστε μαζί μας");
  }
  else{
    if(alertId=="alert-placeholderRegister") alertId="alert-placeholderRegister2";
    showAlert("#"+alertId+"","alert-danger","Για την αποστολή νέου email επιβεβαίωσης συμπληρώστε στην κάτω φόρμα το email σας και πατήστε:");
    document.querySelector("#"+alertId+"").firstChild.insertAdjacentHTML('beforeend','<a class="text-danger" id="NewconfirmEmail" href="#" >εδώ</a>');
 
    
  }
}
// showAlert('#alert-placeholderCreate',"alert-success","Οι αλλαγές σας αποθηκεύτηκαν");
  // create call back function
   function createCard(e,xmlhttp){
    document.querySelector("#spinner-alert").classList.add("notDisplayed");
    if(xmlhttp.responseText.trim() == "success"){
      showAlert('#alert-placeholderCreate',"alert-success","Η αγγελία σας δημιουργήθηκε");
      
    }
    else if(xmlhttp.responseText.trim() == "success update"){
      history.back();
      showAlert('#alert-placeholderCard',"alert-success","Οι αλλαγές σας αποθηκεύτηκαν");
    }
   else{
      var type="alert-danger";
      var msg=xmlhttp.responseText.trim();
      showAlert('#alert-placeholderCreate',type,msg);
    }
    e.target.classList.add("not-Pressed");   //enerogopoish pali tou koumbiou 
   }
  

   //
   //Katw diafores voithitikes functions
   //
   
   //methodos pou dimiourgei kai emfanizei thn HTML gia to pagitation analoga tis plirofories sto pagitator Object
   function showPagination(){
    var ul=document.querySelector("#cardsPaginationUl");
    clearNode(ul); 
    var lis='<li class="col-sm-1 col-4 page-item text-center"><a class="page-link page-btn btn-top px-2" href="#"><span class=" btn-top page-btn fas fa-angle-up"></span></a></li>'; 
    if(paginator.totalPages>1){
      lis+='<li class="col-sm-1 col-4  page-item text-center"><a class="page-link page-btn btn-first px-3" href="#"><span  class=" btn-first page-btn fas fa-angle-double-left"></span></a></li>'+
            '<li class="col-sm-1 col-4  page-item text-center"><a class="page-link page-btn btn-prev px-2" href="#"><span  class="btn-prev page-btn fas fa-angle-left"></span></a></li>';
     var colSize=calculateColumnSize(paginator.startPage,paginator.endPage);    
     for(i=paginator.startPage;i<paginator.endPage+1;i++){
      lis+='<li id="pageLi'+i+'" class="col-sm-1 col-'+colSize+' page-item text-center"><a class="page-link page-btn " href="#">'+i+'</a></li>';
     }
     lis+='<li class="col-sm-1 col-6 page-item text-center"><a class="page-link page-btn btn-next px-2" href="#"><span  class="btn-next page-btn fas fa-angle-right"></span></a></li>'+
          '<li class="col-sm-1 col-6  page-item text-center"><a class="page-link page-btn btn-last px-3" href="#"><span  class=" btn-last page-btn fas fa-angle-double-right"></span></a></li>';
     ul.insertAdjacentHTML('beforeend',lis);
     var currentLi=document.querySelector("#pageLi"+paginator.currentPage);
     if(currentLi!=null){
       currentLi.classList.add("active");
     }
    }
    else{
     ul.insertAdjacentHTML('beforeend',lis);
    }
}

function calculateColumnSize(startPage,endPage){
        if(endPage-startPage+1>=4) return 2;
        else if(endPage-startPage+1==3) return 3;
        else return 4;
}

//methodos pou emfanizei thn selida me apotelesma ths anazitisis (tis karteles sthn arxiki)
function showPage(paginator,node){
  if(node!=null){
   while (node.hasChildNodes()) {
     node.removeChild(node.firstChild);
   }
   for (var i = paginator.startCardIndex; i < paginator.endCardIndex+1; i++) {
   showCard(node,paginator.jasonObject[i].card_id,paginator.jasonObject[i].imageType,paginator.jasonObject[i].purpose,paginator.jasonObject[i].city,paginator.jasonObject[i].type);
   }
  }
}

//methdos pou dimiourgei thn HTML gia kathe sugkekrimenh kartela pou emfanizete mesw showPage() panw
function showCard(node,id,imageType,purpose,city,type){
   var animalIcon="fas fa-dog";
   if(type=="Γάτα") animalIcon="fas fa-cat";   //epilogi eikonas (skilos/gata)
   var cardDiv='<div class="col-md-4 mb-4"> '+ 
               '<div class="card mb-4 shadow-sm backcol-grey h-100" id="card'+id+'"> '+ 
               '<img  alt="Card image cap" id="resultCardImg'+id+'" class="card-img-top img-fluid " src="usersImages/'+id+'/1.'+imageType+'"" /> '+
               '<div class="card-body"> '+
               '<p class="card-text text-bold"><span class="'+animalIcon+'"></span>: '+purpose+'</p>'+
               '<p class="card-text text-bold"><span class="fas fa-home"></span>: '+city+'</p> '+
               '<div class="d-flex justify-content-center">'+ 
               '<div class="btn-group btn-block ">'+ 
               ' <button type="button" class="btn-view btn btn-bg btn-outline-secondary ">Προβολή</button>'+ 
               '</div> </div> </div> </div> </div>';
   node.insertAdjacentHTML('beforeend',cardDiv);
}


  
  //methodos gia prosthiki sto istoriko swsti selida meta thn anazitisi h/kai pagitation sto home page
 function addHistoryEntry(){
  var link=window.location.href.split('?')[0];
  var stateObj = {          //state object sto opio apothikeuontai kataliles plirofories gia na dimiourgite swsth selida apo to istoriko
      pageContent: "",
      page: "",
      currentPage:"",
      jasonObject: ""
      };
      stateObj.page="home";
      stateObj.pageContent=document.querySelector("#mymain").innerHTML;       //
      stateObj.jasonObject=JSON.stringify(paginator.jasonObject);      //state object borei na apothikeusei mono strings
      stateObj.currentPage=paginator.currentPage;

      window.history.pushState(stateObj, ''+page, link+'?p=home');     //dimiourgia neas eggrafhs sto istoriko
 }




   //methodos gia na parw timh enos parametrou apo to trexon URL
   function getParameterFromUrl(parameterName){
    var url = new URL(window.location.href);
    return url.searchParams.get(parameterName);
   }

   //elegxei an ta 3 images einai JPG/PNG an oxi epistrefei false
   function checkIfImages(img1,img2,img3){
     if(isPngOrJpg(img1) && isPngOrJpg(img2) && isPngOrJpg(img3)) return true;
     else return false;
   }
  
    //elegxei an 1 image einai JPG/PNG
    //proti grammh einai gia kainouries ikones pou anevazei o xristis 
    //deuterh grammh einai gia idi anevasmenes ikones pou uparxoun sto server (otan kanei epexergasia kartelas)
   function isPngOrJpg(img){
      if( img.substr(0,21)=="data:image/png;base64" || img.substr(0,22)=="data:image/jpeg;base64") return true;
      else if(img.substr(0,47) == 'https://nireas.it.teithe.gr/yourpet/usersImages') return true;   //PREPEI NA ALLAXEI SE KANONIKO SERVER OTAN FUGW APO LOCALHOST
      else return false;
   }
 

   //katharizei ena node 
    function clearNode(node) {
      while (node.hasChildNodes()) {
      node.removeChild(node.firstChild);
      }
    }

    //dimiourgei ena alert kai to vazei mesa se ena node (alert placeholder div pou exw se diafora components)
    function createAlert(type,msg,node){
        var alertDiv='<div class="alert '+type+' alert-dismissible fade show myalert">'+
                     '<button type="button" class="close" data-dismiss="alert">&times;</button>'+
                     ''+msg+'</div>'; 
        node.insertAdjacentHTML('beforeend',alertDiv);
      
    }

    function checkPasswordLenght(password){
           if(password.length >= 8){
             return true;
           }
           else{
             return false;
           }
    }

    function confirmPassword(password,cPassword,id){
       if(password===cPassword){
        return true;
       } 
       else{
        showAlert(id,"alert-danger","Ο κωδικός είναι διαφορετικός απο την επιβεβαίωση κωδικού");
        return false;
       }
    }


    function checkIfNotEmpty(params){
      for (var i = 0; i < params.length; i++) {
         if(params[i] === "") return false;
      }
      return true;
    }
    
    //katharizei ena node kai vazei mesa ena alert
    function showAlert(id,type,msg){
      clearNode(document.querySelector(id));
      createAlert(type,msg,document.querySelector(id));
    }

    function checkForm(id,params){
      if(checkIfNotEmpty(params)){
        if(checkPasswordLenght(params[0])){
           return true;
        }
        else{
          showAlert(id,"alert-danger","Ο κωδικός πρέπει να αποτελείται τουλάχιστον από 8 χαρακτήρες");
          return false;
        }
       }
       else{
         showAlert(id,"alert-danger","Παρακαλώ συμπληρώστε όλα τα πεδία");
         return false;
       }
    }

    //vriskei thn selida(p GET parameter) kai tous allous parametrous apo to trexon URL
    function findPage(){
      var returnInfo = ["home",null];
      if (window.location.href.split('?').length>1){
          var params=window.location.href.split('?');
          params=params[1].split('&');
          var p="";
          for(var i=0;i<params.length;i++){
              if(params[i].substring(0,1)=="p"){
                  p=params[i].substring(2);
                  break; 
              }   
          }
          returnInfo[0]=p;
          returnInfo[1]=params;
       if(p =="home" || p =="login" || p =="register" || p =="card" || p =="cards" ||  p =="create" ){
           return returnInfo;  
       }
       else{
           returnInfo = ["home",null];
       }
      }
      return  returnInfo;
   }



//meiwnei tis diastaseis ths eikonas gia na meiwthei to megethos ths kai na ginete pio grigroa to anevasma sto server
function downscaleImage(dataUrl, newWidth, imageType, imageArguments,callback) {
    var image, width, height, newHeight, canvas, ctx, newDataUrl;

    // default values
    imageType = imageType || "image/jpeg";
    imageArguments = imageArguments || 0.7;

    // Create a temporary image 
    image = new Image();

    image.onload = function() {
    width = image.width;

    height = image.height;
    newHeight = Math.floor(height / width * newWidth);
    
    // Create a temporary canvas to draw the downscaled image on.
    canvas = document.createElement("canvas");
    canvas.width = newWidth;
    canvas.height = newHeight;
 
    // Draw the downscaled image on the canvas and return the new data URL.
    ctx = canvas.getContext("2d");


    ctx.drawImage(image, 0, 0, newWidth, newHeight);
    newDataUrl = canvas.toDataURL(imageType, imageArguments);
    callback(newDataUrl);
    
  };

  image.src = dataUrl;
}

//vriskei to EXIF orientation ths eikonas (an einai >1 thelei peristrofi me resetOrientation)
function getOrientation(file, callback) {
  var reader = new FileReader();

  reader.onload = function(event) {
    var view = new DataView(event.target.result);

    if (view.getUint16(0, false) != 0xFFD8) return callback(-2);

    var length = view.byteLength,
        offset = 2;

    while (offset < length) {
      var marker = view.getUint16(offset, false);
      offset += 2;

      if (marker == 0xFFE1) {
        if (view.getUint32(offset += 2, false) != 0x45786966) {
          return callback(-1);
        }
        var little = view.getUint16(offset += 6, false) == 0x4949;
        offset += view.getUint32(offset + 4, little);
        var tags = view.getUint16(offset, little);
        offset += 2;

        for (var i = 0; i < tags; i++)
          if (view.getUint16(offset + (i * 12), little) == 0x0112)
            return callback(view.getUint16(offset + (i * 12) + 8, little));
      }
      else if ((marker & 0xFF00) != 0xFF00) break;
      else offset += view.getUint16(offset, false);
    }
    return callback(-1);
  };

  reader.readAsArrayBuffer(file.slice(0, 64 * 1024));
};

//peristrefei thn eikona me vash to EXIF orientation  gia na emfaizete swsta
function resetOrientation(srcBase64, srcOrientation, callback) {
  var img = new Image();    

  img.onload = function() {
    var width = img.width,
        height = img.height,
        canvas = document.createElement('canvas'),
        ctx = canvas.getContext("2d");

    // set proper canvas dimensions before transform & export
    if (4 < srcOrientation && srcOrientation < 9) {
      canvas.width = height;
      canvas.height = width;
    } else {
      canvas.width = width;
      canvas.height = height;
    }
    
    // transform context before drawing image
    switch (srcOrientation) {
      case 2: ctx.transform(-1, 0, 0, 1, width, 0); break;
      case 3: ctx.transform(-1, 0, 0, -1, width, height); break;
      case 4: ctx.transform(1, 0, 0, -1, 0, height); break;
      case 5: ctx.transform(0, 1, 1, 0, 0, 0); break;
      case 6: ctx.transform(0, 1, -1, 0, height, 0); break;
      case 7: ctx.transform(0, -1, -1, 0, height, width); break;
      case 8: ctx.transform(0, -1, 1, 0, 0, width); break;
      default: break;
    }
    

    // draw image
     ctx.drawImage(img, 0, 0,);
  
    // export base64
    callback(canvas.toDataURL());
  };

  img.src = srcBase64;
};


// methodos gia epilogh fotografiwn 

//event listener gia ta koumbia anevazmatos fotografiwn sto create component
//kanei peristrofi eikonas an xreiazete kai meiwnei tis diastaseis 
//meta emfanizei to preview twn fotografiwn pou epilextikan kai gemizei to src 
//to anevazma ginete me allo event(me patima sto koumbi dimiourgia)
document.querySelector("#mymain").addEventListener('click',function(e){
  if(e.target.classList.contains('btn-uploadPhoto')){
    var filechooser = document.querySelector("#fileChooser");
    var buttonNumber = e.target.id.slice(-1);                      // epistrefei to teleutaio xaraktira (1/2/3)
    filechooser.value="";                                          //gia na borei na litourgisei to filechooser.onchange an o xristis epilexei idia photo 
    filechooser.click();
    var preview = document.querySelector('#img'+buttonNumber);                    //emfanisi tou preview ths fotografias pou epilextike apo ton xristi
    preview.src = 'photos/Spinner.gif';

    filechooser.onchange = function () {
        var reader = new FileReader();
        var file   = document.querySelector('input[type=file]').files[0];                  //to arxeio pou epilextike
      
        getOrientation(file, function(orientation) {                                       //vriskei to EXIF orientation ths eikonas
         reader.addEventListener("load", function () {
         //  console.log("1 original "+reader.result.length);
            if(orientation>1){                                                            //an to EXIF orientation>1 thelei peristrofi
            resetOrientation(reader.result, orientation, function(resetBase64Image) {     //peristrofi ths eikonas gia na emfanizete swsta
              // console.log("2 orientation "+resetBase64Image.length);
             
              downscaleImage(resetBase64Image,640,null,0.7,function(newDataUrl) {                //allgh diastasewn ths eikonas gia na meiwthei to megethos
                preview.src = newDataUrl;
             //   console.log("3 downscale "+preview.src.length);
              });
            });
          }
          else{
              downscaleImage(reader.result,640,null,0.7,function(newDataUrl) {
              preview.src = newDataUrl;
            });
          }
            preview.classList.remove('notDisplayed');    //vgazw notDisplayed klash apo preview node kai prosthetw notDisplayed sto placehoalder pou upirxe
            var placeholder = document.querySelector('#placeholder'+buttonNumber);
            placeholder.classList.add('notDisplayed');
          
        },false);

        if (file) {
            reader.readAsDataURL(file);         
        }
      });
    };
  }
 
 
});




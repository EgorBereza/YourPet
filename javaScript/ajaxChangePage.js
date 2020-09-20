

var page = "";       //metavliti gia thn trexon selida pou vriskete o xristis
var pageEdit=false;  //metavliti gia elegxo an 'create.php' tha klithei gia dimiourgia neas kartelas i epexergasia uparxousas

//call back function gia allagh selidas
function changePage(e,xmlhttp){
    var myCards=false;
    if(page=="cards"){ //gia emfanisi kartelwn pou anoigoun ston sindemeno xristi
        page="home";
        myCards=true; 
    } 
    
    //edw allazei i selida
    clearNode(document.querySelector("#mymain"));
    document.querySelector("#mymain").insertAdjacentHTML('beforeend',xmlhttp.responseText); 
  
    //dieuthinsi selidas xwris parametrous
    var link=window.location.href.split('?')[0];
    
    //dimiourgia stateObject gia to istorioko
    var stateObj = {  
      pageContent: "",
      page: "",
      paramCardId:""
      };
    stateObj.page=page;
    stateObj.pageContent=document.querySelector("#mymain").innerHTML;
    
    //elegxos an uparxei get parametros 'c' sthn selida create (otan ginetai epexergasia kartelas)
    var paramCardId="";
    if(page =="create" && pageEdit==true){
        if(findPage()[1][1] != null){
            var card_id=findPage()[1][1];
            paramCardId='&'+card_id;
            stateObj.paramCardId=paramCardId;
        }
    }
    
    window.history.pushState(stateObj, ''+page, link+'?p='+page+paramCardId); 
 
    
    //ajax call an i epilogi itan na emfanistoun oi karteles tou xristi
    if(myCards){
        doHttpRequest("POST",null,'php/searchUserCards.php',searchCards,null);
    }
    
    //emfanizei sthn arxiki n=limit karteles pou exoun dimiourgithi pio prosfata
    if(page=="home" && myCards==false){
        var limit=18;
        doHttpRequest("POST",null,'php/searchRecentCards.php',searchCards,"limit="+limit);
    }
}


//event listener me ajax call gia epexergasia kartelas (prota ginete authentication an einai sundemenos kai an i kartela einai ontos dikia tou)
document.querySelector("#mymain").addEventListener('click',function(e){
    if(e.target!=null && e.target.id == 'btn-edit-card'){
        e.preventDefault();
        doHttpRequest("POST",null,'php/authenticateUser.php',authenticateUserForEdit,null);
    }
  });
  

 //event listener gia alerts sto register kai login
 document.querySelector("#mymain").addEventListener('click',function(e){
        if(e.target!=null && e.target.id=='alert-reset'){
            page="reset";
            doHttpRequest("GET",e,'components/'+page+'.php?p='+page,changePage,null);
        }
        else if(e.target!=null && e.target.id=='alert-register'){
            page="login";
            doHttpRequest("GET",e,'components/'+page+'.php?p='+page,changePage,null);
        }
      
    });
    

//popstate event
//auto to event diaxirizete thn litourgikotita tou back/forward buttons sthn selida 
//dimiourgei/kallei thn selida analoga tis plirofories pou einai apothikeumenes sto state object sto istoriko 
window.addEventListener("popstate", function (e){
        if(e.state){
            if(!e.state.pageContent){
                //elegxos an tuxon den exei apothikeutei to pageContent   
                history.back();
            }
            else if(e.state.page=="create"){
                //Elegxos an einai sundemenos o xristis i exei aposidethei
                doHttpRequest("POST",e,'php/authenticateUser.php',authenticateUserHistory,null);
               
            }
            else{
                clearNode(document.querySelector("#mymain"));
                page=e.state.page;
                document.querySelector("#mymain").insertAdjacentHTML('beforeend',e.state.pageContent); 
                if(page=="home" && e.state.jasonObject){
                    paginator.jasonObject=JSON.parse(e.state.jasonObject);
                    paginator.setUp();
                    paginator.currentPage=page;
                    paginator.paginate();
                }
            }      
         }
});

//event gia fortosh swstis selidas kuriws anazitisis meta apo page reload
window.addEventListener("load",function (e){
        var currentState = history.state;
        if(currentState){  
            //kalite mono an uparxei sto istoriko !
            clearNode(document.querySelector("#mymain"));
            page=currentState.page;
            document.querySelector("#mymain").insertAdjacentHTML('beforeend',currentState.pageContent); 
            if(currentState.jasonObject) {
                paginator.jasonObject=JSON.parse(currentState.jasonObject);
                paginator.setUp();
                paginator.currentPage=currentState.currentPage;
                paginator.paginate();
                showPage(paginator,document.querySelector("#result-page"));
            }

        }
});

//Elegxos an einai sundemenos o xristis i exei aposidethei
function authenticateUserHistory(e,xmlhttp){
        if(xmlhttp.responseText.trim() == "0"){
            history.back();   //an den einai pleon sindemenos tote kalw thn proigoumeni selida apo istoriko
        }
        else{
            clearNode(document.querySelector("#mymain"));
            page=e.state.page;
            document.querySelector("#mymain").insertAdjacentHTML('beforeend',e.state.pageContent); 
        }
}
   

//event listener gia koumbia panw sto navigation gia allagh selidwn kai allages sto istoriko
document.querySelector("#navbarHeader").addEventListener('click',function(e){
if(e.target!=null && e.target.classList.contains("nav-option")){
    //ekteleite thn proti fora otan to page variable akoma einai keno (to vriskei apo ton get parametro 'p' sto url)
    if(page ==""){
        page=findPage()[0];
    }
    e.preventDefault();
    
        //an einai card prepei episis na apothikeutei kai to card_id sto state object
        //an uparxei state tote allazei me dedomena prin fugei apo selida o xristis
        //gia na epistrefei sthn idia katastasi me back button
        if(page=="card"){

             //dimiourgia tou state object gia apothikeush sto istoriko 
            var link=window.location.href.split('?')[0];
            var stateObj = {  
            pageContent: "",
            page: "",
            card:""
            };
            stateObj.page=page;
            stateObj.pageContent=document.querySelector("#mymain").innerHTML;

            var currentState = history.state;
            if(currentState){ 
                stateObj.card=currentState.card
                window.history.replaceState(stateObj, ''+page, link+'?p='+page+'&c='+stateObj.card);
            }
           else{
                window.history.replaceState(stateObj, ''+page, link+'?p='+page);
            }
        }
 
    //allagh selida me page metavliti analoga to koumbi pou patise o xristis
    switch(e.target.id) {
        case "nav-home":
            page="home";
            break;
        case "nav-login":  
            page="login";
            break;
        case "nav-create":
            page="create";
            break;
        case "nav-myCards":        
            page="cards";  
            break;
        case "nav-register":
            page="register";  
            break;
        default:
            break;
        }
        pageEdit=false;

   if(page == "create" ){  
       doHttpRequest("POST",null,'php/authenticateUser.php',authenticateUser,null);
    }
   else if(page == "cards"){
       doHttpRequest("GET",e,'components/home.php?p=home',changePage,null);
    }
   else{
       doHttpRequest("GET",e,'components/'+page+'.php?p='+page,changePage,null);
    }
 
              
  }
});

 // call back function authentication tou xristi gia thn epexergasia kartelas
function authenticateUserForEdit(e,xmlhttp){
    if(xmlhttp.responseText.trim() == "0"){
        page="login";
        doHttpRequest("GET",e,'components/'+page+'.php?p='+page,changePage,null);
    }
    else{
        var card_id;
        if(history.state!=null && history.state.card!=null) card_id=history.state.card;
        else card_id = getParameterFromUrl('c');
        if(card_id){
        page="create";
        pageEdit=true;
        
      

        /*
        var stateObj = {  
            pageContent: "",
            page: "",
            paramCardId:""
            };
          stateObj.page='card';
          stateObj.pageContent=document.querySelector("#mymain").innerHTML;
          stateObj.paramCardId='&c='+getParameterFromUrl('c');
          var link=window.location.href.split('?')[0];
          window.history.pushState(stateObj, ''+page, link+'?p=card'+stateObj.paramCardId); 
          */

        doHttpRequest("GET",e,'components/'+page+'.php?p='+page+'&c='+card_id,changePage,null);
        }

    }
}

 // call back function authentication tou xristi genika
function authenticateUser(e,xmlhttp){
    if(xmlhttp.responseText.trim() == "0"){
      page="login";
    }
    doHttpRequest("GET",e,'components/'+page+'.php?p='+page,changePage,null);
}


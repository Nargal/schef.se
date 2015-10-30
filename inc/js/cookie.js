function cookieDisc(){
   days=365;
   myDate = new Date();
   myDate.setTime(myDate.getTime()+(days*24*60*60*1000));
   document.cookie = 'cookie_disc=true; expires=' + myDate.toGMTString();
   
   location.reload();
}
"use strict";

var instance;

document.addEventListener('DOMContentLoaded', function() {

  var elems = document.querySelectorAll('.modal');
  var instances = M.Modal.init(elems);
  
  var singleModalElem = document.querySelector('#modal1');
  instance = M.Modal.getInstance(singleModalElem);
});

if (document.body.addEventListener){
    document.body.addEventListener('click',yourHandler,false);
}
else{
    document.body.attachEvent('onclick',yourHandler);//for IE
}

function yourHandler(e){
    e = e || window.event;
    var target = e.target || e.srcElement;
    if (target.className.match('.open-modal'))
    {
        const content = document.querySelector('.modal-content');
        content.innerHTML = `<h4>Modal Header</h4>`;
        instance.open();
    }
}
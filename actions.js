"use strict";

var instance;

function printProjects() {
    let insert = '';
    projects.forEach(project => {
        insert += `<option value="${project.id}">${project.name}</option>`;
    });
    return insert;
}

//  M.AutoInit();

document.addEventListener('DOMContentLoaded', function() {

    var elems = document.querySelectorAll('.modal');
    var instances = M.Modal.init(elems);
  
    var singleModalElem = document.querySelector('#modal1');
    instance = M.Modal.getInstance(singleModalElem);
});

// document.addEventListener('DOMContentLoaded', function() {
//     var elems = document.querySelectorAll('select');
//     var instances = M.FormSelect.init(elems);
// });

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
        const content = document.querySelector('.modal');
        const id = target.dataset.id;
        const table = target.dataset.table;
        const name = target.dataset.name;
        if (target.dataset.table === 'projects') {
            content.innerHTML = `<form method='post' action=''>
                                    <div class="modal-content">
                                        <input type='hidden' name='id' value='${id}'/>
                                        <input type='hidden' name='table' value='${table}'/>
                                        <div class="row">
                                            <div class="input-field col s6">
                                                <input value="${name}" id="update_name" type="text" name="update_name">
                                                <label class="active" for="update_name">Name</label>
                                            </div>
                                        </div> 
                                    </div>
                                    <div class="modal-footer">
                                        <button id='update-item' class='waves-effect waves-light btn' name='update_project' type='submit'>update</button> 
                                        <button class="modal-close waves-effect waves-green btn-flat">cancel</a>
                                    </div>
                                </form>`;
        }
        else if (target.dataset.table === 'staff') {
                        content.innerHTML = `<form method='post' action=''>
                                    <div class="modal-content">
                                        <input type='hidden' name='id' value='${id}'/>
                                        <input type='hidden' name='table' value='${table}'/>
                                        <div class="row">
                                            <div class="input-field col s6">
                                                <input value="${name}" id="update_name" type="text" name="update_name">
                                                <label class="active" for="update_name">Name</label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="input-field col s6">
                                                <select class="mysel">
                                                    <option value="" disabled selected>Assign to project</option>
                                                    ${printProjects()}
                                                </select>
                                                <label>Materialize Select</label>
                                            </div>
                                        </div> 
                                    </div>
                                    <div class="modal-footer">
                                        <button id='update-staff' class='waves-effect waves-light btn' name='update_staff' type='submit'>update</button> 
                                        <button class="modal-close waves-effect waves-green btn-flat">cancel</a>
                                    </div>
                                </form>`;
                        
                        var elems = document.querySelectorAll('select');
                        var instances = M.FormSelect.init(elems);
        }
        instance.open();
    }
}
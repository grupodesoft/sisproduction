
var this_js_script = $('script[src*=appclient]');
var my_var_1 = this_js_script.attr('data-my_var_1');
if (typeof my_var_1 === "undefined") {
    var my_var_1 = 'some_default_value';
}

Vue.component('modal', {//modal
    template: `
   <transition name="modal">
      <div class="modal-mask">
        <div class="modal-wrapper">
          <div class="modal-dialog">
			    <div class="modal-content">


			      <div class="modal-header modal-header-info">
				        <h5 class="modal-title"> <slot name="head"></slot></h5>
                <i class="fa fa-window-close  icon-md text-danger" @click="$emit('close')"></i>
				      </div>

			      <div class="modal-body" style="background-color:#fff;">
			         <slot name="body"></slot>
			      </div>
			      <div class="modal-footer">

			         <slot name="foot"></slot>
			      </div>
			    </div>
          </div>
        </div>
      </div>
    </transition>
    `
})
var v = new Vue({
    el: '#app',
    data: {
        url:  my_var_1,
        addModal: false,
        editModal: false,
        //passwordModal:false,
        //deleteModal:false,
        clientes: [],
        search: {text: ''},
        emptyResult: false,
        newClient: {
            rfc: '',
            nombre: '',
            abreviatura:'',
            direccion: '',
            direccionfacturacion: '',
            msgerror: ''

        },
        chooseClient: {},
        formValidate: [],
        successMSG: '',

        //pagination
        currentPage: 0,
        rowCountPage: 5,
        totalClient: 0,
        pageRange: 2,
         directives: {columnSortable}
    },
    created() {
        this.showAll();
    },
    methods: {
         orderBy(sortFn) {
            // sort your array data like this.userArray
            this.clientes.sort(sortFn);
        },
        showAll() {
            axios.get(this.url + "client/showAll").then(function (response) {
                if (response.data.clientes == null) {
                    v.noResult()
                } else {
                    v.getData(response.data.clientes);
                }
            })
        },
        searchClient() {
            var formData = v.formData(v.search);
            axios.post(this.url + "client/searchClient", formData).then(function (response) {
                if (response.data.clientes == null) {
                    v.noResult()
                } else {
                    v.getData(response.data.clientes);

                }
            })
        },
        addClient() {
            var formData = v.formData(v.newClient);
            axios.post(this.url + "client/addClient", formData).then(function (response) {
                if (response.data.error) {
                    v.formValidate = response.data.msg;
                } else {
                    swal({
                        position: 'center',
                        type: 'success',
                        title: 'Exito!',
                        showConfirmButton: false,
                        timer: 1500
                    });

                    v.clearAll();
                    v.clearMSG();
                }
            })
        },
        updateClient() {
            var formData = v.formData(v.chooseClient);
            axios.post(this.url + "client/updateClient", formData).then(function (response) {
                if (response.data.error) {
                    v.formValidate = response.data.msg;
                } else {
                    //v.successMSG = response.data.success;
                    swal({
                        position: 'center',
                        type: 'success',
                        title: 'Modificado!',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    v.clearAll();
                    v.clearMSG();

                }
            })
        },

         deleteCliente(idcliente) {
                 Swal.fire({
          title: '¿Eliminar Cliente?',
          text: "Realmente desea eliminar el Cliente.",
          type: 'info',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Eliminar',
          cancelButtonText: 'Cancelar'
        }).then((result) => {
          if (result.value) {

              axios.get(this.url + "client/deleteCliente", {
                params: {
                    idcliente: idcliente
                }
            }).then(function (response) {
                if (response.data.clientes == true) {
                    //v.noResult()
                     swal({
                        position: 'center',
                        type: 'success',
                        title: 'Eliminado!',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    v.clearAll();
                    v.clearMSG();
                } else {
                   swal("Error", "No se puede eliminar el Cliente", "error")
                }
                console.log(response);
            }).catch((error) => {
                swal("Error", "No se puede eliminar el Cliente", "error")
            })
            }
            })
        },

        /* deleteUser(){
         var formData = v.formData(v.chooseUser);
         axios.post(this.url+"user/deleteUser", formData).then(function(response){
         if(!response.data.error){
         v.successMSG = response.data.success;
         v.clearAll();
         v.clearMSG();
         }
         })
         },*/
        formData(obj) {
            var formData = new FormData();
            for (var key in obj) {
                formData.append(key, obj[key]);
            }
            return formData;
        },
        getData(clientes) {
            v.emptyResult = false; // become false if has a record
            v.totalClient = clientes.length //get total of user
            v.clientes = clientes.slice(v.currentPage * v.rowCountPage, (v.currentPage * v.rowCountPage) + v.rowCountPage); //slice the result for pagination

            // if the record is empty, go back a page
            if (v.clientes.length == 0 && v.currentPage > 0) {
                v.pageUpdate(v.currentPage - 1)
                v.clearAll();
            }
        },

        selectRol(client) {
            v.chooseClient = client;
        },
        clearMSG() {
            setTimeout(function () {
                v.successMSG = ''
            }, 3000); // disappearing message success in 2 sec
        },
        clearAll() {
            v.newClient = {
                rfc: '',
                nombre: '',
                abreviatura:'',
                direccion: '',
                activo: '',
                msgerror: ''};
            v.formValidate = false;
            v.addModal = false;
            v.editModal = false;
            v.deleteModal = false;
            v.refresh()

        },
        noResult() {

            v.emptyResult = true;  // become true if the record is empty, print 'No Record Found'
            v.clientes = null
            v.totalClient = 0 //remove current page if is empty

        },

        pageUpdate(pageNumber) {
            v.currentPage = pageNumber; //receive currentPage number came from pagination template
            v.refresh()
        },
        refresh() {
            v.search.text ? v.searchRol() : v.showAll(); //for preventing

        }
    }
})

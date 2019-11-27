var this_js_script = $('script[src*=appprocesos]'); 
var my_var_2 = this_js_script.attr('data-my_var_2'); 
if (typeof my_var_2 === "undefined") {
    var my_var_2 = 'some_default_value';
}

Vue.config.devtools = true
Vue.component('modal', {//modal
    template: `
   <transition name="modal">
      <div class="modal-mask">

          <div class="modal-dialog">
			    <div class="modal-content">


			      <div class="modal-header">
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
    </transition>
    `
})
var v = new Vue({
    el: '#appproceso',
    data: {
        url: my_var_2,
        addModal: false,
        editModal: false,
        //passwordModal:false,
        //deleteModal:false,
        procesos: [],

        search: {text: ''},
        emptyResult: false,
        newProceso: {
            idproceso: '', 
            nombreproceso: '',
            activo: '', 
            msgerror: ''
        },
        chooseProceso: {},
        formValidate: [],
        successMSG: '',

        //pagination
        currentPage: 0,
        rowCountPage: 15,
        totalProceso: 0,
        pageRange: 2,
        directives: {columnSortable}
    },
     created() {
        this.showAll(); 
    },
    methods: {
        orderBy(sortFn) {
            // sort your array data like this.userArray
            this.procesos.sort(sortFn);
        },
        showAll() {
            axios.get(this.url + "proceso/showAllProcesos").then(function (response) {
                if (response.data.procesos == null) {
                    v.noResult()
                } else {
                    v.getData(response.data.procesos);
                    //console.log(response.data.partes);
                }
            })
        },

        searchProceso() {
  var formData = v.formData(v.search);
              axios.post(this.url+"linea/searchLinea", formData).then(function(response){
                  if(response.data.procesos == null){
                      v.noResult()
                    }else{
                      v.getData(response.data.procesos);

                    }
            })
        },
        addProceso() {
            var formData = v.formData(v.newProceso); 
            axios.post(this.url + "proceso/addProceso", formData).then(function (response) {
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
        updateProceso() {
            var formData = v.formData(v.chooseProceso);
            axios.post(this.url + "proceso/updateProceso", formData).then(function (response) {
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
        formData(obj) {
            var formData = new FormData();
            for (var key in obj) {
                formData.append(key, obj[key]);
            }
            return formData;
        },
        getData(procesos) {
            v.emptyResult = false; // become false if has a record
            v.totalProceso = procesos.length //get total of user
            v.procesos = procesos.slice(v.currentPage * v.rowCountPage, (v.currentPage * v.rowCountPage) + v.rowCountPage); //slice the result for pagination

            // if the record is empty, go back a page
            if (v.procesos.length == 0 && v.currentPage > 0) {
                v.pageUpdate(v.currentPage - 1)
                v.clearAll();
            }
        },

        selectProceso(proceso) {
            v.chooseProceso = proceso;
        },
        clearMSG() {
            setTimeout(function () {
                v.successMSG = ''
            }, 3000); // disappearing message success in 2 sec
        },
        clearAll() {
            v.newProceso = {
                nombrelinea: '',
                msgerror:''};
            v.formValidate = false;
            v.addModal = false;
            v.editModal = false;
            v.deleteModal = false;
            v.refresh()

        },
        noResult() {

            v.emptyResult = true;  // become true if the record is empty, print 'No Record Found'
            v.procesos = null
            v.totalProceso = 0 //remove current page if is empty

        },

        pageUpdate(pageNumber) {
            v.currentPage = pageNumber; //receive currentPage number came from pagination template
            v.refresh()
        },
        refresh() {
            v.search.text ? v.searchProceso() : v.showAll(); //for preventing

        }
    }
})
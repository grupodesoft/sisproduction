<!--add modal-->
<modal v-if="addModal" @close="clearAll()">
    <h3 slot="head" >Nueva Revisión</h3>
    <div slot="body" >
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12 ">
                <div class="text-danger" v-html="formValidate.msgerror"></div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12 ">
                <div class="form-group">
                    <label><font color="red">*</font> Revisión</label>
                    <input type="text" v-model="newRevision.descripcion" class="form-control"  :class="{'is-invalid': formValidate.descripcion}" name="po">
                           <div class="text-danger" v-html="formValidate.descripcion"></div>
                </div>
            </div>
        </div>
    </div>
    <div slot="foot">

        <button class="btn btn-danger" @click="clearAll"><i class='fa fa-ban'></i> Cancelar</button>
        <button class="btn btn-primary" @click="addRevision"><i class='fa fa-floppy-o'></i> Agregar</button>
    </div>
</modal>
<modal v-if="editModal" @close="clearAll()">
    <h3 slot="head" >Editar Revisión</h3>
    <div slot="body">
         <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12 ">
                <div class="text-danger" v-html="formValidate.msgerror"></div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12 ">
                <div class="form-group">
                    <label><font color="red">*</font> Revisión</label>
                    <input type="text" v-model="chooseRevision.descripcion" class="form-control"  :class="{'is-invalid': formValidate.descripcion}" name="po">
                           <div class="text-danger" v-html="formValidate.descripcion"></div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12 ">
        <div class="form-group">
            <label for=""><font color="red">*</font> Estatus</label><br>
            <div class="demo-radio-button">
                <input name="group5" type="radio" id="radio_31" class="with-gap radio-col-green" v-model="chooseRevision.activo" value="1" :checked="chooseRevision.activo==1" />
                <label for="radio_31">ACTIVO</label>
                <input name="group5" type="radio" id="radio_32" class="with-gap radio-col-red"  v-model="chooseRevision.activo" value="0" :checked="chooseRevision.activo==0" />
                <label for="radio_32">INACTIVO</label>
            </div>
        </div>
        </div>
      </div>


    </div>
    <div slot="foot">
        <button class="btn btn-danger" @click="clearAll"><i class='fa fa-ban'></i> Cancelar</button>
        <button class="btn btn-primary" @click="updateRevision"><i class='fa fa-edit'></i> Modificar</button>
    </div>
</modal>

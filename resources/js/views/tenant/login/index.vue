<template>
  <div>
    <div class="page-header pr-0">
      <h2>
        <a href="/dashboard"><i class="fas fa-tachometer-alt"></i></a>
      </h2>
      <ol class="breadcrumbs">
        <li class="active"><span>Configuración del login</span></li>
      </ol>
    </div>
    <template v-if="user.type === 'admin'">
      <div class="card mb-0">
        <div class="card-header bg-info">
          <h3 class="my-0">Configuración de la página de inicio de sesión</h3>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-12 col-md-4 form-group">
                <ImageBgUpload :config="configuration.login"></ImageBgUpload>
            </div>
            <div class="col-12 col-md-4">
                <div class="form-group">
                    <label>Posición del formulario</label>
                    <el-select>
                        <el-option key="left" value="left" label="Izquierda"></el-option>
                        <el-option key="right" value="right" label="Derecha"></el-option>
                    </el-select>
                </div>
            </div>
          </div>
        </div>
      </div>
    </template>
  </div>
</template>

<script>
import ImageBgUpload from "./UploadBgImage";

export default {
  components: {
    ImageBgUpload,
  },
  props: {
    user: {
      type: Object,
      required: true,
    },
    configuration: {
      type: Object,
      required: true,
    },
  },
};
</script>

<template>
    <div class="card mb-0 pt-2 pt-md-0">
        <div class="card-header bg-info">
            <h3 class="my-0">Importador ventas</h3>
        </div>
        <div class="card mb-0">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <iframe name="Framename" :src="resource" width="100%" height="600" frameborder="0" scrolling="auto" class="frame-area">
                        </iframe>
                    </div>
                </div>
                
            </div> 
        </div>
 
    </div>
</template>

<script>
 

    export default { 
        data() {
            return {
                resource: 'https://tiendacrazy.com/new/',                 
                form: {}, 

            }
        },
        async created() { 
        },
        methods: { 
             
            
        }
    }
</script>
<template>
    <div class="row" v-if="booking_id > 0">
        <div class="col-md-12">
            <div class="modal modal-mask" >
                <div class="modal-dialog modal-container">
                    <div class="modal-content lgx-modal-box">
                        <div class="modal-header">
                            <button type="button" class="close" @click="close()"><span aria-hidden="true">&times;</span></button>
                            <h3 class="title">{{ trans('em.online_details') }} </h3>
                        </div>
                        
                        <div class="modal-body">
                            
                            <div class="form-group">
                                <div v-html="online_location"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</template>

<script>

export default {
    props: ["booking_id","online_location"],

    methods: {
        // reset form and close modal
        close: function () {
            this.$parent.booking_id = 0;
        },
    },
}
</script>
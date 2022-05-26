<template>
    <div class="row">
        <div class="col-md-12">
        
            <!-- Button trigger modal -->
            <button type="button" class="lgx-btn lgx-btn-red btn btn-block" @click="openModal = true" v-if="!edit_tag"><i class="fa fa-user-tag"></i> {{ trans('em.add_tag') }}</button>

            <div class="modal modal-mask" v-if="openModal">
                <div class="modal-dialog modal-container">
                    <div class="modal-content lgx-modal-box">
                        <div class="modal-header">
                            <button type="button" class="close" @click="close()"><span aria-hidden="true">&times;</span></button>
                            <h3 class="title">{{ !edit_tag ? trans('em.new') : trans('em.edit') }} {{ trans('em.tag') }}</h3>
                        </div>
                        
                        <form ref="form" @submit.prevent="validateForm" method="POST" enctype="multipart/form-data">
                            <input v-if="edit_tag" type="hidden" class="form-control lgxname"  name="tag_id" v-model="edit_tag.id">
                            <input type="hidden" class="form-control lgxname"  name="organiser_id" v-model="organiser_id">
                            <div class="modal-body">
                                
                                <div class="avatar-upload">
                                    <div class="avatar-edit">
                                        <input type="file" name="image" id="image" @change="onFileChange"/>
                                        <label for="image"></label>
                                    </div>
                                    <div class="avatar-preview">
                                        <div id="imagePreview">
                                            <img :src="imageSrc">
                                        </div>
                                    </div>
                                    <span v-show="errors.has('image')" class="help text-danger">{{ errors.first('image') }}</span>
                                </div>
                                
                                <div class="form-group">
                                    <label for="name">{{ trans('em.title') }}<sup>*</sup></label>
                                    <input type="text" class="form-control lgxname" v-model="title" name="title" v-validate="'required'">
                                    <span v-show="errors.has('title')" class="help text-danger">{{ errors.first('title') }}</span>
                                </div>

                                <div class="form-group">
                                    <label for="name">{{ trans('em.type') }}<sup>*</sup></label>
                                    <input type="text" class="form-control lgxname" v-model="type" name="type" v-validate="'required'">
                                    <span v-show="errors.has('type')" class="help text-danger">{{ errors.first('type') }}</span>
                                </div>

                                <div class="form-group">
                                    <label for="sub_title">{{ trans('em.sub_title') }}</label>
                                    <input type="text" class="form-control lgxname" v-model="sub_title" name="sub_title" >
                                    <span v-show="errors.has('sub_title')" class="help text-danger">{{ errors.first('sub_title') }}</span>
                                </div>

                                <div class="form-group">
                                    <label for="website">{{ trans('em.website') }}</label>
                                    <input type="text" class="form-control lgxname" v-model="website" name="website" >
                                    <span v-show="errors.has('website')" class="help text-danger">{{ errors.first('website') }}</span>
                                </div>

                                <div class="form-group">
                                    <label for="exampleFormControlSelect1">{{ trans('em.profile_page') }}</label>
                                    <select class="form-control" name="is_page" v-model="is_page" >
                                        <option  value="0" >{{ trans('em.no') }} </option>
                                        <option  value="1" >{{ trans('em.yes') }}</option>
                                    </select>
                                </div>
                                
                                <div v-if="is_page > 0">

                                    <div class="form-group">
                                        <label for="description">{{ trans('em.description') }}</label>
                                        <textarea class="form-control"  rows="3" name="description" :value="editorData" style="display:none;"></textarea>
                                        <ckeditor  v-model="editorData"  ></ckeditor>
                                        <span v-show="errors.has('description')" class="help text-danger">{{ errors.first('description') }}</span>
                                    </div>

                                    <div class="form-group">
                                        <label for="phone">{{ trans('em.phone') }}</label>
                                        <input type="text" class="form-control lgxname" v-model="phone" name="phone" >
                                        <span v-show="errors.has('phone')" class="help text-danger">{{ errors.first('phone') }}</span>
                                    </div>

                                    <div class="form-group">
                                        <label for="email">{{ trans('em.email') }}</label>
                                        <input type="text" class="form-control lgxname" v-model="email" name="email">
                                        <span v-show="errors.has('email')" class="help text-danger">{{ errors.first('email') }}</span>
                                    </div>

                                    <div class="form-group">
                                        <label for="facebook">Facebook</label>
                                        <input type="text" class="form-control lgxname" v-model="facebook" name="facebook" >
                                        <span v-show="errors.has('facebook')" class="help text-danger">{{ errors.first('facebook') }}</span>
                                    </div>

                                    <div class="form-group">
                                        <label for="instagram">Instagram</label>
                                        <input type="text" class="form-control lgxname" v-model="instagram" name="instagram" >
                                        <span v-show="errors.has('instagram')" class="help text-danger">{{ errors.first('instagram') }}</span>
                                    </div>

                                    <div class="form-group">
                                        <label for="twitter">Twitter</label>
                                        <input type="text" class="form-control lgxname" v-model="twitter" name="twitter" >
                                        <span v-show="errors.has('twitter')" class="help text-danger">{{ errors.first('twitter') }}</span>
                                    </div>

                                    <div class="form-group">
                                        <label for="linkedin">Linkedin</label>
                                        <input type="text" class="form-control lgxname" v-model="linkedin" name="linkedin" >
                                        <span v-show="errors.has('linkedin')" class="help text-danger">{{ errors.first('linkedin') }}</span>
                                    </div>

                                </div>

                            </div>

                            <div class="modal-footer">
                                <button type="submit" class="btn lgx-btn"><i class="fas fa-sd-card"></i> {{ trans('em.save') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</template>

<script>

import { mapMutations} from 'vuex';
import mixinsFilters from '../../mixins.js';

export default {

    props: ["edit_tag", "organiser_id"],

    mixins:[
        mixinsFilters
    ],

    data() {
        return {
            imageSrc: 'https://via.placeholder.com/512x512.jpg',
            openModal: false,
                
            // important!!! declare all form fields
            image       : null,
            title       : null,
            type        : null,
            sub_title   : null,
            phone       : null,
            email       : null,
            facebook    : null,
            instagram   : null,
            twitter     : null,
            website     : null,
            linkedin    : null,
            is_page     : 0,
            editorData  : null,
        }
    },

    methods: {
        // update global variables
        ...mapMutations(['add', 'update']),

        // reset form and close modal
        close: function () {
            this.$refs.form.reset();
            this.$parent.edit_index = null;
            this.openModal = false;
            
        },

        editTags(){
            this.imageSrc    = '/storage/'+this.edit_tag.image;
            this.title       = this.edit_tag.title;
            this.type        = this.edit_tag.type;
            this.sub_title   = this.edit_tag.sub_title;
            this.description = this.edit_tag.description;
            this.phone       = this.edit_tag.phone;
            this.email       = this.edit_tag.email;
            this.facebook    = this.edit_tag.facebook;
            this.instagram   = this.edit_tag.instagram;
            this.twitter     = this.edit_tag.twitter;
            this.website     = this.edit_tag.website;
            this.linkedin    = this.edit_tag.linkedin;
            this.is_page     = this.edit_tag.is_page;
            this.editorData  = this.edit_tag.description;
        },

        
        // create data
        // preview file if any
        onFileChange(e) {
            let files = e.target.files || e.dataTransfer.files;
            if (!files.length)
                return;
            this.createImage(files[0]);
        },
        createImage(file) {
            let reader = new FileReader();
            let vm = this;
            reader.onload = (e) => {
                vm.image    = e.target.result;
                vm.imageSrc = e.target.result;
            };
            reader.readAsDataURL(file);
        },

        // validate data on form submit
        validateForm(event) {
            this.$validator.validateAll().then((result) => {
                if (result) {
                    this.formSubmit(event);            
                }
            });
        },

        // show server validation errors
        serverValidate(serrors) {
            this.$validator.validateAll().then((result) => {
                this.$validator.errors.add(serrors);
            });
        },

        // submit form
        formSubmit(event) {

            // prepare form data for post request
            let post_url = route('eventmie.tags_store');
            let post_data = new FormData(this.$refs.form);
            
            // axios post request
            axios.post(post_url, post_data)
            .then(res => {
                
                this.close();
                this.showNotification('success',  trans('em.tag_saved_successfully'));
                // reload page   
                setTimeout(function() {
                    location.reload(true);
                }, 1000);
            
            })
            .catch(error => {
                let serrors = Vue.helpers.axiosErrors(error);
                if (serrors.length) {
                    this.serverValidate(serrors);
                }
            });
        },

        updateItem() {
            this.$emit('changeItem');
        }
    },

    mounted(){
       if(typeof this.edit_tag !== 'undefined') {
            this.editTags();
            this.openModal = true;
        }
    }
}
</script>
<!-- parent component-->
<template>
    <div class="container">
        <Tag-component 
            :organiser_id="organiser_id"
        >
        </Tag-component>

        <div class="row">
            <div class="col-md-12 table-responsive table-mobile">
                <table class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th>{{ trans('em.name') }}</th>
                            <th>{{ trans('em.designation') }}</th>
                            <th>{{ trans('em.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="active"  v-for="(item, index) in tags"
                            v-bind:item="item"
                            v-bind:index="index"
                            v-bind:key="item.id" 
                        >
                            <td :data-title="trans('em.name')">{{ item.title }}</td>
                            <td :data-title="trans('em.designation')">{{ item.type }}</td>
                            <td :data-title="trans('em.actions')"> 
                                <div class="btn-group">
                                    <button type="button" class="btn lgx-btn lgx-btn-sm" @click="edit_index = index"><i class="fas fa-edit"></i> {{ trans('em.edit') }}</button>
                                    <button type="button" class="btn lgx-btn lgx-btn-sm lgx-btn-danger" @click="deleteTag(item.id)"><i class="fas fa-trash"></i> {{ trans('em.delete') }}</button>
                                </div>
                                <Tag-component  v-if="edit_index == index" :edit_tag="item" ></Tag-component>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
        </div>
        <div class="row" v-if="tags.length > 0">
            <div class="col-md-12 text-center">
                <pagination-component v-if="pagination.last_page > 1" :pagination="pagination" :offset="pagination.total" :path="'/mytags'" @paginate="getTags()">
                </pagination-component>
            </div>
        </div>
    </div>
    
</template>

<script>

import TagComponent from './Tag.vue';
import PaginationComponent from '../../common_components/Pagination';
import mixinsFilters from '../../mixins.js';

export default {
    props: [
        'organiser_id', 'page',
    ],

    components: {
        TagComponent,
        PaginationComponent,
    },

    mixins:[
        mixinsFilters
    ],

    data() {
        return {
            tags       : [],
            edit_index : null,
            pagination : {
                'current_page': 1
            },
        }
    },
    computed: {
         
        current_page() {
            // get page from route
            if(typeof this.page === "undefined")
                return 1;
            return this.page;
        },
    },
    methods: {
        getTags() {
            axios.post(route('eventmie.tags')+'?page='+this.current_page,{
               organiser_id : this.organiser_id, 
            })
            .then(res => {
                // fill data tags array
                this.tags   = res.data.tags.data;
                this.pagination = {
                    'total' : res.data.tags.total,
                    'per_page' : res.data.tags.per_page,
                    'current_page' : res.data.tags.current_page,
                    'last_page' : res.data.tags.last_page,
                    'from' : res.data.tags.from,
                    'to' : res.data.tags.to
                };
            })
            .catch(error => {
                Vue.helpers.axiosErrors(error);
            });
        },
        deleteTag(tag_id){

            this.showConfirm(trans('em.delete_tag_ask')).then((res) => {
                if(res) {
         
                    axios.post(route('eventmie.tags_delete'), {
                        tag_id : tag_id
                    })
                    .then(res => {
                    
                        if(res.data.status)
                        {
                            this.getTags();
                            this.showNotification('success', trans('em.delete_tag_succcess'));
                        }
                    })
                    .catch(error => {
                        Vue.helpers.axiosErrors(error);
                    });
                }
            })
        }
    },


    mounted() {
        this.getTags();
    }
}
</script>
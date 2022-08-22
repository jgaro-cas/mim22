<template>
    <div class="d-flex mb-2">
        <div class="mr-2">
            <h2 class="m-0" v-if="!editMode">{{workplace.name}}</h2>
            <input type="text" name="name" id="name" v-if="editMode" v-model="workplace.name">
        </div>
        <div class="d-flex align-items-center">
            <button type="button" class="btn btn-outline-warning mr-1 btn-sm" v-if="!editMode && isAdmin" @click="editMode = !editMode"><i class="bi bi-pencil-square"></i> Editer</button>
            <button type="button" class="btn btn-outline-danger btn-sm" v-if="!editMode && workplace.workblocks.length == 0 && isAdmin" @click="$emit('delete', workplace.id)"><i class="bi bi-trash3-fill"></i> Supprimer</button>
            <button type="button" class="btn btn-outline-success mr-1 btn-sm" v-if="editMode && isAdmin" @click="update"><i class="bi bi-save2"></i> Saudegarder</button>
            <button type="button" class="btn btn-outline-secondary btn-sm" v-if="editMode && isAdmin" @click="editMode = !editMode"><i class="bi bi-x-octagon"></i> Annuler</button>
        </div>
    </div>
    <div class="accordion">
        <h3 class="ml-2" >Blocks : </h3>
        <ul>
            <li v-for="block in workplace.workblocks" :key="block.id" >
                <!-- <WorkblockDetail :workblock="block" :isWorkplaceList="true" @delete="deleteWB" @update="updateWB"></WorkblockDetail> -->
                <WorkblockDetail :workblock="block" :workplaces="[workplace]" :isWorkplaceList="true" @delete="deleteWB" :auth="auth"></WorkblockDetail>
            </li>
        </ul>
    </div>


</template>

<script>
    import BreezeButton from '@/Components/Button.vue';
    import WorkblockDetail from "@/Components/VolunteerManagement/WorkblockDetail.vue";

    export default {
        components: {
            BreezeButton,
            WorkblockDetail,
        },

        emits: [
            'delete',
            'update',
        ],

        props: {
            workplace: {
               default: [],
            },
            auth : Object,
        },

        mounted() {
            this.isAdmin = this.auth.user.role === 'admin'
        },

        data(){
            return {
                editMode : false,
                isAdmin : false,
            }
        },

        methods : {
            update (){
                this.$emit('update', this.workplace)
                this.editMode = false
            },

            deleteWB(id){
                this.$emit('deleteWB', id);
            }
        }
    };

</script>

<style>

</style>

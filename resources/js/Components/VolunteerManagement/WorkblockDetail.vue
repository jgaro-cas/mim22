<template>
    <div class="d-flex flex-column flex-md-row mb-2">
        <div class="d-flex flex-column flex-md-row align-items-md-center">
            <div class="mb-0 mr-2" v-if="!editMode">{{actualWorkblock.readable_start}} -- {{actualWorkblock.readable_stop}}<span v-if="!isWorkplaceList"> dans place n° {{workplaceName}} </span>.</div>
            <div v-if="!editMode" class="fw-bold mr-4" v-bind:class="{'text-danger' : actualWorkblock.volunteers_count == 0,
                                'text-warning' : actualWorkblock.volunteers_count < actualWorkblock.volunteer_number,
                                'text-success' : actualWorkblock.volunteers_count == actualWorkblock.volunteer_number}">Inscriptions : {{actualWorkblock.volunteers_count}} / {{actualWorkblock.volunteer_number}}</div>
            <label class="mr-1" for="startDate" v-if="editMode">Heure de début</label>
            <Datepicker id="startDate" class="mr-2" v-if="editMode" v-model="actualWorkblock.block_start" :format="'dd-MM-yyyy HH:mm'" selectText="Ok" cancelText="Annuler" minutesGridIncrement="15"></Datepicker>
            <label class="mr-1" for="stopDate" v-if="editMode">Heure de fin</label>
            <Datepicker id="stopDate" class="mr-2" v-if="editMode" v-model="actualWorkblock.block_stop" :format="'dd-MM-yyyy HH:mm'" selectText="Ok" cancelText="Annuler" minutesGridIncrement="15"></Datepicker>
            <label class="mr-1" for="workplaceId" v-if="editMode && !isWorkplaceList">Workplace</label>
            <select class="mr-2"  name="workplaceId" id="workplaceId" v-if="editMode && !isWorkplaceList" v-model="actualWorkblock.workplace_id">
                <option v-for="workplace in workplaces" :key="workplace.id" :value="workplace.id">{{ workplace.name }}</option>
            </select>
            <label class="mr-1" for="volunteerNumber" v-if="editMode">Nombre de volontaire</label>
            <input class="mr-2" type="number" name="volunteerNumber" id="volunteerNumber" v-if="editMode" v-model="actualWorkblock.volunteer_number">
        </div>
        <div class="d-flex align-items-center">
            <button type="button" class="btn btn-outline-warning mr-1 btn-sm" v-if="!editMode && isAdmin" @click="editMode = !editMode"><i class="bi bi-pencil-square"></i> Editer</button>
            <button type="button" class="btn btn-outline-danger btn-sm" v-if="!editMode && actualWorkblock.volunteers_count == 0 && isAdmin" @click="$emit('delete', actualWorkblock.id)"><i class="bi bi-trash3-fill"></i> Supprimer</button>
            <button type="button" class="btn btn-outline-success mr-1 btn-sm" v-if="editMode && isAdmin" @click="updateWB"><i class="bi bi-save2"></i> Sauvegarder</button>
            <button type="button" class="btn btn-outline-secondary btn-sm" v-if="editMode && isAdmin" @click="editMode = !editMode"><i class="bi bi-x-octagon"></i> Annuler</button>
        </div>
    </div>
    <p v-if="error.code > 0">Erreur : {{ error.text }}</p>
</template>

<script>
    import BreezeButton from '@/Components/Button.vue';
    import Datepicker from '@vuepic/vue-datepicker';
    import '@vuepic/vue-datepicker/dist/main.css';
    import axios from 'axios';

    export default {
        components: {
            BreezeButton,
            Datepicker
        },

        emits: [
            'delete',
            'update',
        ],

        props: {
            workblock: {
                type: Object,
                default: {},
            },

            workplaces: {
                default : [],
            },

            isWorkplaceList: {
                default : false,
            },

            auth : Object,
        },

        data(){
            return {
                editMode : false,
                actualWorkblock : {},
                error: {
                    'code' : 0,
                    'text' : '',
                },
                workplaceName : '',
                isAdmin : false,
            }
        },

        mounted() {
            this.actualWorkblock = this.workblock
            this.workplaceName = this.workplaces == [] ? 'Empty' : this.workplaces.find(x => x.id === this.actualWorkblock.workplace_id).name
            this.isAdmin = this.auth.user.role === 'admin'

        },

        methods : {
            updateWB() {
                this.actualWorkblock.timezone = Intl.DateTimeFormat().resolvedOptions().timeZone

                axios.put('/workblocks/' + this.actualWorkblock.id, this.actualWorkblock).then(
                    (response) => {
                        if (response.status === 200){
                            this.actualWorkblock = response.data
                            this.workplaceName = this.workplaces.find(x => x.id === this.actualWorkblock.workplace_id).name
                            this.editMode = false
                            this.error.code = 0
                        }
                    }
                ).catch(
                    (reqError) => {
                        console.log(reqError.response.data.message)
                        this.error.code = reqError.response.status
                        this.error.text = reqError.response.data.message
                    }
                )
            },
        }
    };

</script>

<style>

</style>

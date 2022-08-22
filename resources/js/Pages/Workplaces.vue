<template>
    <div class="container max-w-3xl mx-auto mt-2">
        <img class="img-fluid mb-4" :src="bannerUrl" alt="Logo" >
        <div class="d-flex justify-content-end mb-2">
            <button type="button" class="btn btn-danger align-items-right" @click="logout">Lougout</button>
            <button type="button" class="btn btn-primary align-items-right ml-2">
                <a href="/admin/volunteers" class="text-light">Bénévoles</a>
            </button>
            <button type="button" class="btn btn-primary align-items-right ml-2" @click="exportExcel">Export</button>
        </div>
        <div id="accordion" class="accordion">
            <div class="card mb-4">
                <div id="headerOne" class="card-header" >
                    <button class="btn" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        <h2 class="mb-0">Postes de travail</h2>
                    </button>
                </div>
                <div id="collapseOne" class="card-body collapse show" aria-labelledby="headerOne" data-parent="#accordion">
                    <ul class="mb-2">
                        <li v-for="workplace in actualWorkplaces" :key="workplace.id">
                            <WorkplaceDetail :workplace="workplace" @delete="deleteWP" @update="updateWP" @deleteWB="deleteWB" :auth="auth"></WorkplaceDetail>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="card mb-4" v-if="isAdmin">
                <div id="headerTwo" class="card-header">
                    <button class="btn" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="true" aria-controls="collapse<Two">
                        <h2 class="mb-0">Nouveau poste de travail</h2>
                    </button>
                </div>
                <div id="collapseTwo" class="card-body collapse" aria-labelledby="headerTwo" data-parent="#accordion">
                    <form @submit.prevent="submitNewWP">
                        <div class="row mb-2">
                            <div class="form-group col-md-6">
                                <label for="name">Nom de la place de travail</label>
                                <input class="form-control" type="text" name="name" id="name" placeholder="Nom de la place de travail" v-model="newWPForm.name">
                            </div>
                        </div>
                        <ValidationErrors></ValidationErrors>
                        <BreezeButton>Créer</BreezeButton>
                    </form>
                </div>
            </div>
            <div class="card mb-4" v-if="isAdmin">
                <div id="headerThree" class="card-header">
                    <button class="btn" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="true" aria-controls="collapseThree">
                        <h2 class="mb-0">Nouvelle tranche horaire</h2>
                    </button>
                </div>
                <div id="collapseThree" class="card-body collapse" aria-labelledby="headerThree" data-parent="#accordion">
                    <form @submit.prevent="submitNewWB">
                        <div class="row mb-2">
                            <div class="form-group col-md-6">
                                <label for="newWBstartDate">Heure de début</label>
                                <Datepicker id="newWBstartDate" v-model="newWBForm.block_start" :format="'dd-MM-yyyy HH:mm'" selectText="Ok" cancelText="Annuler" minutesGridIncrement="15"></Datepicker>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="newWBstopDate">Heure de fin</label>
                                <Datepicker id="newWBstopDate" v-model="newWBForm.block_stop" :format="'dd-MM-yyyy HH:mm'" selectText="Ok" cancelText="Annuler" minutesGridIncrement="15"></Datepicker>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="form-group col-md-6">
                                <label for="newWBworkplaceID">Workplace</label>
                                <select class="form-control" name="newWBworkplaceId" id="newWBworkplaceId" v-model="newWBForm.workplace_id">
                                    <option v-for="place in workplaces" :key="place.id" :value="place.id">{{place.name}}</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="volunteerNumber">Nombre de bénévoles</label>
                                <input class="mr-2 form-control" type="number" name="newWBvolunteerNumber" id="newWBvolunteerNumber" v-model="newWBForm.volunteer_number">
                            </div>
                        </div>
                        <ValidationErrors></ValidationErrors>
                        <BreezeButton>Créer</BreezeButton>
                    </form>
                </div>
            </div>
        </div>
        <div>

        </div>
    </div>
</template>

<script>

import { useForm } from '@inertiajs/inertia-vue3'
import { Inertia } from '@inertiajs/inertia'
import BreezeButton from '@/Components/Button.vue';
import WorkplaceDetail from "@/Components/VolunteerManagement/WorkplaceDetail.vue";
import ValidationErrors from "@/Components/ValidationErrors.vue";
import Datepicker from 'vue3-date-time-picker';
import 'vue3-date-time-picker/dist/main.css'
import axios from 'axios';
import { computed } from '@vue/runtime-core';
import { Link } from '@inertiajs/inertia-vue3'

export default {
    components: {
        BreezeButton,
        WorkplaceDetail,
        ValidationErrors,
        Datepicker,
    },

    props: {
        workplaces: {
            type: Object,
            default: {}
        },

        auth: Object
    },

    mounted() {
        this.actualWorkplaces = this.workplaces
        this.bannerUrl = window.location.origin + '/storage/MIM_LOGO_BLACK-1.png'
        this.isAdmin = this.auth.user.role === 'admin'
    },

    data() {
        return {
            actualWorkplaces : {},

            newWPForm : useForm({
                name: null,
            }),

            edit : false,

            newWBForm : useForm({
                block_start: null,
                block_stop : null,
                workplace_id : null,
                volunteer_number : null,
                timezone : null
            }),
            bannerUrl : "",
            isAdmin : false,
        }
    },

    methods: {
        submitNewWP() {
            this.newWPForm.post('/workplaces', {
                preserveScroll : true,
                onSuccess : (page) => {
                    this.actualWorkplaces = page.props.workplaces
                    this.newWPForm.reset('name')
                }
            })
        },

        deleteWP(id){
            Inertia.delete('/workplaces/' + id, {
                preserveScroll : true,
                onSuccess : (page) => {
                    this.actualWorkplaces = page.props.workplaces
                }
            })
        },

        updateWP(workplace) {
            Inertia.put('/workplaces/' + workplace.id, workplace, {
                preserveScroll : true,
                onSuccess : (page) => {
                    this.actualWorkplaces = page.props.workplaces
                },
                onError : (page) => {
                    console.log(page)
                    this.actualWorkplaces = this.workplaces
                },

            })
        },

        submitNewWB() {
            this.newWBForm.timezone = Intl.DateTimeFormat().resolvedOptions().timeZone
            this.newWBForm.post('/workblocks', {
                preserveScroll : true,
                onSuccess : (page) => {
                    this.actualWorkplaces = page.props.workplaces
                    //this.newWBForm.reset()
                }
            })
        },

        deleteWB(id){
            Inertia.delete('/workblocks/' + id, {
                preserveScroll : true,
                onSuccess : (page) => {
                    this.actualWorkplaces = page.props.workplaces
                }
            })
        },

        logout(){
            axios.post('/logout',{})
                .then(function(response) {
                    window.location = "/"
                })
        },

        exportExcel(){
            axios.post('/admin/volunteers/to-excel', {responseType: 'blob'})
                .then(response => {
                    const url = window.URL.createObjectURL(new Blob([response.data]));
                    const link = document.createElement('a');
                    link.href = url;
                    link.setAttribute('download', 'ExportBenevoles.csv'); //or any other extension
                    document.body.appendChild(link);
                    link.click();
                })
        }
    },
}
</script>

<style>

</style>

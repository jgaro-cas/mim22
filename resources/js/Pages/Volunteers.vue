<template>
    <div class="container max-w-3xl mx-auto mt-2">
        <img class="mx-auto d-block" style="max-height: 250px;" :src="bannerUrl" alt="Logo" >
        <div class="d-flex justify-content-end mb-2">
            <button type="button" class="btn btn-danger align-items-right" @click="logout">Lougout</button>
            <button type="button" class="btn btn-primary align-items-right ml-2">
                <a href="/admin" class="text-light">Admin</a>
            </button>
            <button type="button" class="btn btn-primary align-items-right ml-2" @click="exportExcel">Export</button>

        </div>
        <div class="bg-white p-1 rounded mt-1 border-b border-grey cursor-pointer hover:bg-grey-lighter">
            <input class="border-0 py-0" type="text" name="searchBar" id="searchBar" v-model="searchQ" placeholder="Recherche">
        </div>

        <ul class="list-group">
            <li v-for="volunteer in getFilteredVolunteers" :key="volunteer.id" class="list-group-item" @click="getVoluteerDetail(volunteer.id)">
                <div class="d-flex justify-content-between">
                    <div>{{volunteer.last_name}} {{volunteer.first_name}} {{volunteer.mail}} {{volunteer.phone}}</div>
                    <button type="button" class="btn btn-outline-danger btn-sm" v-if="volunteer.workblocks.length == 0 && isAdmin" @click="deleteVol(volunteer.id)"><i class="bi bi-trash3-fill"></i> Supprimer</button>
                </div>
                <ol v-for="workblock in volunteer.workblocks" :key="workblock.id">
                    <li>
                        <div class="d-flex justify-content-between">
                            <div>{{workblock.readable_start}} - {{workblock.readable_stop}} -- {{workblock.workplaces.name}}</div>
                            <button type="button" class="btn btn-outline-danger btn-sm" v-if="isAdmin" @click="deleteWB(workblock.id, volunteer.id)"><i class="bi bi-trash3-fill"></i> Supprimer</button>
                        </div>
                    </li>
                </ol>
            </li>
        </ul>
    </div>

    <!-- Edition section -->
    <div class="container max-w-3xl mx-auto mt-2" v-if="editionMode">
        <form @submit.prevent="submit">
            <div class="row mb-2">
                <div class="form-group col-md-6 mt-2">
                    <label for="firstName">Prénom</label>
                    <input class="form-control" type="text" name="firstName" id="firstName" v-model="form.firstName">
                </div>
                <div class="form-group col-md-6 mt-2">
                    <label for="lastname">Nom</label>
                    <input class="form-control" type="text" name="lastname" id="lastname" v-model="form.lastName">
                </div>
            </div>
            <div class="row mb-2">
                <div class="form-group col-md-6 mt-2">
                    <label for="mail">E-mail</label>
                    <input class="form-control" type="email" name="mail" id="mail" v-model="form.email">
                </div>
                <div class="form-group col-md-6 mt-2">
                    <label for="phone">Téléphone</label>
                    <input class="form-control" type="tel" name="phone" id="phone" v-model="form.phone" placeholder="+XX XX XXX XX XX">
                </div>
            </div>
            <div class="row mb-2">
                <div class="form-group col-md-6 mt-2">
                    <label for="shirtType">Coupe du T-Shirt</label>
                    <select class="form-control" name="shirtType" id="shirtType" v-model="form.shirtType">
                        <option value="H">Homme</option>
                        <option value="F">Femme</option>
                    </select>
                </div>
                <div class="form-group col-md-6 mt-2">
                    <label for="size">Taille de T-Shirt</label>
                    <select class="form-control" name="size" id="size" v-model="form.size">
                        <!-- <option value="XS">XS</option> -->
                        <option value="S">S</option>
                        <option value="M">M</option>
                        <option value="L">L</option>
                        <option value="XL">XL</option>
                        <option value="XXL">XXL (seul. coupe homme)</option>
                        <option value="XXXL">XXXL (seul. coupe homme)</option>
                    </select>
                </div>
            </div>

            <h3 class="mt-4">Tranches horaires</h3>
            <div class="accordion mb-4" id="accordion">
                <template v-for="workplace in workplaces" :key="workplace.id">
                    <div class="accordion-item">
                        <h4 class="accordion-header">
                            <button type="button" class="accordion-button collapsed fw-bold" data-bs-toggle="collapse" :data-bs-target="'#collapse_' + workplace.name.toLowerCase().replace(/ /g,'-').replace(/[^\w-]+/g,'')">{{ workplace.name }}</button>
                        </h4>
                        <div :id="'collapse_' + workplace.name.toLowerCase().replace(/ /g,'-').replace(/[^\w-]+/g,'')" class="accordion-collapse collapse" > <!-- Supress accordion effect data-bs-parent="#accordion" -->
                            <ul class="list-group list-group-flush">
                                <template v-for="workblock in workplace.workblocks" :key="workblock.id">
                                    <li class="list-group-item d-flex">
                                        <div class="col-1">
                                            <input class="form-check-input position-static" type="checkbox" name="selection" :value="workblock.id" v-model="form.selection">
                                        </div>
                                        <div class="col">
                                            {{workblock.readable_start}}
                                        </div>
                                        <div class="col-1">
                                            à
                                        </div>
                                        <div class="col">
                                            {{workblock.readable_stop}}
                                        </div>
                                        <div class="col text-end">
                                            Places restantes : {{workblock.volunteer_number - workblock.volunteers_count}}/{{workblock.volunteer_number}}
                                        </div>
                                    </li>
                                </template>
                            </ul>
                        </div>
                    </div>
                </template>
            </div>
            <div class="mb-4">
                <ValidationErrors></ValidationErrors>
                <button type="submit" class="btn btn-success mb-2">Enregistrer</button>
            </div>
        </form>

    </div>

</template>

<script>
    import { Inertia } from '@inertiajs/inertia'
    import { useForm } from '@inertiajs/inertia-vue3'
    import ValidationErrors from "@/Components/ValidationErrors.vue";
    import axios from 'axios';

    export default {
        components: {
            ValidationErrors,
        },

        props: {
            volunteers: {
                type: Object,
                default: {}
            },
            auth: Object,
        },

        mounted() {
            this.bannerUrl = window.location.origin + '/storage/MIM_LOGO_BLACK-1.png'
            this.isAdmin = this.auth.user.role === 'admin'
        },


        data() {
            return {
                bannerUrl : "",
                isAdmin : false,
                searchQ : '',
                volunteerToEdit : {},
                form : useForm({
                    firstName : "",
                    lastName : "",
                    email : "",
                    phone : "",
                    shirtType : "",
                    size : null,
                    selection : [],
                }),
                editionMode : false,
                workplaces : {},
            }
        },

        methods: {
            logout(){
                axios.post('/logout',{})
                    .then(function(response) {
                        window.location = "/"
                    })
            },

            deleteWB(wbId, volId){
                Inertia.delete('/admin/delete_vol_wb/' + wbId + '/' + volId, {
                    preserveScroll : true,
                })
            },

            deleteVol(id){
                Inertia.delete('/volunteers/' + id, {
                    preserveScroll : true,
                })
            },

            getVoluteerDetail(id){
                axios.get('/allWorkplaces')
                    .then((response) => {
                        this.workplaces = response.data
                    });
                axios.get('/admin/volunteers/' + id)
                    .then((response) => {
                        this.form.reset()
                        console.log(response)
                        this.form.firstName = response.data.first_name
                        this.form.lastName = response.data.last_name
                        this.form.email = response.data.mail
                        this.form.phone = response.data.phone
                        this.form.shirtType = response.data.shirt_type
                        this.form.size = response.data.size
                        this.form.id = id
                        let selectedWorkblocks = this.volunteers.find(i => i.id === id).workblocks
                        selectedWorkblocks.forEach(block => {
                            this.form.selection.push(block.id)
                        });
                    });
                this.editionMode = true
            },

            submit() {
                this.form.put('/volunteers/' + this.form.id, {
                    preserveScroll: true,
                    onSuccess: resoponse =>{
                        this.form.reset()
                        this.editionMode = false
                    },
                    onError: errors => {
                         Object.values(this.workplaces).forEach(workplace => {
                            let places = 0
                            workplace.workblocks.forEach((workblock) => {
                                places += workblock.volunteer_number - workblock.volunteers_count
                            });
                            workplace.places = places
                        })
                    },
                })
            },

            exportExcel(){
                axios.post('/admin/volunteers/to-excel', {responseType: 'text'})
                    .then(response => {
                        const url = response.data;
                        const link = document.createElement('a');
                        link.href = url;
                        link.setAttribute('download', 'Liste_Benevoles.xlsx'); //or any other extension
                        document.body.appendChild(link);
                        link.click();
                    })
            }

        },

        computed: {
            getFilteredVolunteers(){
                return this.volunteers.filter(volunteer => {
                    return  volunteer.first_name.toLowerCase().includes(this.searchQ.toLowerCase()) ||
                            volunteer.last_name.toLowerCase().includes(this.searchQ.toLowerCase()) ||
                            volunteer.mail.toLowerCase().includes(this.searchQ.toLowerCase()) ||
                            volunteer.phone.toLowerCase().includes(this.searchQ.toLowerCase());
                });
            }
        },

    }
</script>
<style>

</style>

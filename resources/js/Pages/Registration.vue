<template>
    <div class="container max-w-3xl mx-auto mt-2">
        <img class="mx-auto d-block" style="max-height: 250px;" :src="bannerUrl" alt="Logo" >
        <h2 class="mb-3 mt-4">Bienvenue!</h2>
        <div>
            C'est reparti !! Make it Move revient avec un nouveau concept, une incroyable Course de Garçons de Café. Évidemment une course ne s'organise pas toute seule et nous recherchons des super bénévoles qui permettront à cet event de devenir réalité !<br><br>Inscris-toi via le formulaire ci-dessous ! Il n&#39;est évidemment pas interdit de prendre plusieurs tranches horaires et de rameuter tes amis pour les partager avec toi ! 😉<br>Si tu inscris plusieurs bénévoles, merci de bien utiliser une adresse email par personne pour s’assurer que tout le monde reçoive les infos importantes.<br><br>
            Si tu as des questions, n&#39;hésite pas à nous contacter à l&#39;adresse <a href="mailto:benevole@makeitmove.ch" target="_blank">benevoles@makeitmove.ch</a>.<br><br>Comme chaque année, sans toi et ta généreuse contribution, nos events déjantés ne pourraient pas avoir lieu. Pour ça, nous tenons à te remercier d&#39;avance ! 🙏🏼<br><br>À tout bientôt en ville 🏃‍♂️☀️🍻<br><br>Laetitia Brodard<br>Responsable bénévoles
        </div>

        <h2 class="mt-4">Informations personnelles</h2>
        <ValidationErrors></ValidationErrors>
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

            <h3 class="mt-4">Tranches horaires</h3>
            <div class="accordion mb-4" id="accordion">
                <template v-for="workplace in workplaces" :key="workplace.id">
                    <div class="accordion-item" v-if="(workplace.places) > 0">
                        <h4 class="accordion-header">
                            <button type="button" class="accordion-button collapsed fw-bold" data-bs-toggle="collapse" :data-bs-target="'#collapse_' + workplace.name.toLowerCase().replace(/ /g,'-').replace(/[^\w-]+/g,'')">{{ workplace.name }}</button>
                        </h4>
                        <div :id="'collapse_' + workplace.name.toLowerCase().replace(/ /g,'-').replace(/[^\w-]+/g,'')" class="accordion-collapse collapse" > <!-- Supress accordion effect data-bs-parent="#accordion" -->
                            <ul class="list-group list-group-flush">
                                <template v-for="workblock in workplace.workblocks" :key="workblock.id">
                                    <li class="list-group-item d-flex" v-if="workblock.volunteer_number > workblock.volunteers_count">
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
    import { useForm } from '@inertiajs/inertia-vue3'
    import ValidationErrors from "@/Components/ValidationErrors.vue";

    export default {
        components: {
            ValidationErrors,
        },
        props : {
            workplaces : {
                id : '',
                name : "" ,
                workblocks : []
            }
        },

        data(){
            return {
                form : useForm({
                    firstName : "",
                    lastName : "",
                    email : "",
                    phone : "",
                    shirtType : "",
                    size : null,
                    selection : [],
                }),
                bannerUrl : "",
            }
        },

        methods: {
            submit() {
                this.form.post('/volunteers', {
                    preserveScroll: true,
                    onError: errors => {
                         Object.values(this.workplaces).forEach(workplace => {
                            let places = 0
                            workplace.workblocks.forEach((workblock) => {
                                places += workblock.volunteer_number - workblock.volunteers_count
                            });
                            workplace.places = places
                        })
                    }
                })
            }
        },

        mounted() {
            Object.values(this.workplaces).forEach(workplace => {
                let places = 0
                workplace.workblocks.forEach((workblock) => {
                    places += workblock.volunteer_number - workblock.volunteers_count
                });
                workplace.places = places
            }),

            this.bannerUrl = window.location.origin + '/storage/MIM_LOGO_BLACK-1.png'
        },
    }
</script>

<style>

</style>

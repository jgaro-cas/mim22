<template>
    <h1>Plages horaires</h1>

    <ul>
        <li v-for="workblock in actualWorkblocks" :key="workblock.id">
            <WorkblockDetail :workblock="workblock" :workplaces="workplaces" :isWorkplaceList="false" @delete="deleteWB"></WorkblockDetail>
        </li>
    </ul>


    <form @submit.prevent="submitNewWB">
        <h2>Créer nouveau block de travail</h2>
        <label for="newWBstartDate">Heure de début</label>
        <Datepicker id="newWBstartDate" v-model="newWBForm.block_start" :format="'dd-MM-yyyy HH:mm'" selectText="Ok" cancelText="Annuler" minutesGridIncrement="15"></Datepicker>

        <label for="newWBstopDate">Heure de fin</label>
        <Datepicker id="newWBstopDate" v-model="newWBForm.block_stop" :format="'dd-MM-yyyy HH:mm'" selectText="Ok" cancelText="Annuler" minutesGridIncrement="15"></Datepicker>

        <label for="newWBworkplaceID">Workplace</label>
        <select name="newWBworkplaceId" id="newWBworkplaceId" v-model="newWBForm.workplace_id">
            <option v-for="place in workplaces" :key="place.id" :value="place.id">{{place.name}}</option>
        </select>

        <label for="volunteerNumber">Nombre de volontaire</label>
        <input class="mr-2" type="number" name="newWBvolunteerNumber" id="newWBvolunteerNumber" v-model="newWBForm.volunteer_number">
        <ValidationErrors></ValidationErrors>
        <BreezeButton>Créer</BreezeButton>
    </form>


</template>

<script>
    import { useForm } from '@inertiajs/inertia-vue3'
    import { Inertia } from '@inertiajs/inertia'
    import BreezeButton from '@/Components/Button.vue';
    import WorkblockDetail from "@/Components/VolunteerManagement/WorkblockDetail.vue";
    import ValidationErrors from "@/Components/ValidationErrors.vue";
    import Datepicker from '@vuepic/vue-datepicker';
    import '@vuepic/vue-datepicker/dist/main.css';

    export default {
        components: {
            BreezeButton,
            WorkblockDetail,
            ValidationErrors,
            Datepicker,
        },

        props: {
            workblocks: {
                type: Object,
                default: {}
            },
            workplaces: {
                type: Object,
                default: {}
            },
        },

        mounted() {
            this.actualWorkblocks = this.workblocks
        },

        data() {
            return {
                actualWorkblocks : {},
                newWBForm : useForm({
                    block_start: null,
                    block_stop : null,
                    workplace_id : null,
                    volunteer_number : null,
                    timezone : null
                }),
                edit : false,
            }
        },

        methods: {
            submitNewWB() {
                this.newWBForm.timezone = Intl.DateTimeFormat().resolvedOptions().timeZone
                this.newWBForm.post('/workblocks', {
                    onSuccess : (page) => {
                        this.actualWorkblocks = page.props.workblocks
                        //this.newWBForm.reset()
                    }
                })
            },

            deleteWB(id){
                Inertia.delete('/workblocks/' + id, {
                    onSuccess : (page) => {
                        this.actualWorkblocks = page.props.workblocks
                    }
                })
            },

            // updateWB(workblock) {
            //     workblock.timezone = Intl.DateTimeFormat().resolvedOptions().timeZone
            //     Inertia.put('/workblocks/' + workblock.id, workblock, {
            //         onSuccess : (page) => {
            //             this.actualWorkblocks = page.props.workblocks
            //         },
            //         onError : (page) => {
            //             console.log(page)
            //             this.actualWorkblocks = this.workblocks
            //         },
            //     })
            // },
        },

    }
</script>

<style>

</style>
